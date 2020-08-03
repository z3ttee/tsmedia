<?
namespace App\Endpoint\v1;

use App\Models\Response;
use App\Models\Validator;

class GroupEndpoint extends Endpoint {

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
     * 
     * @apiError invalid_json_format_for_permissions The json format provided by <code>permissions</code> is invalid.
     * @apiError input_invalid:_[PARAMETER_NAME] The format of the provided parameter is invalid.
     * @apiError name_already_exists The provided name already exists in the database.
     * @apiError group_not_created The group was not created because of an database error.
     * 
     * @apiHeader {String} Authorization User's unique access-token (Bearer).
     * @apiVersion 1.0.0
     */
    private function create() {
        $database = \App\Models\Database::getInstance();

        if(!isset($_POST["name"]) && !isset($_POST["displayname"]) && !isset($_POST["permissions"])) {
            throw new \Exception('missing required params');
        }

        $name = escape($_POST["name"]);
        $displayname = escape($_POST["displayname"]);
        $permissions = escape($_POST["permissions"]);

        // Validate
        $validator = new Validator();
        if(!$validator->validate($permissions, array('json')) || \is_numeric($permissions) || !\startsWith($permissions, '[')) {
            throw new \Exception('Invalid json format for permissions');
        }

        if(!$validator->validate($name, array('name'))) {
            throw new \Exception('input invalid: [name]');
        }
        if(!$validator->validate($displayname, array('name'))) {
            throw new \Exception('input invalid: [displayname]');
        }

        if($database->exists('groups', array('name', '=', $name))) {
            throw new \Exception('name already exists');
        }

        $uuid = uuidv4();
        while($database->exists('groups', array('id', '=', $uuid))) {
            $uuid = uuidv4();
        }

        $profile = array(
            'id' => $uuid,
            'name' => $name,
            'displayname' => $displayname,
            'permissions' => $permissions
        );

        if(!$database->insert('groups', $profile)){
            throw new \Exception('group not created');
        }
    }

    /**
     * @api {get} /group/all Get all
     * @apiDescription Requests all existing groups in database.
     * @apiGroup Group
     * @apiName Get all
     * 
     * @apiUse CommonDoc
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

        $result = $database->get('groups', array());
        if($result->count() == 0) {
            throw new \Exception('not found');
        }

        $result = $result->results();
        Response::getInstance()->setData($result);
    }

    function requiresAuthenticated() {
        return true;
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
     */
    private function get($id) {
        $database = \App\Models\Database::getInstance();
        $request = \App\Models\Request::getInstance();

        $result = $database->get('groups', array('id', '=', $id));
        if($result->count() == 0) {
            throw new \Exception('not found');
        }

        $result = \get_object_vars($result->first());
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
     */
    private function delete() {
        $request = \App\Models\Request::getInstance();
        $database = \App\Models\Database::getInstance();

        if(!isset($request->query()[2])) {
            throw new \Exception('missing required params');
        }

        $id = \escape($request->query()[2]);

        if(!$database->exists('groups', array('id', '=', $id))) {
            throw new \Exception('not found');
        }

        $group = $database->get('groups', array('id', '=', $id))->first();
        if($group->name == 'default') {
            throw new \Exception('no permission');
        }

        if(!$database->delete('groups', array('id', '=', $id))){
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
     * 
     * @apiError not_found The group was not found.
     * @apiError nothing_to_update No parameters were specified to update.
     * @apiError not_updated The group was not updated because of a database error.
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

        if(!$database->exists('groups', array('id', '=', $id))) {
            throw new \Exception('not found');
        }

        $validator = new Validator();
        $profile = array();

        if(isset($data['name'])) {
            $name = \escape($data['name']);
            if(!$validator->validate($name, array('name'))) throw new \Exception('input invalid: [name]');
            $profile['name'] = $name;
        }
        if(isset($data['displayname'])) {
            $displayname = \escape($data['displayname']);
            if(!$validator->validate($displayname, array('displayname'))) throw new \Exception('input invalid: [displayname]');
            $profile['displayname'] = $displayname;
        }
        if(isset($data['permissions'])) {
            $permissions = \escape($data['permissions']);
            if(!$validator->validate($permissions, array('json'))) throw new \Exception('invalid json format for permissions');
            $profile['permissions'] = $permissions;
        }

        if(empty($profile)) {
            throw new \Exception('nothing to update');
        }

        if(!$database->update('groups', array('id', '=', $id), $profile)){
            throw new \Exception('not updated');
        }
    }

}
?>