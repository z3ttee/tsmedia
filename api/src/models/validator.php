<?
namespace App\Models;

use \App\Models\Config;
use \App\Models\Database;

class Validator {

    private $_lastError = null;

    function validate(string $subject, array $params = array()) {
        if(empty($params)) return true;
        foreach($params as $key => $param) {

            if($param == 'required' && empty($subject)) {
                return false;
            } else if($param == 'name') {
                $regex = "/^[a-zA-Z0-9]{3,16}$/";
                if(!\preg_match($regex,$subject)) {
                    return false;
                }
            } else if($param == 'uuid') {
                $regex = "/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i";
                if(!\preg_match($regex,$subject)) {
                    return false;
                }
            } else if($param == 'regex') {
                $regex = $params['regex'];
                if(!\preg_match($regex,$subject)) {
                    return false;
                }
            } else if($param == 'json') {
                json_decode($subject);
                if(\json_last_error() != JSON_ERROR_NONE) {
                    return false;
                }
            } else if($param == 'number') {
                if(!\is_numeric($subject)) {
                    $this->_lastError = 'not a number';
                    return false;
                }
            } else if($key == 'unique') {
                if(isset($params['unique'])) {
                    $mysqlTable = $params['unique']['table'];
                    $field = $params['unique']['field'];
                    $entryWhere = isset($params['unique']['entryWhere']) ? $params['unique']['entryWhere'] : null;

                    $subject = escape($subject);
                    $database = Database::getInstance();

                    // Check if value exists on earlier version of entry
                    $result = $database->exists($mysqlTable, "{$field} = '{$subject}'".(!is_null($entryWhere) ? ' AND '.$entryWhere : ''));

                    // Get amount of all values
                    $amount = $database->amount($mysqlTable, "{$field} = '{$subject}'");

                    if(!$result && $amount > 0) {
                        $this->_lastError = 'exists';
                        return false;
                    }
                }
            } else if($param == 'password') {
                $regex = "/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).{6,32}$/";

                $result = preg_match($regex, $subject);
                if($result != 1) {
                    return false;
                }
            }
        }

        return true;
    }

    function error() {
        return $this->_lastError;
    }
}