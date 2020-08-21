<?
namespace App\Models;
use PDO;

class Database {
    private static $_instance = null;
    private $_pdo,
            $_query,
            $_error = false,
            $_results,
            $_count = 0,
            $_connected = false;

    private function __construct() {
        try {
            $this->_pdo = new \PDO('mysql:host='.Config::get('mysql/host').';port='.Config::get('mysql/port').';dbname='.Config::get('mysql/database').';charset=utf8', Config::get('mysql/username'), Config::get('mysql/password'));
            $this->_connected = true;
        } catch (\PDOException $ex) {
            $this->_connected = false;
        }
    }

    public function hasConnection(){
        return $this->_connected;
    }

    public static function getInstance() {
        if (!isset(self::$_instance)) self::$_instance = new Database();
        return self::$_instance;
    }

    public function query($syntax, $params = array()) {
        if($this->hasConnection()) {
            $this->_error = false;

            if ($this->_query = $this->_pdo->prepare($syntax)) {
                $x = 1;
                if (count($params)) {
                    foreach ($params as $param) {
                        $this->_query->bindValue($x,$param);
                        $x++;
                    }
                }

                if ($this->_query->execute()) {
                    $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                    $this->_count = $this->_query->rowCount();
                } else {
                    $this->_error = true;
                }
            }
        } else {
            $this->_error = true;
        }
        return $this;
    }

    public function action($action, $table, string $where = '', $fields = array(), $offset = 0, $limit = 1, $orderField = null, $orderType = 'ASC') {
        if(empty($fields)) {
            $fields = '*';
        } else {
            $fields = implode(',',$fields);
        }

        $whereClause = (empty($where) ? '' : 'WHERE '.$where);

        if($action === "DELETE") {
            $sql = "DELETE FROM `".Config::get('mysql/prefix').$table."` {$whereClause};";
        } else {
            $sql = "{$action} {$fields} FROM `".Config::get('mysql/prefix').$table."` {$whereClause} ".($orderField != null ? 'ORDER BY `'.$orderField.'` '.$orderType : '')." ".($limit == -1 ? '' : "LIMIT {$offset},{$limit}").";";
        }

        if(!$this->query($sql)->error()){
            return $this;
        }

        return $this;
    }
    public function get($table, string $where, $fields = array(), $offset = 0, $limit = 1, $orderField = null, $orderType = 'ASC') {
        return $this->action('SELECT', $table, $where, $fields, $offset, $limit, $orderField, $orderType);
    }
    public function exists($table, string $where) {
        return ((int) $this->action('SELECT', $table, $where, array('COUNT(*) AS amount'))->first()->amount) > 0;
    }
    public function amount($table, string $where) {
        return ((int) $this->action('SELECT', $table, $where, array('COUNT(*) AS amount'))->first()->amount);
    }
    public function delete($table, string $where) {
        return !$this->action('DELETE', $table, $where)->error();
    }

    public function insert($table, $fields = array()) {
        $keys = array_keys($fields);
        $values = '';
        $x = 1;

        foreach ($fields as $field) {
            $values .= "?";
            if ($x < count($fields)) {
                $values .= ', ';
            }
            $x++;
        }

        $columns = implode("`,`",$keys);
        $sql = "INSERT INTO `".Config::get('mysql/prefix').$table."` (`".$columns."`) VALUES ({$values});";

        if (!$this->query($sql, $fields)->error()) {
            return true;
        }
        return false;
    }

    public function update($table, string $where, $params = array()) {

        if(!empty($where)) {
            $whereClause = 'WHERE '.$where;

            $set = '';
            $x = 1;

            foreach ($params as $name => $v) {
                $set .= "`{$name}` = ?";
                if ($x < count($params)) {
                    $set .= ', ';
                }
                $x++;
            }

            $sql = "UPDATE `".Config::get('mysql/prefix').$table."` SET {$set} {$whereClause};";
            if (!$this->query($sql, $params)->error()) {
                return true;
            }

            /*$operators = array('=','>','<','>=','<=');

            $field      = $where[0];
            $operator   = $where[1];
            $value      = $where[2];

            if (in_array($operator, $operators)) {
                

                \array_push($params, $value);

                
            }*/
        }
        return false;
    }

    public function results() {
        return $this->_results;
    }
    public function first() {
        return $this->results()[0];
    }
    public function error() {
        return $this->_error;
    }
    public function count() {
        return $this->_count;
    }
    public function errorInfo() {
        return $this->_query->errorInfo();
    }
}