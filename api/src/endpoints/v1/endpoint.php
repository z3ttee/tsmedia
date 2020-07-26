<?
namespace App\Endpoint\v1;

abstract class Endpoint {
    /**
     * @apiDefine ApiError
     * @apiErrorExample {json} Error-Response:
     *      {
     *          "status": {
     *              "code": 400,
     *              "message": "..."
     *          },
     *          "data": []
     *      }
     */
    abstract function process();
    abstract function requiresAuthenticated();
}
?>