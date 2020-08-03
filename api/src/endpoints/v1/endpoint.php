<?
namespace App\Endpoint\v1;

abstract class Endpoint {
    /**
     * @apiDefine CommonDoc
     * @apiError \"database unavailable\" Cannot connect to database to retrieve data.
     * @apiError \"missing required params\" Cannot connect to database to retrieve data.
     * 
     * @apiErrorExample {json} Error-Response:
     *      {
     *          "status": {
     *              "code": 400,
     *              "message": "..."
     *          },
     *          "data": []
     *      }
     */
    /**
     * @apiDefine CommonSuccess
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": {
     *          "code": 200,
     *          "message": "OK"
     *      },
     *      "data": []
     *  }
     */
    abstract function process();
    abstract function requiresAuthenticated();
}
?>