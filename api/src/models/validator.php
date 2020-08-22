<?
namespace App\Models;

use \App\Models\Config;
use \App\Models\Database;

class Validator {

    private $_lastError = null;

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
            if($param == 'uuid') {
                $regex = "/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i";
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
            if($param == 'number') {
                if(!\is_numeric($subject)) $this->_lastError = 'not a number';
                return \is_numeric($subject);
            }
            if($param == 'unique') {
                $mysqlTable = Config::get('mysql/prefix').$params['unique']['table'];
                $field = $params['unique']['field'];

                if(Database::getInstance()->exists($mysqlTable, "{$field} = '".\escape($subject)."'")) {
                    $this->_lastError = 'exists';
                    return false;
                } else {
                    return true;
                }
            }
            if($param == 'password') {
                $regex = "/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).{6,32}$/";

                $result = preg_match($regex, $subject);
                return $result == 1;
            }
        }

        return true;
    }

    function error() {
        return $this->_lastError;
    }
}