<?
namespace App\Endpoint\v1;

abstract class Endpoint {
    /**
     * @apiDefine CommonDoc
     * @apiError database_unavailable Cannot connect to database to retrieve data.
     * @apiError missing_required_params Cannot connect to database to retrieve data.
     * @apiError invalid_access_token No access token provided or expired.
     * @apiError authentication_required Authorization is required to access endpoint.
     * @apiError authorization_header_required Authorization is required to access endpoint but no header was sent.
     * @apiError invalid_endpoint This endpoint does not exist
     * @apiError invalid_api_version The requested version does not exist
     * @apiError unrecognized_action The action for an endpoint is not supported.
     * @apiError no_permission Not enough permission to access endpoint
     * @apiError input_invalid:_[...] Input for that parameter is invalid or does not match the requirements
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