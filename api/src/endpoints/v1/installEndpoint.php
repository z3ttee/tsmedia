<?
namespace App\Endpoint\v1;

class InstallEndpoint extends Endpoint {

    private $_adminUUID = null;
    private $_defCategoryID = null;
    private $_defGroupID = null;

    public function __construct() {
        $this->_adminUUID = uuidv4();
        $this->_defCategoryID = uuidv4();
        $this->_defGroupID = uuidv4();
    }

    /**
     * @api {get} /install First-time setup
     * @apiDescription Initiates first-time setup. If finished successfully the endpoint will get deleted.
     * @apiGroup Setup
     * @apiName Installation
     * @apiUse CommonDoc
     * @apiVersion 1.0.0
     */
    function process() {
        $database = \App\Models\Database::getInstance();

        if(!$database->hasConnection()){
            throw new \Exception('database unavailable');
        }

        $database->query("CREATE TABLE IF NOT EXISTS ".\App\Models\Config::get('mysql/prefix')."access_tokens(id VARCHAR(36) NOT NULL UNIQUE, token VARCHAR(254) NOT NULL UNIQUE, expiry BIGINT NOT NULL);");
        $database->query("CREATE TABLE IF NOT EXISTS ".\App\Models\Config::get('mysql/prefix')."users(id VARCHAR(36) NOT NULL UNIQUE, name VARCHAR(16) NOT NULL UNIQUE, password VARCHAR(254) NOT NULL, permissionGroup VARCHAR(36) DEFAULT '".$this->_defGroupID."', joined BIGINT NOT NULL, discordID VARCHAR(254) DEFAULT NULL);");
        $database->query("CREATE TABLE IF NOT EXISTS ".\App\Models\Config::get('mysql/prefix')."sessions(sessionHash VARCHAR(254) NOT NULL UNIQUE, id VARCHAR(36) NOT NULL, expiry BIGINT NOT NULL);");
        $database->query("CREATE TABLE IF NOT EXISTS ".\App\Models\Config::get('mysql/prefix')."groups(id VARCHAR(36) NOT NULL UNIQUE, name VARCHAR(16) NOT NULL UNIQUE, displayname VARCHAR(16) NOT NULL, permissions TEXT NOT NULL, hierarchy INT DEFAULT '0');");
        $database->query("CREATE TABLE IF NOT EXISTS ".\App\Models\Config::get('mysql/prefix')."videos(id VARCHAR(36) NOT NULL UNIQUE, title VARCHAR(254), description TEXT, duration BIGINT, mimeType VARCHAR(254) NOT NULL, creator VARCHAR(36) NOT NULL, source TEXT, visibility INT DEFAULT '0', filesize BIGINT, category VARCHAR(36) DEFAULT '".$this->_defCategoryID."', created BIGINT, hash VARCHAR(254) UNIQUE, views INT DEFAULT '0', favs INT DEFAULT '0');");
        $database->query("CREATE TABLE IF NOT EXISTS ".\App\Models\Config::get('mysql/prefix')."categories(id VARCHAR(36) NOT NULL UNIQUE, name VARCHAR(254), creator VARCHAR(36) NOT NULL, created BIGINT);");

        $this->createDefUser();
        $this->createDefGroup();
        $this->createDefCategory();
    }

    function createDefUser() {
        $database = \App\Models\Database::getInstance();

        $password = \password_hash('hackme', PASSWORD_BCRYPT);
        $username = 'admin';

        $profile = array(
            'id' => $this->_adminUUID,
            'name' => $username,
            'password' => $password,
            'permissionGroup' => '*',
            'joined' => (int) \microtime(true)*1000
        );

        if(!$database->exists('users', "permissionGroup = '*'")) {
            $database->insert('users', $profile);
        }
    }

    function createDefGroup() {
        $database = \App\Models\Database::getInstance();

        $name = 'default';

        $data = array(
            'id' => $this->_defGroupID,
            'name' => $name,
            'displayname' => $name,
            'permissions' => '[]',
            'hierarchy' => 0
        );

        if(!$database->exists('groups', "name = '{$name}'")) {
            $database->insert('groups', $data);
        }
    }

    function createDefCategory() {
        $database = \App\Models\Database::getInstance();

        $name = 'Uncategorized';

        $data = array(
            'id' => $this->_defCategoryID,
            'name' => $name,
            'creator' => $this->_adminUUID,
            'created' => (int) microtime(true)*1000
        );

        if(!$database->exists('categories', "name = '{$name}'")) {
            $database->insert('categories', $data);
        }
    }

    function requiresAuthenticated() {
        return false;
    }
    function authenticationOptional() {
        return false;
    }
}
?>