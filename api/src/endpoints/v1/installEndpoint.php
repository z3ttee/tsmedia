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
    }

    function requiresAuthenticated() {
        return false;
    }
}
?>