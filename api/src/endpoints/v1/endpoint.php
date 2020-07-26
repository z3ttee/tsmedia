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
    abstract protected function process();
    abstract protected function requiresAuthenticated();
}
?>