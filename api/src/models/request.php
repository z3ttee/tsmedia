<?
namespace App\Models;

class Request {
    private static $_instance;
    private $_params,
            $_version,
            $_endpoint,
            $_query,
            $_authToken = null,
            $_userID = null,
            $_permissionGroup = null;

    public function __construct() {
        if($this->getMethod() != 'OPTIONS') {
            $query = explode("/",\explode('&', $_SERVER['QUERY_STRING'], 2)[0]);
            $query = \array_filter($query, function($element) {
                if(!\is_null($element) && !empty($element)) {
                    return $element;
                }
            });

            $this->_params = $_GET;
            $this->_query = $query;

            if(count($this->_params) < 1) {
                throw new \Exception("invalid endpoint");
            }

            $version = $query[0];

            if(is_null($version) || empty($version) || !startsWith($version, 'v')) {
                throw new \Exception("invalid api version");
            }

            $endpoint = \explode("&", $query[1])[0];

            if(is_null($endpoint) || empty($endpoint)) {
                throw new \Exception("invalid endpoint");
            }

            $this->_version = $version;
            $this->_endpoint = $endpoint;
        }
    }

    public function hasPermission($permission) {
        if(\is_null($this->_permissionGroup)) {
            return false;
        }

        // Root permission
        if($this->_permissionGroup === '*') {
            if(\is_array($permission)) {
                $checked = array();
                foreach ($permission as $p) {
                    $checked[$p] = true;   
                }
                return $checked;
            } else {
                return true;
            }
        }

        // Load permissions
        $result = Database::getInstance()->get('groups', "id = '{$this->_permissionGroup}'", array('permissions'));
        if($result->count() == 0){
            return false;
        }

        $permissions = \json_decode(unescape($result->first()->permissions), true);
        return \in_array(\strtolower($permission), $permissions);
    }

    public function getMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function params() {
        return $this->_params;
    }
    public function query() {
        return $this->_query;
    }
    public function authToken() {
        return $this->_authToken;
    }
    public function userID() {
        return $this->_userID;
    }
    public function permissionGroup() {
        return $this->_permissionGroup;
    }

    public function process() {
        $namespace = 'App\\Endpoint\\'.ucfirst($this->_version).'\\';

        $className = $namespace.ucfirst($this->_endpoint).'Endpoint';
        $endpoint = new $className();

        if(!$endpoint->requiresAuthenticated()) {
            $endpoint->process();
        } else {
            if($this->authenticate()) {
                $endpoint->process();
            } else {
                throw new \Exception("authentication required");
            }
        }
    }

    private function authenticate() {
        if(!isset(getallheaders()["Authorization"]) && !isset($_GET['access_token'])) {
            throw new \Exception("authorization header required");
        }

        if(isset($_GET['access_token'])) {
            $bearerCode = $_GET['access_token'];
        } else {
            $bearerCode = getallheaders()["Authorization"];
        }
        
        $bearerCode = \str_replace("Bearer ", "", $bearerCode);

        if(\is_null($bearerCode)) {
            throw new \Exception("invalid access token");
        }

        if(!Database::getInstance()->hasConnection()) {
            throw new \Exception("database unavailable");
        }

        // Table: userID / token / expiry
        $result = Database::getInstance()->get('access_tokens', "token = '{$bearerCode}'");
        if(Database::getInstance()->error() || Database::getInstance()->count() == 0) {
            throw new \Exception("invalid access token");
        }

        $result = $result->first();
        $expiry = $result->expiry;
        $this->_userID = $result->id;

        $userProfile = Database::getInstance()->get('users', "id = '{$this->_userID}'");
        if($userProfile->count() > 0) {
            $userProfile = $userProfile->first();
            $this->_permissionGroup = $userProfile->permissionGroup;
        }

        $currentTime = round(microtime(true) * 1000);
        if($expiry != -1 && $expiry <= $currentTime) {
            Database::getInstance()->delete('access_tokens', "token = '{$result->token}'");
            throw new \Exception("invalid access token");
        }

        $this->_authToken = $bearerCode;
        return true;
    }

    public static function getInstance() {
        if(self::$_instance == null) self::$_instance = new Request();
        return self::$_instance;
    }
}
?>