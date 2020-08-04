<?
namespace App\Endpoint\v1;

use App\Models\Response;
use App\Models\Validator;

class UserEndpoint extends Endpoint {

    function process() {
        $database = \App\Models\Database::getInstance();

        if(!$database->hasConnection()){
            throw new \Exception('database unavailable');
        }

        $request = \App\Models\Request::getInstance();
        
        if($request->getMethod() === 'POST') {
            $this->create();
        } else if($request->getMethod() === 'DELETE') {
            $this->delete();
        } else if($request->getMethod() === 'PUT') {
            $this->update();
        } else {
            if(isset($request->query()[2])) {
                $action = $request->query()[2];

                if($action == 'all') {
                    $this->getAll();
                } else {
                    $this->getUser($action);
                }

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
     * 
     * @apiUse CommonDoc
     * 
     * @apiSuccess {Object} data Object containing profile info.
     * @apiSuccess {String} data.id UUID of the user.
     * @apiSuccess {String} data.name Name of the user.
     * @apiSuccess {String} data.permissionGroup Permission-GroupID of the user.
     * @apiSuccess {Long} data.joined Date of creation of user's profile.
     * 
     * @apiError not_found The user was not found.
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

        $result = $database->get('users', array('id', '=', $id), array('id', 'name', 'joined', 'permissionGroup'));
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
     * 
     * @apiUse CommonDoc
     * @apiUse CommonSuccess
     * 
     * @apiParam {String} name Users name.
     * @apiParam {String} password Users password.
     * 
     * @apiError failed_to_get_default_group The default group couldnt be found in the database.
     * @apiError group_not_found The provided group does not exist.
     * @apiError name_already_exists The provided username does already exist.
     * @apiError not_created The user was not created because of an database error.
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
        $validator = new Validator();

        if(!$validator->validate($username, array('name'))) {
            throw new \Exception('input invalid: [name]');
        }

        $password = \password_hash(\escape($_POST["password"]), PASSWORD_BCRYPT);

        if(!isset($_POST["group"])) {
            $groupResult = $database->get('groups', array('name', '=', 'default'), array('id'));
            if($groupResult->count() == 0){
                throw new \Exception('failed to get default group');
            } else {
                $group = $groupResult->first()->id;
            }
        } else {
            $group = \escape($_POST["group"]);

            $groupResult = $database->get('groups', array('id', '=', $group), array('id'));
            if($groupResult->count() == 0){
                throw new \Exception('group not found');
            }
        }

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
            'permissionGroup' => $group,
            'joined' => (int) \microtime(true)*1000
        );

        if(!$database->insert('users', $profile)){
            throw new \Exception('not created');
        }
    }

     /**
     * @api {get} /user/:id Get user by id
     * @apiDescription Requests user data of user with matching id.
     * @apiGroup User
     * @apiName Get user
     * 
     * @apiUse CommonDoc
     * 
     * @apiParam {String} id ID of the requested user.
     * 
     * @apiSuccess {Object} data Object containing profile info.
     * @apiSuccess {String} data.id UUID of the user.
     * @apiSuccess {String} data.name Name of the user.
     * @apiSuccess {String} data.permissionGroup Permission-GroupID of the user.
     * @apiSuccess {Long} data.joined Date of creation of user's profile.
     * 
     * @apiError not_found The user was not found.
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
    private function getUser($id) {
        $database = \App\Models\Database::getInstance();
        $request = \App\Models\Request::getInstance();

        $result = $database->get('users', array('id', '=', $id), array('id', 'name', 'joined', 'permissionGroup'));
        if($result->count() == 0) {
            throw new \Exception('not found');
        }

        $result = \get_object_vars($result->first());
        Response::getInstance()->setData($result);
    }

    /**
     * @api {get} /user/all Get all
     * @apiDescription Requests all existing users in database.
     * @apiGroup User
     * @apiName Get all
     * 
     * @apiUse CommonDoc
     * 
     * @apiSuccess {Object[]} data Object containing profiles.
     * 
     * @apiError not_found There are no users in the database.
     * 
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": {
     *          "code": 200,
     *          "message": "OK"
     *      },
     *      "data": [
     *          {...},
     *          {...}, ...
     *      ]
     *  }
     * 
     * @apiHeader {String} Authorization User's unique access-token (Bearer).
     * @apiVersion 1.0.0
     */
    private function getAll() {
        $database = \App\Models\Database::getInstance();
        $request = \App\Models\Request::getInstance();

        $result = $database->get('users', array(), array('id', 'name', 'joined', 'permissionGroup'));
        if($result->count() == 0) {
            throw new \Exception('not found');
        }

        $result = $result->results();
        Response::getInstance()->setData($result);
    }

    /**
     * @api {delete} /user/:id Delete user
     * @apiDescription Delete a user matching the given id
     * @apiGroup User
     * @apiName Delete user
     * 
     * @apiUse CommonDoc
     * @apiUse CommonSuccess
     * 
     * @apiParam {String} id User's id.
     * 
     * @apiError not_found The user was not found.
     * @apiError not_deleted The user was not deleted because of a database error.
     * 
     * @apiHeader {String} Authorization User's unique access-token (Bearer).
     * @apiVersion 1.0.0
     */
    private function delete() {
        $request = \App\Models\Request::getInstance();
        $database = \App\Models\Database::getInstance();

        if(!isset($request->query()[2])) {
            throw new \Exception('missing required params');
        }

        $id = \escape($request->query()[2]);

        if(!$database->exists('users', array('id', '=', $id))) {
            throw new \Exception('not found');
        }

        $user = $database->get('users', array('id', '=', $id))->first();
        if($user->permissionGroup == '*') {
            throw new \Exception('no permission');
        }

        if(!$database->delete('users', array('id', '=', $id))){
            throw new \Exception('not deleted');
        }
    }

    /**
     * @api {put} /user/:id Update user
     * @apiDescription Update a user matching the given id
     * @apiGroup User
     * @apiName Update user
     * 
     * @apiUse CommonDoc
     * @apiUse CommonSuccess
     * 
     * @apiParam {String} id User's id.
     * @apiParam {String} name User's updated name (optional).
     * @apiParam {String} group User's updated group (optional).
     * 
     * @apiError not_found The user was not found.
     * @apiError nothing_to_update No parameters were specified to update.
     * @apiError not_updated The user was not updated because of a database error.
     * 
     * @apiHeader {String} Authorization User's unique access-token (Bearer).
     * @apiVersion 1.0.0
     */
    private function update() {
        $request = \App\Models\Request::getInstance();
        $database = \App\Models\Database::getInstance();
        parse_str(file_get_contents("php://input"),$data);

        if(!isset($request->query()[2])) {
            throw new \Exception('missing required params');
        }

        $id = \escape($request->query()[2]);

        if(!$database->exists('users', array('id', '=', $id))) {
            throw new \Exception('not found');
        }

        $validator = new Validator();
        $profile = array();

        if(isset($data['group'])) {
            // Check for uuid
            $group = escape($data['group']);
            if(!$validator->validate($group, array('regex' => "/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i"))) throw new \Exception('input invalid: [group]');
            $profile['permissionGroup'] = $group;
        }
        if(isset($data['name'])) {
            $name = \escape($data['name']);
            if(!$validator->validate($name, array('name'))) throw new \Exception('input invalid: [name]');
            $profile['name'] = $name;
        }
        if(isset($data['password'])) {
            $profile['password'] = \password_hash(\escape($data['password']), PASSWORD_BCRYPT);
        }

        if(empty($profile)) {
            throw new \Exception('nothing to update');
        }

        if(!$database->update('users', array('id', '=', $id), $profile)){
            throw new \Exception('not updated');
        }
    }

    function requiresAuthenticated() {
        return true;
    }
}
?>