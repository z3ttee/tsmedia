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
     * @api {post} /upload/video Upload video
     * @apiDescription Uploads a new video file
     * @apiGroup Upload
     * @apiName Upload video
     * 
     * @apiUse CommonDoc
     * 
     * @apiSuccess {String} id UUID of the uploaded video on success.
     * 
     * @apiHeader {String} Authorization User's unique access-token (Bearer).
     * @apiVersion 1.0.0
     */
    function uploadVideo() {
        
    }

    function requiresAuthenticated() {
        return true;
    }
}
?>