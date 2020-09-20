<?
namespace App\Endpoint\V1;

use App\Models\Response;
use App\Models\Request;
use App\Models\Database;
use App\Models\Validator;

class UserEndpoint extends Endpoint {

    function process() {
        $database = \App\Models\Database::getInstance();

        if(!$database->hasConnection()){
            throw new \Exception('database unavailable');
        }

        $request = Request::getInstance();
        
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
                } else if($action == 'hasPermission') {
                    $this->hasPermission();
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
     *          "id": "cdd3a65e-c51e-444a-93eb-99ebef092f16",
     *          "name": "zettee",
     *          "joined": "1597862848000",
     *          "permissionGroup": "becd434c-023b-47c9-b83f-9416e2c62b94",
     *          "permissions": []
     *      }
     *  }
     * 
     * @apiHeader {String} Authorization User's unique access-token (Bearer).
     * @apiVersion 1.0.0
     */
    private function getCurrent() {
        $database = Database::getInstance();
        $request = Request::getInstance();

        $id = $request->userID();

        $result = $database->get('users', "id = '{$id}'", array('id', 'name', 'joined', 'permissionGroup'));
        if($result->count() == 0) {
            throw new \Exception('not found');
        }

        $user = $result->first();
        $permissionGroup = $user->permissionGroup;

        $user->hierarchy = 0;

        if($permissionGroup == '*') {
            $user->permissions = array('*');
        } else {
            $result = $database->get('groups', "id = '{$permissionGroup}'", array('permissions', 'hierarchy'));

            if($result->count() > 0) {
                $result = $result->first();
                $permissions = json_decode(unescape($result->permissions));
                $user->permissions = $permissions;
                $user->hierarchy = $result->hierarchy;
            }
        }

        if($permissionGroup == '*') {
            $user->hierarchy = 1000;
        } 

        Response::getInstance()->setData(\get_object_vars($user));
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
     * @apiParam {String} name User's name.
     * @apiParam {String} password User's password.
     * @apiParam {String} discordID User's discordID (Optional).
     * 
     * @apiError failed_to_get_default_group The default group couldnt be found in the database.
     * @apiError group_not_found The provided group does not exist.
     * @apiError name_exists The provided username does already exist.
     * @apiError discordID_exists The provided discordID does already exist.
     * @apiError not_created The user was not created because of an database error.
     * 
     * @apiHeader {String} Authorization User's unique access-token (Bearer).
     * @apiVersion 1.0.0
     * @apiPermission permission.users.create
     */
    private function create() {
        $request = Request::getInstance();
        if(!$request->hasPermission('permission.panel') && !$request->hasPermission('permission.users.create')) {
            throw new \Exception('no permission');
        }

        if(!isset($_POST["name"]) && !isset($_POST["password"])) {
            throw new \Exception('Missing required params');
        }

        $username = \escape($_POST["name"]);
        $password = \escape($_POST["password"]);
        $validator = new Validator();

        if(!$validator->validate($username, array('required','name', 'unique' => array('table' => 'users', 'field' => 'name')))) {
            throw new \Exception('input invalid: [name]');
        }
        if(!$validator->validate($password, array('requiured','password'))) {
            throw new \Exception('input invalid: [password]');
        }

        $password = \password_hash(\escape($_POST["password"]), PASSWORD_BCRYPT);
        $database = Database::getInstance();

        if(isset($_POST['discordID'])) {
            $discordID = escape($_POST['discordID']);
        } else {
            $discordID = null;
        }

        if(isset($_POST["group"])) {
            $group = \escape($_POST["group"]);

            $groupResult = $database->get('groups', "id = '{$group}'", array('id'));
            if($groupResult->count() == 0){
                throw new \Exception('group not found');
            }

            if(!$validator->validate($group, array('uuid'))) {
                throw new \Exception('input invalid: [name]');
            }
        }
        if(isset($_POST["discordID"])) {
            $discordID = \escape($_POST["discordID"]);
        }

        if($database->exists('users', "name = '{$username}'")) {
            throw new \Exception('name exists');
        }

        if(!is_null($discordID) && $database->exists('users', "discordID = '{$discordID}'")) {
            throw new \Exception('discordID exists');
        }

        $uuid = uuidv4();
        while($database->exists('users', "id = '{$uuid}'")) {
            $uuid = uuidv4();
        }

        $profile = array(
            'id' => $uuid,
            'name' => $username,
            'password' => $password,
            'joined' => (int) \microtime(true)*1000
        );
        if(!\is_null($discordID)) {
            $profile['discordID'] = $discordID;
        }
        if(isset($group)) {
            $profile['permissionGroup'] = $group;
        }
        if(isset($discordID)) {
            $profile['discordID'] = $discordID;
        }

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
     * @apiPermission permission.users
     */
    private function getUser($id) {
        $request = Request::getInstance();
        if(!$request->hasPermission('permission.panel') && !$request->hasPermission('permission.users')) {
            throw new \Exception('no permission');
        }

        $database = Database::getInstance();
        $result = $database->get('users', "id = '{$id}'", array('id', 'name', 'joined', 'permissionGroup', 'discordID'));
        if($result->count() == 0) {
            throw new \Exception('not found');
        }
        
        $result = \get_object_vars($result->first());
        Response::getInstance()->setData($result);
    }

    /**
     * @api {get} /user/all/?offset=...&limit=... Get all
     * @apiDescription Requests all existing users in database.
     * @apiGroup User
     * @apiName Get all
     * 
     * @apiUse CommonDoc
     * 
     * @apiParam {Integer} offset Starting index (Optional) Default: <code>0</code>.
     * @apiParam {String} limit Amount of items to retrieve (Optional) Default: <code>25</code>.
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
     *      "data": {
     *          "entries": [
     *              {
     *                  "id": "727a721c-ea49-4ea4-bcd7-33f85cb37c43",
     *                  "name": "admin",
     *                  "joined": "1597584131000",
     *                  "permissionGroup": "*",
     *                  "discordID": null
     *              }
     *          ],
     *          "available": 1
     *      }
     * 
     * @apiHeader {String} Authorization User's unique access-token (Bearer).
     * @apiVersion 1.0.0
     * @apiPermission permission.users
     */
    private function getAll() {
        $request = Request::getInstance();
        if(!$request->hasPermission('permission.panel') && !$request->hasPermission('permission.users')) {
            throw new \Exception('no permission');
        }

        $response = array('entries' => array());
        
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;
        $limit = isset($_GET['limit']) ? $_GET['limit'] : 25;

        if($limit > 15) $limit = 25;
        if($limit < 0) $limit = 1;

        $database = Database::getInstance();
        $result = $database->get('users', '', array('id', 'name', 'joined', 'permissionGroup', 'discordID'), escape($offset), escape($limit));
        if($result->count() == 0) {
            throw new \Exception('not found');
        }

        $response['entries'] = $result->results();
        $response['available'] = $database->amount('users');

        Response::getInstance()->setData($response);
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
     * @apiParam {String} byIDs Removes multiple users if they exist.
     * @apiParam {String} id User's id.
     * 
     * @apiError not_found The user was not found.
     * @apiError no_specific_permisison A specific user could not be deleted because of missing permission.
     * @apiError not_deleted The user was not deleted because of a database error.
     * 
     * @apiHeader {String} Authorization User's unique access-token (Bearer).
     * @apiVersion 1.0.0
     * @apiPermission permission.users.delete
     */
    private function delete() {
        $request = Request::getInstance();
        
        if(isset($_GET['byIDs'])) {
            $ids = json_decode($_GET['byIDs'],true);
            foreach($ids as $id) {
                $this->deleteSingle(escape($id), true);
            }
        } else {
            if(!isset($request->query()[2])) {
                throw new \Exception('missing required params');
            }

            $id = \escape($request->query()[2]);
            $this->deleteSingle($id, false);
        }
    }
    private function deleteSingle($id, $multiple) {
        $request = Request::getInstance();
        $database = Database::getInstance();

        if(!$database->exists('users', "id = '{$id}'")) {
            throw new \Exception('not found');
        }

        $user = $database->get('users', "id = '{$id}'", array('id', 'permissionGroup'))->first();
        
        if(!$request->hasPermission('permission.panel') || !$request->hasPermission('permission.users.delete')) {
            if($user->id != $request->userID()) {
                if($multiple) {
                    throw new \Exception('no specific permission');
                } else {
                    throw new \Exception('no permission');
                }
            }
        }

        if($user->permissionGroup == '*') {
            if($multiple) {
                throw new \Exception('no specific permission');
            } else {
                throw new \Exception('no permission');
            }
        }

        if(!$database->delete('users', "id = '{$id}'")){
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
     * @apiParam {String} password User's updated password (optional).
     * @apiParam {String} discordID User's updated discordID (optional).
     * 
     * @apiError not_found The user was not found.
     * @apiError not_found The user was not found.
     * @apiError higher_tier_required The target has a higher hierarchy and cannot be edited.
     * @apiError nothing_to_update No parameters were specified to update.
     * @apiError not_updated The user was not updated because of a database error.
     * 
     * @apiHeader {String} Authorization User's unique access-token (Bearer).
     * @apiVersion 1.0.0
     * @apiPermission permission.users.edit
     */
    private function update() {
        $request = Request::getInstance();
        
        if(!$request->hasPermission('permission.panel') || !$request->hasPermission('permission.users.edit')) {
            throw new \Exception('no permission');
        }

        $database = Database::getInstance();
        parse_str(file_get_contents("php://input"),$data);

        if(!isset($request->query()[2])) {
            throw new \Exception('missing required params');
        }

        $id = \escape($request->query()[2]);

        if(!$database->exists('users', "id = '{$id}'")) {
            throw new \Exception('not found');
        }

        $target = $database->get('users', "id = '{$id}'", array('id', 'permissionGroup'))->first();

        if($request->permissionGroup() != '*') {
            if($target->permissionGroup == '*') {
                $targetHierarchy = 1000;
            } else {
                $targetHierarchy = $database->get('groups', "id = '{$target->permissionGroup}'", array('hierarchy'))->first()->hierarchy ?: 0;
            }

            $performerHierarchy = $database->get('groups', "id = '".$request->permissionGroup()."'", array('hierarchy'))->first()->hierarchy ?: 0;

            if($target->id != $request->userID()) {
                if($targetHierarchy >= $performerHierarchy) {
                    throw new \Exception("higher tier required");
                }
            }
            
        } else {
            if($target->permissionGroup == '*') {
                if(isset($data['group'])) {
                    unset($data['group']);
                }
            }
        }

        $validator = new Validator();
        $profile = array();

        if(isset($data['discordID'])) {
            $discordID = escape($data['discordID']);
            $profile['discordID'] = $discordID;
        }
        if(isset($data['group'])) {
            // Check for uuid
            $group = escape($data['group']);
            if(!$validator->validate($group, array('uuid'))) throw new \Exception('input invalid: [group]');
            $profile['permissionGroup'] = $group;
        }
        if(isset($data['name'])) {
            $name = \escape($data['name']);
            if(!$validator->validate($name, array('name', 'unique' => array('table' => 'users', 'field' => 'name', 'entryWhere' => "id = '{$id}'")))) {
                if($validator->error() == 'exists') {
                    throw new \Exception('name exists');
                } else {
                    throw new \Exception('input invalid: [name]');
                }
            }
            $profile['name'] = $name;
        }
        if(isset($data['password'])) {
            $password = \escape($data['password']);
            if(!$validator->validate($password, array('password'))) throw new \Exception('input invalid: [password]');
            $profile['password'] = \password_hash($password, PASSWORD_BCRYPT);
        }
        if(isset($data['discordID'])) {
            $profile['discordID'] = \escape($data['discordID']);
        }

        if(empty($profile)) {
            throw new \Exception('nothing to update');
        }

        if(!$database->update('users', "id = '{$id}'", $profile)){
            throw new \Exception('not updated');
        }
    }

    /**
     * @api {get} /user/hasPermission/?permission=... Check permission
     * @apiDescription Checks if a user has right permission
     * @apiGroup User
     * @apiName Check permission
     * 
     * @apiUse CommonDoc
     * 
     * @apiParam {String || Json-Array} permission Single permission or array of permissions to check.
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
     *          {'permission': true},
     *          {'permission2': false}
     *      }
     *  }
     * 
     * @apiHeader {String} Authorization User's unique access-token (Bearer).
     * @apiVersion 1.0.0
     */
    private function hasPermission() {
        $database = Database::getInstance();
        $request = Request::getInstance();

        $id = $request->userID();

        $result = $database->get('users', "id = '{$id}'", array('permissionGroup'));
        if($result->count() == 0) {
            throw new \Exception('not found');
        }

        $permissionGroup = $result->first()->permissionGroup;
        if($permissionGroup == '*') {
            
        }

        $result = \get_object_vars($result->first());
        Response::getInstance()->setData($result);
    }

    function requiresAuthenticated() {
        return true;
    }
    function authenticationOptional() {
        return false;
    }
}
?>