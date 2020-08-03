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
        } /*else if($request->getMethod() === 'DELETE') {
            $this->delete();
        } else if($request->getMethod() === 'PUT') {
            $this->update();
        } else {
            $this->get();
        }*/
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
     * @apiError \"invalid json format for permissions\" The json format provided by <code>permissions</code> is invalid.
     * @apiError \"input invalid: [PARAMETER_NAME]\" The format of the provided parameter is invalid.
     * @apiError \"name already exists\" The provided name already exists in the database.
     * @apiError \"group not created\" The group was not created because of an database error.
     * 
     * @apiHeader {String} Authorization User's unique access-token (Bearer).
     * @apiVersion 1.0.0
     */
    private function create() {
        $database = \App\Models\Database::getInstance();

        if(!isset($_POST["name"]) && !isset($_POST["displayname"]) && !isset($_POST["permissions"])) {
            throw new \Exception('missing required params');
        }

        $name = $_POST["name"];
        $displayname = $_POST["displayname"];
        $permissions = $_POST["permissions"];

        // Validate
        $validator = new Validator();
        if(!$validator->validate($permissions, array('json')) || \is_numeric($permissions) || !\startsWith($permissions, '[')) {
            throw new \Exception('Invalid json format for permissions');
        }

        if(!$validator->validate($name, array('name'))) {
            throw new \Exception('input invalid: [name]');
        }
        if(!$validator->validate($displayname, array('name'))) {
            throw new \Exception('input invalid: [name]');
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
            'name' => \escape($name),
            'displayname' => \escape($displayname),
            'permissions' => \escape($permissions)
        );

        if(!$database->insert('groups', $profile)){
            throw new \Exception('group not created');
        }
    }

    function requiresAuthenticated() {
        return true;
    }
}
?>