<?
namespace App\Endpoint\v1;

use App\Models\Request;
use App\Models\Database;
use App\Models\Config;
use App\Models\Response;

use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;

class VideoEndpoint extends Endpoint {
    function process() {
        $request = Request::getInstance();
        $database = Database::getInstance();

        if(!$database->hasConnection()){
            throw new \Exception('database unavailable');
        }

        if($request->getMethod() === 'POST') {
            if(isset($request->query()[2])) {
                $action = $request->query()[2];
                if($action == 'upload') {
                    $this->upload();
                    return;
                }
            }
        } else if($request->getMethod() === 'PUT') {
            if(isset($request->query()[2])) {
                $uuid = $request->query()[2];
                $this->updateVideo($uuid);
                return;
            }
        } else if($request->getMethod() === 'GET') {
            if(isset($request->query()[2])) {
                $action = $request->query()[2];
                if($action == 'all') {
                    $this->getAll();
                    return;
                } else if($action == 'latest') {
                    $this->getLatest();
                    return;
                } else if($action == 'watch') {
                    $this->watch();
                    return;
                } else {
                    $this->getVideo($action);
                    return;
                }
            }
        }

        throw new \Exception('invalid endpoint');
    }

    /**
     * @api {post} /video/upload/(?url=...) Upload video
     * @apiDescription Uploads a new video file
     * @apiGroup Upload
     * @apiName Upload video
     * 
     * @apiParam {File} file The file to upload (8GB max).
     * @apiParam {String} url An url pointing to a file to download.
     * 
     * @apiUse CommonDoc
     * 
     * @apiSuccess {String} id UUID of the uploaded video on success.
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": {
     *          "code": 200,
     *          "message": "OK"
     *      },
     *      "data": {
     *          "id": "4073c027-1330-49aa-8fc7-966b66360230"
     *      }
     *  }
     * 
     * @apiError too_large The provided file is to large.
     * @apiError not_uploaded An error occured when saving file.
     * @apiError video_exists File with same size, duration, title and creator already exists
     * @apiError unsupported_encoding The provided file has an invalid format or encoding.
     * 
     * @apiHeader {String} Authorization User's unique access-token (Bearer).
     * @apiVersion 1.0.0
     */
    function upload() {
        if(!isset($_FILES['file']) && !isset($_GET['url']) || empty($_FILES['file']) && !isset($_GET['url'])) {
            throw new \Exception('missing required params');
        }

        $request = Request::getInstance();
        $database = Database::getInstance();

        $target_dir = API_ROOT."/uploads/videos/";
        $thumbnail_dir = API_ROOT."/uploads/thumbnails/";
        $getID3 = new \getID3();
        $uuid = uuidv4();
        $mimeTypes = array('video/mpeg', 'video/mp4', 'video/ogg', 'video/webm', 'video/x-msvideo');
        $maxSize = 1000*1000*1000*1000*8; // 8GB max

        if(!\is_dir($target_dir) && !\mkdir($target_dir, 0777, true)) {
            throw new \Exception('not uploaded');
        }
        if(!\is_dir($thumbnail_dir)) {
            \mkdir($thumbnail_dir, 0777, true);
        }
        
        if(isset($_GET['url'])) {
            // Download from url

            $url = $_GET['url'];
            $tmpFile = \stream_get_meta_data(\tmpfile())['uri'];

            $bytesWritten = \file_put_contents($tmpFile, \fopen($url, 'r'));
            if(!$bytesWritten) {
                throw new \Exception('not uploaded');
            }
            
            $analysis = $getID3->analyze($tmpFile);
            $urlParts = \explode('/', $url);
            $title = \escape(\explode('.', $urlParts[count($urlParts)-1])[0]);
            $fileSize = $bytesWritten;
        } else {
            // Upload file
            $file = $_FILES['file'];

            $analysis = $getID3->analyze($file['tmp_name']);
            $title = escape(\explode('.', $file['name'])[0]);
            $fileSize = \escape($file['size']);
        }

        if($fileSize > $maxSize) {
            throw new \Exception('too large');
        }

        $mimeType = \escape($analysis['mime_type']);

        if(!\in_array($mimeType, $mimeTypes)) {
            throw new \Exception('unsupported encoding');
        }

        $path = $analysis['filenamepath'];
        $duration = escape((int)($analysis['playtime_seconds']*1000));
        $ext = \escape($analysis['fileformat']);
        $target_file = $target_dir.$uuid.'.'.$ext;
        $thumbnail_file = $thumbnail_dir.$uuid.".jpg";

        if(!\rename($path, $target_file)) {
            \unlink($path);
            throw new \Exception('not uploaded');
        }
        
        $info = array(
            'id' => $uuid,
            'title' => $title, 
            'description' => '',
            'duration' => $duration,
            'creator' => $request->userID(),
            'source' => $target_file,
            'visibility' => 0, // processing...
            'filesize' => $fileSize,
            'category' => '',
            'mimeType' => $mimeType,
            'created' => (int) \microtime(true) * 1000,
            'hash' => \hash('md5', $request->userID().$title.$fileSize.$duration)
        );

        if($database->exists('videos', "hash = '".$info['hash']."'")) {
            \unlink($target_file);
            throw new \Exception('video exists');
        }

        if(!$database->insert('videos', $info)) {
            \unlink($target_file);
            throw new \Exception('not uploaded');
        }

        $isWindows = false;
        if(strcasecmp(substr(PHP_OS, 0, 3), 'WIN') == 0){
            $isWindows = true;
        }

        $output = array();
        $returnval = 0;
        if($isWindows) {
            $ffmpeg_path = Config::get('ffmpeg/path').'/ffmpeg.exe';
        } else {
            $ffmpeg_path = "ffmpeg";
        }

        $command = $ffmpeg_path.' -y -i '.\realpath($target_file).' -vf "select=eq(n\,1)" -vframes 1 '.$thumbnail_file.' 2>&1';
        \exec($command, $output, $returnVal);

        $database->update('videos', "id = '".$info['id']."'", array('visibility' => 3));
        Response::getInstance()->setData($info);
    }

