<?
namespace App\Endpoint\v1;

class UserEndpoint extends Endpoint {

    function process() {
        $database = \App\Models\Database::getInstance();

        if(!$database->hasConnection()){
            throw new \Exception('database unavailable');
        }

        $request = \App\Models\Request::getInstance();
        
        if($request->getMethod() === 'POST') {
            $this->create();
        } else {
            if(isset($request->query()[2])) {
                $this->getUser($request->query()[2]);
            } else {
                $this->getCurrent();
            }
        }
    }

    /**
     * @api {get} /user Get current user
     * @apiDescription Requests the user data of the authenticated request.
     * @apiGroup User
     * @apiName Get current user data
     * @apiUse ApiError
     * 
     * @apiSuccess {Object} data Object containing profile info.
     * @apiSuccess {String} data.id UUID of the user.
     * @apiSuccess {String} data.name Name of the user.
     * @apiSuccess {String} data.group Permission-GroupID of the user.
     * 
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": {
     *          "code": 200,
     *          "message": "OK"
     *      },
     *      "data": {
     *          "id": "aa337788-ab51-4476-91e5-c7d07d98ca1c",
     *          "name": "John Doe",
     *          "group": "edfa989c-356c-453e-9eac-a3e5cf569bc1"
     *      }
     *  }
     * 
     * @apiHeader {String} Authorization User's unique access-token (Bearer).
     * @apiVersion 1.0.0
     */
    private function getCurrent() {
        
    }

    /**
     * @api {post} /user Register new user
     * @apiDescription Creates new user with given information
     * @apiGroup User
     * @apiName Create user
     * @apiUse ApiError
     * 
     * @apiParam {String} name Users name.
     * @apiParam {String} password Users password.
     * 
     * @apiSuccess {Object} data Object containing profile info.
     * @apiSuccess {String} data.id UUID of the user.
     * @apiSuccess {String} data.name Name of the user.
     * @apiSuccess {String} data.group Permission-GroupID of the user.
     * 
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": {
     *          "code": 200,
     *          "message": "OK"
     *      },
     *      "data": {
     *          "id": "aa337788-ab51-4476-91e5-c7d07d98ca1c",
     *          "name": "John Doe",
     *          "group": "edfa989c-356c-453e-9eac-a3e5cf569bc1"
     *      }
     *  }
     * 
     * @apiHeader {String} Authorization User's unique access-token (Bearer).
     * @apiVersion 1.0.0
     */
    private function create() {
        $database = \App\Models\Database::getInstance();

        
    }

     /**
     * @api {get} /user/:id Get user by id
     * @apiDescription Requests user data of user with matching id.
     * @apiGroup User
     * @apiName Get user
     * @apiUse ApiError
     * 
     * @apiSuccess {Object} data Object containing profile info.
     * @apiSuccess {String} data.id UUID of the user.
     * @apiSuccess {String} data.name Name of the user.
     * @apiSuccess {String} data.group Permission-GroupID of the user.
     * 
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": {
     *          "code": 200,
     *          "message": "OK"
     *      },
     *      "data": {
     *          "id": "aa337788-ab51-4476-91e5-c7d07d98ca1c",
     *          "name": "John Doe",
     *          "group": "edfa989c-356c-453e-9eac-a3e5cf569bc1"
     *      }
     *  }
     * 
     * @apiHeader {String} Authorization User's unique access-token (Bearer).
     * @apiVersion 1.0.0
     */
    private function getUser() {

    }

    function requiresAuthenticated() {
        return false;
    }
}
?>