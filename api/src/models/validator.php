<?
namespace App\Models;

class Validator {
    function validate(string $subject, array $params = array()) {
        if(empty($params)) return true;
        foreach($params as $param) {
            if($param == 'required' && empty($subject)) {
                return false;
            }
            if($param == 'name') {
                $regex = "/^[a-zA-Z0-9]{3,16}$/";
                return \preg_match($regex,$subject);
            }
            if($param == 'regex') {
                $regex = $params['regex'];
                return \preg_match($regex,$subject);
            }
            
            if($param == 'json') {
                json_decode($subject);
                return \json_last_error() == JSON_ERROR_NONE;
            }
        }

        return true;
    }
}