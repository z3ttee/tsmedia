<?
namespace App\Endpoint\v1;
use App\Models\Response;

class AuthEndpoint extends Endpoint {

    private $_expiry = -1;
    
    function process() {
        $this->_expiry = ((int) \microtime(true)*1000) + (1000*60*60*24*7);
        $database = \App\Models\Database::getInstance();

        if(!$database->hasConnection()){
            throw new \Exception('database unavailable');
        }

        $request = \App\Models\Request::getInstance();

        if(isset($request->query()[2])) {
            if($request->query()[2] === 'refresh') {
                $this->refresh();
            } else if($request->query()[2] === 'logout') {
                $this->logout();
            }
        } else {
            $this->login();
        }
    }

    /**
     * @api {get} /auth Login
     * @apiDescription Logs an user in to receive an access token.
     * @apiGroup Authentication
     * @apiName Login
     * @apiUse ApiError
     * 
     * @apiParam {String} name User's name.
     * @apiParam {String} password User's password.
     * 
     * @apiSuccess {Object} access_token Access token of the user's session.
     * @apiSuccess {String} access_token.value Value of the token.
     * @apiSuccess {Long} access_token.expiry Expiration in milliseconds of the token.
     * @apiSuccess {Object} session_hash Hash of the user's session.
     * @apiSuccess {String} session_hash.value Hash value of the user's session.
     * @apiSuccess {Long} session_hash.expiry Expiry of the user's session in millis.
     * 
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": {
     *          "code": 200,
     *          "message": "OK"
     *      },
     *      "data": {
     *          "access_token": {
     *              "value": "3DoVTH2m76VakD7Q",
     *              "expiry": 1596475596000
     *          },
     *          "session_hash": {
     *              "value": "3DoVTH2m76VakD7Q",
     *              "expiry": 1596475596000
     *          },
     *      }
     *  }
     * 
     * @apiVersion 1.0.0
     */
    private function login() {
        if(!isset($_GET["name"]) && !isset($_GET["password"])) {
            throw new \Exception('Missing required params');
        }

        $database = \App\Models\Database::getInstance();
        $request = \App\Models\Request::getInstance();
        
        $username = \escape($_GET["name"]);
        $password = \escape($_GET["password"]);

        $result = $database->get('users', array('name', '=', $username), array('id','password'));
        if($result->count() == 0) {
            throw new \Exception('not found');
        }

        $profile = $result->first();
        if(!password_verify($password, $profile->password)) {
            throw new \Exception('wrong credentials');
        }

        $hash = \bin2hex(\random_bytes(16));
        $token = \bin2hex(\random_bytes(16));

        $accessToken = $database->get('access_tokens', array('id', '=', $profile->id));

        $tokenProfile = array(
            'id' => $profile->id,
            'token' => $token,
            'expiry' => $this->_expiry
        );
        $sessionProfile = array(
            'id' => $profile->id,
            'sessionHash' => $hash,
            'expiry' => $this->_expiry
        );

        if($accessToken->count() > 0) {
            $accessToken = $accessToken->first()->token;
            $tokenProfile['token'] = $accessToken;
        } else {
            if($accessToken->count() == 0 && !$database->insert('access_tokens', $tokenProfile)) {
                throw new \Exception('Could not create access_token');
            }
        }

        if(!$database->insert('sessions', $sessionProfile)) {
            throw new \Exception('Could not create session');
        }

        // Check for expired hashes + delete multiple sessions
        $oldSessions = $database->get('sessions', array('id', '=', $profile->id))->results();
        $expiredHashes = \array_filter($oldSessions, function($element) {
            if($element->expiry <= ((int) \microtime(true)*1000)) {
                return $element;
            }
        });

        if(count($expiredHashes) == 0 && count($oldSessions) > 6) {
            $expiredHashes = \array_slice($oldSessions, 6, count($oldSessions));
        }

        foreach ($expiredHashes as $session) {
            $database->delete('sessions', array('sessionHash', '=', $session->sessionHash));
        }
        
        Response::getInstance()->setData(array(
            'access_token' => array('value' => $tokenProfile['token'], 'expiry' => $this->_expiry),
            'session_hash' => array('value' => $sessionProfile['sessionHash'], 'expiry' => $this->_expiry)
        ));
    }