    /**
     * @api {put} /video/:id Update info
     * @apiDescription Update a video matching the given id
     * @apiGroup Video
     * @apiName Update info
     * 
     * @apiUse CommonDoc
     * @apiUse CommonSuccess
     * 
     * @apiParam {String} id Video's id.
     * @apiParam {String} title Video's updated title (optional).
     * @apiParam {String} description Video's updated description (optional).
     * @apiParam {Integer} visibility Video's updated visibility (optional) <code>[0 = processing; 1 = private; 2 = not listed; 3 = public]</code>.
     * @apiParam {String} category Video's updated category (optional).
     * 
     * @apiError not_found The user was not found.
     * @apiError nothing_to_update No parameters were specified to update.
     * @apiError not_updated The user was not updated because of a database error.
     * 
     * @apiHeader {String} Authorization User's unique access-token (Bearer).
     * @apiVersion 1.0.0
     * @apiPermission permission.users.edit
     */
    function updateVideo() {
        $request = Request::getInstance();
        $database = Database::getInstance();
    }

    /**
     * @api {get} /video/:id/?offset=...&limit=... Get info
     * @apiDescription Get info about a video matching the given id
     * @apiGroup Video
     * @apiName Get info
     * 
     * @apiUse CommonDoc
     * @apiUse CommonSuccess
     * 
     * @apiParam {String} id Video's id.
     * @apiParam {Integer} offset Starting index (Optional) Default: <code>0</code>.
     * @apiParam {String} limit Amount of items to retrieve (Optional) Default: <code>25</code>.
     * 
     * @apiError not_found No video were found.
     * 
     * @apiHeader {String} Authorization User's unique access-token (Bearer).
     * @apiVersion 1.0.0
     * @apiPermission permission.users.edit
     */
    function getAll() {
        $database = \App\Models\Database::getInstance();
        $request = Request::getInstance();

        $offset = isset($_GET['offset']) ? escape($_GET['offset']) : 0;
        $limit = isset($_GET['limit']) ? escape($_GET['limit']) : 25;

        if($limit > 15) $limit = 15;
        if($limit < 0) $limit = 1;
        
        $result = $database->get('videos', "visibility = '3'", array('id', 'title', 'description', 'duration', 'creator', 'visibility', 'category', 'created'), $offset, $limit);
        if($result->count() == 0) {
            throw new \Exception('not found');
        }

        $result = $result->results();
        $result = \array_filter($result, function($element){
            if($element->visibility == 3) return $element;
        });
        Response::getInstance()->setData($result);
    }

    /**
     * @api {get} /video/latest/ Get latest
     * @apiDescription Returns the latest 10 videos
     * @apiGroup Video
     * @apiName Get latest
     * 
     * @apiUse CommonDoc
     * @apiUse CommonSuccess
     * 
     * @apiError not_found No videos were found.
     * 
     * @apiHeader {String} Authorization User's unique access-token (Bearer).
     * @apiVersion 1.0.0
     */
    function getLatest() {
        $database = Database::getInstance();
        $request = Request::getInstance();
        
        $result = $database->get('videos', '', array(), 0, 10, 'created', 'DESC');
        if($result->count() == 0) {
            throw new \Exception('not found');
        }

        $result = $result->results();
        Response::getInstance()->setData($result);
    }

    /**
     * @api {get} /video/watch/:id Get resource
     * @apiDescription Requests the binary data for the resource
     * @apiGroup Video
     * @apiName Get resource
     * 
     * @apiUse CommonDoc
     * @apiUse CommonSuccess
     * 
     * @apiParam {String} id Video's id.
     * 
     * @apiError not_found The video was not found.
     * @apiError no_resource The video was not found on known path.
     * 
     * @apiHeader {String} Authorization User's unique access-token (Bearer).
     * @apiVersion 1.0.0
     * @apiPermission permission.users.edit
     */
    function watch() {
        $database = Database::getInstance();
        $request = Request::getInstance();

        if(!isset($request->query()[3])) {
            throw new \Exception('missing required params');
        }

        $id = \escape($request->query()[3]);
        $result = $database->get('videos', "id = '{$id}'", array(), 0, 1, 'created', 'DESC');
        if($result->count() == 0) {
            throw new \Exception('not found');
        }

        $file = $result->first();

        if(!\file_exists($file->source)) {
            throw new \Exception('no resource');
        }

        Response::getInstance()->setContentType($file->mimeType);
        header('Content-Type: '.$file->mimeType);
        header('Cache-Control: must-revalidate');
        header('Expires: 0');

        \readfile($file->source);
        exit;
    }

    function requiresAuthenticated() {
        return true;
    }
}
?>