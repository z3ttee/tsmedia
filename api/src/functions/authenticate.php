<?
use App\Models\Database;

    function authenticate($token) {
        if(isset($token)) {
            $bearerCode = $token;
        } else {
            if(!isset(getallheaders()["Authorization"]) && !isset($_GET['access_token'])) {
                throw new \Exception("authorization header required");
            }
    
            if(isset($_GET['access_token'])) {
                $bearerCode = $_GET['access_token'];
            } else {
                $bearerCode = getallheaders()["Authorization"];
            }
        }
        
        $bearerCode = \str_replace("Bearer ", "", $bearerCode);
        $data = array();

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
        $data['userID'] = $result->id;

        $userProfile = Database::getInstance()->get('users', "id = '".$data['userID']."'");
        if($userProfile->count() > 0) {
            $userProfile = $userProfile->first();
            $data['permissionGroup'] = $userProfile->permissionGroup;
        }

        $currentTime = round(microtime(true) * 1000);
        if($expiry != -1 && $expiry <= $currentTime) {
            Database::getInstance()->delete('access_tokens', "token = '{$result->token}'");
            throw new \Exception("invalid access token");
        }

        $data['authToken'] = $bearerCode;
        $data['authenticated'] = true;
        return $data;
    }