    /**
     * @api {get} /auth/refresh Login with session
     * @apiDescription Logs an user in using its session hash.
     * @apiGroup Authentication
     * @apiName Login with session
     * @apiUse ApiError
     * 
     * @apiParam {String} session_hash User's session hash.
     * 
     * @apiSuccess {Object} access_token Access token of the user's session.
     * @apiSuccess {String} access_token.value Value of the token.
     * @apiSuccess {Long} access_token.expiry Expiration in milliseconds of the token.
     * @apiSuccess {Object} session_hash Hash of the user's session.
     * @apiSuccess {String} session_hash.value Hash value of the user's session.
     * @apiSuccess {Long} session_hash.expiry Expiry of the user's session in millis.
     * 
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": {
     *          "code": 200,
     *          "message": "OK"
     *      },
     *      "data": {
     *          "access_token": {
     *              "value": "3DoVTH2m76VakD7Q",
     *              "expiry": 1596475596000
     *          },
     *          "session_hash": {
     *              "value": "3DoVTH2m76VakD7Q",
     *              "expiry": 1596475596000
     *          },
     *      }
     *  }
     * 
     * @apiVersion 1.0.0
     */
    private function refresh() {
        if(!isset($_GET["session_hash"])) {
            throw new \Exception('Missing required params');
        }

        $database = \App\Models\Database::getInstance();
        $request = \App\Models\Request::getInstance();
        
        $hash = \escape($_GET["session_hash"]);
        
        $sessionProfile = $database->get('sessions', array('sessionHash', '=', $hash), array('id', 'expiry'));
        if($sessionProfile->count() == 0) {
            throw new \Exception('not found');
        }

        $sessionProfile = $sessionProfile->first();
        $userID = $sessionProfile->id;

        $tokenProfile = $database->get('access_tokens', array('id', '=', $userID));
        if($tokenProfile->count() == 0) {
            $database->delete('sessions', array('sessionHash', '=', $hash));
            throw new \Exception('login required');
        }
        $tokenProfile = $tokenProfile->first();

        Response::getInstance()->setData(array(
            'access_token' => array(
                'value' => $tokenProfile->token,
                'expiry' => $tokenProfile->expiry+0
            ),
            'session_hash' => array(
                'value' => $hash,
                'expiry' => $sessionProfile->expiry+0
            )
        ));
    }

    /**
     * @api {get} /auth/refresh Logout a session
     * @apiDescription Logs an user out using its session hash.
     * @apiGroup Authentication
     * @apiName Logout a session
     * @apiUse ApiError
     * 
     * @apiParam {String} session_hash User's session hash.
     * 
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": {
     *          "code": 200,
     *          "message": "OK"
     *      },
     *      "data": []
     *  }
     * 
     * @apiVersion 1.0.0
     */
    private function logout() {
        if(!isset($_GET["session_hash"])) {
            throw new \Exception('Missing required params');
        }

        $database = \App\Models\Database::getInstance();
        $request = \App\Models\Request::getInstance();
        
        $hash = \escape($_GET["session_hash"]);

        if($database->exists('sessions', array('sessionHash', '=', $hash))){
            $database->delete('sessions', array('sessionHash', '=', $hash));
        }
        
        /*$sessionProfile = $database->get('sessions', array('sessionHash', '=', $hash), array('id', 'expiry'));
        if($sessionProfile->count() == 0) {
            return;
        }

        $sessionProfile = $sessionProfile->first();
        $userID = $sessionProfile->id;

        $tokenProfile = $database->get('access_tokens', array('id', '=', $userID));
        if($tokenProfile->count() == 0) {
            $database->delete('sessions', array('sessionHash', '=', $hash));
            throw new \Exception('login required');
        }
        $tokenProfile = $tokenProfile->first();

        Response::getInstance()->setData(array(
            'access_token' => array(
                'value' => $tokenProfile->token,
                'expiry' => $tokenProfile->expiry
            ),
            'session_hash' => array(
                'value' => $hash,
                'expiry' => $sessionProfile->expiry
            )
        ));*/
    }

    function requiresAuthenticated() {
        return false;
    }
}
?>