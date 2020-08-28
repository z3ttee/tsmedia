<?
namespace App\Endpoint\v1;

class PingEndpoint extends Endpoint {
    function process() {}

    function requiresAuthenticated() {
        return false;
    }
    function authenticationOptional() {
        return false;
    }
}
?>