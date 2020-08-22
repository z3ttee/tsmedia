<?
namespace App\Endpoint\v1;

use App\Models\Response;
use App\Models\Request;
use App\Models\Database;
use App\Models\Validator;

class GroupEndpoint extends Endpoint {

    function process() {
        $database = Database::getInstance();

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
                } else {
                    $this->get($action);
                }
            } else {
                throw new \Exception('unrecognized action');
            }
        }
    }

    /**
     * @api {post} /group Create Group
     * @apiDescription Creates new group with given information
     * @apiGroup Group
     * @apiName Create Group
     * 
     * @apiUse CommonDoc
     * @apiUse CommonSuccess
     * 
     * @apiParam {String} name Group's name (min: 3; max: 16).
     * @apiParam {String} displayname Group's displayname (min: 3; max: 16).
     * @apiParam {Json-Array} permissions Group's permissions.
     * @apiParam {Int} hierarchy Group's permissions (optional) (min: 0; max: 1000).
     * 
     * @apiError invalid_json_format_for_permissions The json format provided by <code>permissions</code> is invalid.
     * @apiError name_exists The provided name already exists in the database.
     * @apiError not_created The group was not created because of an database error.
     * 
     * @apiHeader {String} Authorization User's unique access-token (Bearer).
     * @apiVersion 1.0.0
     * @apiPermission permission.groups.create
     */
    private function create() {
        $request = Request::getInstance();
        if(!$request->hasPermission('permission.panel') && !$request->hasPermission('permission.groups.create')) {
            throw new \Exception('no permission');
        }

        if(!isset($_POST["name"]) && !isset($_POST["displayname"]) && !isset($_POST["permissions"])) {
            throw new \Exception('missing required params');
        }

        if(isset($_POST["hierarchy"])) {
            $hierarchy = \escape($_POST["hierarchy"]);
        } else {
            $hierarchy = 0;
        }

        $database = Database::getInstance();
        $name = escape($_POST["name"]);
        $displayname = escape($_POST["displayname"]);
        $permissions = $_POST["permissions"];

        // Validate
        $validator = new Validator();
        if(!$validator->validate($permissions, array('json')) || \is_numeric($permissions) || !\startsWith($permissions, '[')) {
            throw new \Exception('Invalid json format for permissions');
        }

        if(!$validator->validate($name, array('required','name'))) {
            throw new \Exception('input invalid: [name]');
        }
        if(!$validator->validate($displayname, array('required','name'))) {
            throw new \Exception('input invalid: [displayname]');
        }
        if(!$validator->validate($hierarchy, array('number'))) {
            throw new \Exception('input invalid: [hierarchy]');
        }

        if($hierarchy < 0) $hierarchy = 0;
        if($hierarchy > 1000) $hierarchy = 1000;

        if($database->exists('groups', "name = '{$name}'")) {
            throw new \Exception('name exists');
        }

        $uuid = uuidv4();
        while($database->exists('groups', "id = '{$uuid}'")) {
            $uuid = uuidv4();
        }

        $profile = array(
            'id' => $uuid,
            'name' => $name,
            'displayname' => $displayname,
            'permissions' => \escape($permissions),
            'hierarchy' => $hierarchy
        );

        if(!$database->insert('groups', $profile)){
            throw new \Exception('not created');
        }
    }

    /**
     * @api {get} /group/all/?offset=...&limit=...(&ofIDs=[...])(&props=[...]) Get all
     * @apiDescription Requests all existing groups in database.
     * @apiGroup Group
     * @apiName Get all
     * 
     * @apiUse CommonDoc
     * 
     * @apiParam {Integer} offset Starting index (Optional) Default: <code>0</code>.     
     * @apiParam {String} limit Amount of items to retrieve (Optional) Default: <code>25</code>.
     * @apiParam {Json-Array} ofIDs Filter which groups to get (Optional).
     * @apiParam {Json-Array} props Filter which columns to get (Optional).
     * 
     * @apiSuccess {Object[]} data Object containing groups.
     * 
     * @apiError not_found There are no groups in the database.
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
     *                  "id": "b1f0b962-c04e-4fd7-a433-f8e9118d3d93",
     *                  "name": "test",
     *                  "displayname": "test1",
     *                  "permissions": "[]",
     *                  "hierarchy": "0"
     *              }
     *          ],
     *          "available": 1
     *      }
     *  }
     * 
     * @apiHeader {String} Authorization User's unique access-token (Bearer).
     * @apiVersion 1.0.0
     * @apiPermission permission.groups
     */
    private function getAll() {
        $request = Request::getInstance();
        $database = Database::getInstance();

        if(!$request->hasPermission('permission.panel') && !$request->hasPermission('permission.groups')) {
            throw new \Exception('no permission');
        }

        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;
        $limit = isset($_GET['limit']) ? $_GET['limit'] : 25;
        
        $props = array();
        if(isset($_GET['props'])) {
            $props = json_decode($_GET['props'],true);
        }

        $response = array('entries' => array());

        if(isset($_GET['ofIDs'])) {

            $ids = array();
            foreach(json_decode($_GET['ofIDs'],true) as $id) {
                array_push($ids, escape($id));
            }

            $whereClause = "id in ('".implode("','", $ids)."')";
            $limit = -1;

            $result = $database->get('groups', $whereClause, $props, escape($offset), escape($limit));
            if($result->count() == 0) {
                throw new \Exception('not found');
            }

            $response['entries'] = $result->results();
        } else {
            $result = $database->get('groups', '', $props, escape($offset), escape($limit));
            if($result->count() == 0) {
                throw new \Exception('not found');
            }
    
            $response['entries'] = $result->results();
        }

        $response['available'] = $database->amount('groups');        
        Response::getInstance()->setData($response);
    }    

    /**
     * @api {get} /group/:id Get group by ID
     * @apiDescription Requests group data with matching id.
     * @apiGroup Group
     * @apiName Get group by ID
     * 
     * @apiUse CommonDoc
     * 
     * @apiParam {String} id ID of requested group.
     * 
     * @apiSuccess {Object} data Object containing group info.
     * @apiSuccess {String} data.id UUID of group.
     * @apiSuccess {String} data.name Name of group.
     * @apiSuccess {String} data.displayname Displayname of group.
     * @apiSuccess {String[]} data.permissions Permissions of group.
     * 
     * @apiError not_found The group was not found.
     * 
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": {
     *          "code": 200,
     *          "message": "OK"
     *      },
     *      "data": {
     *          "id": "aa337788-ab51-4476-91e5-c7d07d98ca1c",
     *          "name": "default",
     *          "displayname": "default",
     *          "permissions": []
     *      }
     *  }
     * 
     * @apiHeader {String} Authorization User's unique access-token (Bearer).
     * @apiVersion 1.0.0
     * @apiPermission permission.groups
     */
    private function get($id) {
        $request = Request::getInstance();
        if(!$request->hasPermission('permission.panel') && !$request->hasPermission('permission.groups')) {
            throw new \Exception('no permission');
        }

        $database = Database::getInstance();
        $result = $database->get('groups', "id = '".escape($id)."'");
        if($result->count() == 0) {
            throw new \Exception('not found');
        }

        $result = $result->first();
        $result->permissions = \json_decode(\html_entity_decode($result->permissions), true);

        $result = \get_object_vars($result);
        Response::getInstance()->setData($result);
    }

    /**
     * @api {delete} /group/:id Delete group
     * @apiDescription Delete a group matching the given id
     * @apiGroup Group
     * @apiName Delete group
     * 
     * @apiUse CommonDoc
     * @apiUse CommonSuccess
     * 
     * @apiParam {String} id Group's id.
     * 
     * @apiError not_found The group was not found.
     * @apiError not_deleted The group was not deleted because of a database error.
     * 
     * @apiHeader {String} Authorization User's unique access-token (Bearer).
     * @apiVersion 1.0.0
     * @apiPermission permission.groups.delete
     */
    private function delete() {
        $request = Request::getInstance();
        if(!$request->hasPermission('permission.panel') && !$request->hasPermission('permission.groups.delete')) {
            throw new \Exception('no permission');
        }
        
        if(!isset($request->query()[2])) {
            throw new \Exception('missing required params');
        }

        $id = \escape($request->query()[2]);
        $database = Database::getInstance();

        if(!$database->exists('groups', "id = '{$id}'")) {
            throw new \Exception('not found');
        }

        $group = $database->get('groups', "id = '{$id}'")->first();
        if($group->name == 'default') {
            throw new \Exception('no permission');
        }

        // Find users with group
        $users = $database->get('users', "permissionGroup = '{$id}'", array('id'), 0, -1);

        if($users->count() > 0) {
            $users = $users->results();
            $userIDs = array();

            foreach($users as $user) {
                array_push($userIDs, $user->id);
            }

            $whereClause = "id = '".implode("' OR id = '", $userIDs)."'";
            
            $defaultGroup = $database->get('groups', "name = 'default'", array('id'))->first();
            if(!is_null($defaultGroup)) {
                $database->update('users', $whereClause, array('permissionGroup' => $defaultGroup->id));
            }
        }

        if(!$database->delete('groups', "id = '{$id}'")){
            throw new \Exception('not deleted');
        }
    }

    /**
     * @api {put} /group/:id Update group
     * @apiDescription Update a group matching the given id
     * @apiGroup Group
     * @apiName Update group
     * 
     * @apiUse CommonDoc
     * @apiUse CommonSuccess
     * 
     * @apiParam {String} id Group's id.
     * @apiParam {String} name Group's updated name (optional).
     * @apiParam {String} displayname Group's updated displayname (optional).
     * @apiParam {String} permissions Group's updated permissions (optional).
     * @apiParam {String} hierarchy Group's updated permissions (optional).
     * 
     * @apiError not_found The group was not found.
     * @apiError name_exists The group name already exists.
     * @apiError nothing_to_update No parameters were specified to update.
     * @apiError not_updated The group was not updated because of a database error.
     * @apiError invalid_json_format_for_permissions The provided json for permissions is invalid.
     * 
     * @apiHeader {String} Authorization User's unique access-token (Bearer).
     * @apiVersion 1.0.0
     * @apiPermission permission.groups.edit
     */
    private function update() {
        $request = Request::getInstance();
        if(!$request->hasPermission('permission.panel') && !$request->hasPermission('permission.groups.edit')) {
            throw new \Exception('no permission');
        }

        parse_str(file_get_contents("php://input"),$data);
        if(!isset($request->query()[2])) {
            throw new \Exception('missing required params');
        }

        $id = \escape($request->query()[2]);
        $database = Database::getInstance();

        if(!$database->exists('groups', "id = '{$id}'")) {
            throw new \Exception('not found');
        }

        $validator = new Validator();
        $profile = array();

        $isDefault = $database->get('groups', "id = '{$id}'")->first()->name == 'default';

        if(isset($data['name']) && !$isDefault) {
            $name = \escape($data['name']);
            if(!$validator->validate($name, array('name', 'unique' => array('table' => 'groups', 'field' => 'name')))) {
                if($validator->error() == 'exists') {
                    throw new \Exception('name exists');
                } else {
                    throw new \Exception('input invalid: [name]');
                }
            }
            $profile['name'] = $name;
        }
        if(isset($data['displayname'])) {
            $displayname = \escape($data['displayname']);
            if(!$validator->validate($displayname, array('name'))) throw new \Exception('input invalid: [displayname]');
            $profile['displayname'] = $displayname;
        }
        if(isset($data['permissions'])) {
            $permissions = $data['permissions'];
            if(!$validator->validate($permissions, array('json'))) throw new \Exception('invalid json format for permissions');
            $profile['permissions'] = \escape($permissions);
        }
        if(isset($data['hierarchy']) && !$isDefault) {
            $hierarchy = \escape($data['hierarchy']);
            if(!$validator->validate($hierarchy, array('number'))) throw new \Exception('input invalid: [hierarchy]');
            $profile['hierarchy'] = $hierarchy;
        }

        if($hierarchy < 0) $hierarchy = 0;
        if($hierarchy > 1000) $hierarchy = 1000;

        if(empty($profile)) {
            throw new \Exception('nothing to update');
        }

        if(!$database->update('groups', "id = '{$id}'", $profile)){
            throw new \Exception('not updated');
        }
    }

    function requiresAuthenticated() {
        return true;
    }
}
?>