<?
namespace App\Endpoint\v1;

class UploadEndpoint extends Endpoint {
    function process() {
        $request = \App\Models\Request::getInstance();
        $database = \App\Models\Database::getInstance();

        if(!$database->hasConnection()){
            throw new \Exception('database unavailable');
        }

        if($request->getMethod() === 'POST') {
            if(isset($request->query()[2])) {
                $type = $request->query()[2];

                if($type == 'avatar') {
                    $this->uploadVideo();
                    return;
                } else if($type == 'video') {
                    $this->uploadVideo();
                    return;
                }
            }
        }

        throw new \Exception('invalid endpoint');
    }

    function uploadAvatar() {

    }

    /**
     * @api {post} /upload/video/ Upload video
     * @apiDescription Uploads a new video file
     * @apiGroup Upload
     * @apiName Upload video
     * 
     * @apiParam {File} file The file to upload (8GB max).
     * 
     * @apiUse CommonDoc
     * 
     * @apiSuccess {String} id UUID of the uploaded video on success.
     * 
     * @apiError too_large The provided file is to large.
     * @apiError not_uploaded An error occured when saving file.
     * @apiError unsupported_encoding The provided file has an invalid format or encoding.
     * 
     * @apiHeader {String} Authorization User's unique access-token (Bearer).
     * @apiVersion 1.0.0
     */
    function uploadVideo() {
        if(!isset($_FILES['file']) || empty($_FILES['file'])) {
            throw new \Exception('missing required params');
        }

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

        \var_dump($target_file);

        if(!\is_dir($target_dir) && !\mkdir($target_dir, 0777, true)) {
            throw new \Exception('not uploaded');
        }

        if(!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            throw new \Exception('not uploaded');
        }
    }

    function requiresAuthenticated() {
        return true;
    }
}
?>