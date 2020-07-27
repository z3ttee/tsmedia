<?
namespace App\Endpoint\v1;
use App\Models\Response;

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
     * @apiSuccess {String} data.permissionGroup Permission-GroupID of the user.
     * @apiSuccess {Long} data.joined Date of creation of user's profile.
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
     *          "permissionGroup": "edfa989c-356c-453e-9eac-a3e5cf569bc1",
     *          "joined": 1595839489632
     *      }
     *  }
     * 
     * @apiHeader {String} Authorization User's unique access-token (Bearer).
     * @apiVersion 1.0.0
     */
    private function getCurrent() {
        $database = \App\Models\Database::getInstance();
        $request = \App\Models\Request::getInstance();

        $id = $request->userID();

        $result = $database->get('users', array('id', '=', $id));
        if($result->count() == 0) {
            throw new \Exception('not found');
        }

        $result = \get_object_vars($result->first());
        Response::getInstance()->setData($result);
    }

    /**
     * @api {post} /user Create user
     * @apiDescription Creates new user with given information
     * @apiGroup User
     * @apiName Create user
     * @apiUse ApiError
     * 
     * @apiParam {String} name Users name.
     * @apiParam {String} password Users password.
     * 
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": {
     *          "code": 200,
     *          "message": "OK"
     *      }
     *  }
     * 
     * @apiHeader {String} Authorization User's unique access-token (Bearer).
     * @apiVersion 1.0.0
     */
    private function create() {
        $database = \App\Models\Database::getInstance();

        if(!isset($_POST["name"]) && !isset($_POST["password"])) {
            throw new \Exception('Missing required params');
        }

        $username = \escape($_POST["name"]);
        $password = \escape($_POST["password"]);

        $password = \password_hash($password, PASSWORD_BCRYPT);

        if($database->exists('users', array('name', '=', $username))) {
            throw new \Exception('name already exists');
        }

        $uuid = uuidv4();
        while($database->exists('users', array('id', '=', $uuid))) {
            $uuid = uuidv4();
        }

        $profile = array(
            'id' => $uuid,
            'name' => $username,
            'password' => $password,
            'permissionGroup' => 'null',
            'joined' => (int) \microtime(true)*1000
        );

        if(!$database->insert('users', $profile)){
            throw new \Exception('user not created');
        }
    }

     /**
     * @api {get} /user/:id Get user by id
     * @apiDescription Requests user data of user with matching id.
     * @apiGroup User
     * @apiName Get user
     * @apiUse ApiError
     * 
     * @apiParam {String} id ID of the requested user.
     * 
     * @apiSuccess {Object} data Object containing profile info.
     * @apiSuccess {String} data.id UUID of the user.
     * @apiSuccess {String} data.name Name of the user.
     * @apiSuccess {String} data.permissionGroup Permission-GroupID of the user.
     * @apiSuccess {Long} data.joined Date of creation of user's profile.
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
     *          "permissionGroup": "edfa989c-356c-453e-9eac-a3e5cf569bc1",
     *          "joined": 1595839489632
     *      }
     *  }
     * 
     * @apiHeader {String} Authorization User's unique access-token (Bearer).
     * @apiVersion 1.0.0
     */
    private function getUser() {
        $database = \App\Models\Database::getInstance();
        $request = \App\Models\Request::getInstance();

        $id = $request->query()[2];

        $result = $database->get('users', array('id', '=', $id));
        if($result->count() == 0) {
            throw new \Exception('not found');
        }

        $result = \get_object_vars($result->first());
        Response::getInstance()->setData($result);
    }

    function requiresAuthenticated() {
        return true;
    }
}
?>