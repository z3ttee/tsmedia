<?
namespace App\Endpoint\v1;

class InstallEndpoint extends Endpoint {

    /**
     * @api {get} /install First-time setup
     * @apiDescription Initiates first-time setup. If finished successfully the endpoint will get deleted.
     * @apiGroup Setup
     * @apiName Installation
     * @apiUse ApiError
     * @apiVersion 1.0.0
     */
    function process() {
        $database = \App\Models\Database::getInstance();

        if(!$database->hasConnection()){
            throw new \Exception('database unavailable');
        }

        $database->query("CREATE TABLE IF NOT EXISTS ".\App\Models\Config::get('mysql/prefix')."access_tokens(id VARCHAR(36) NOT NULL UNIQUE, token VARCHAR(254) NOT NULL UNIQUE, expiry BIGINT NOT NULL);");
        $database->query("CREATE TABLE IF NOT EXISTS ".\App\Models\Config::get('mysql/prefix')."users(id VARCHAR(36) NOT NULL UNIQUE, name VARCHAR(16) NOT NULL UNIQUE, password VARCHAR(254) NOT NULL, permissionGroup VARCHAR(36) NOT NULL, joined BIGINT);");
    }

    function requiresAuthenticated() {
        return false;
    }
}
?>