import { Controller, ControllerFlag, ControllerPermission } from "alliance-rest/lib/controller/controller";
import { Page } from "alliance-rest/lib/pagination/page";
import { CurrentRoute } from "alliance-rest/lib/router/currentRoute"

export default class CategoryController extends Controller {

    constructor() {
        super(
            [
                ControllerFlag.FLAG_AUTHENTICATION_REQUIRED
            ],
            [
                new ControllerPermission("alliance.members.read", "getOne"),
                new ControllerPermission("alliance.members.read", "getMultiple"),
                new ControllerPermission("alliance.members.write", "createOne"),
                new ControllerPermission("alliance.members.write", "updateOne"),
                new ControllerPermission("alliance.members.write", "deleteOne")
            ]
        )
    }

    /**
     * @api {get} /members/:id Get account
     */
    public async actionGetOne(route: CurrentRoute): Promise<Object> {
        return null
    }

    /**
     * @api {get} /members Get multiple accounts
     */
    public async actionGetMultiple(route: CurrentRoute): Promise<Page<Object>> {
        return null
    }

    /**
     * @api {delete} /members/:id Delete account
     */
    /*public async actionDeleteOne(route: CurrentRoute): Promise<Response> {
        let targetUUID = route.params["id"];
        await MemberService.deleteMemberById(targetUUID);
        return EmptyResponse;
    }*/

    /**
     * @api {post} /members Create account
     */
    /*public async actionCreateOne(route: CurrentRoute): Promise<Member> {
        return MemberService.createMember(route.body);
    }*/

    /**
     * @api {put} /members/:uuid Update account
     */
    /*public async actionUpdateOne(route: CurrentRoute): Promise<Member> {
        let targetUUID = route.params["id"];

        if(!MemberService.canModifyMember(route.userDetails, targetUUID)) {
            throw new PermissionDeniedError();
        }

        return MemberService.updateMember(targetUUID, route.body);
    }*/

}