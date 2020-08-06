<?
namespace App\Endpoint\v1;

use App\Models\Request;
use App\Models\Database;
use App\Models\Response;

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
                    $this->getAllFiltered();
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
     * @api {post} /video/upload/ Upload video
     * @apiDescription Uploads a new video file
     * @apiGroup Upload
     * @apiName Upload video
     * 
     * @apiParam {File} file The file to upload (8GB max).
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
        if(!isset($_FILES['file']) || empty($_FILES['file'])) {
            throw new \Exception('missing required params');
        }

        $request = Request::getInstance();
        $database = Database::getInstance();

        $file = $_FILES['file'];
        $fileSize = $file['size'];
        $uuid = uuidv4();
        $maxSize = 1000*1000*1000*1000*8;   // 8GB max

        if($fileSize > $maxSize) {
            throw new \Exception('too large');
        }

        $mimeTypes = array('video/mpeg', 'video/mp4', 'video/ogg', 'video/webm', 'video/x-msvideo');
        $ext = '.'.\pathinfo($file['name'],PATHINFO_EXTENSION);

        if(!\in_array($file['type'], $mimeTypes)) {
            throw new \Exception('unsupported encoding');
        }

        $target_dir = "uploads/videos/";
        $target_file = $target_dir.$uuid.$ext;

        $getID3 = new \getID3();
        $analysis = $getID3->analyze($file['tmp_name']);

        $title = \explode('.', $file['name'])[0];
        $duration = (int)($analysis['playtime_seconds']*1000);

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
            'created' => (int) \microtime(true) * 1000,
            'hash' => \hash('md5', $request->userID().$title.$fileSize.$duration)
        );

        if($database->exists('videos', array('hash', '=', $info['hash']))) {
            throw new \Exception('video exists');
        }

        if(!$database->insert('videos', $info)) {
            throw new \Exception('not uploaded');
        }

        if(!\is_dir($target_dir) && !\mkdir($target_dir, 0777, true)) {
            $database->delete('videos', array('id', '=', $info['id']));
            throw new \Exception('not uploaded');
        }

        if(!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $database->delete('videos', array('id', '=', $info['id']));
            throw new \Exception('not uploaded');
        }

        $database->update('videos', array('id', '=', $info['id']), array('visibility' => 3));
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

    }

    function requiresAuthenticated() {
        return true;
    }
}
?>