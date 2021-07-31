import { RouteMethod, RouteGroup } from "@tsalliance/rest"
import CategoryController from "../controller/categoriesController"

export const ROUTES: RouteGroup[] = [
    {
        handler: CategoryController,
        groupname: "categories",
        routes: [
            { name: "CategoriesGetAll", path: "/categories/", action: "getMultiple", method: RouteMethod.METHOD_GET, forcePagination: true },
            { name: "CategoriesGetOne", path: "/categories/:id", action: "getOne", method: RouteMethod.METHOD_GET },
            { name: "CategoriesCreateOne", path: "/categories", action: "createOne", method: RouteMethod.METHOD_POST },
            { name: "CategoriesUpdateOne", path: "/categories/:id", action: "updateOne", method: RouteMethod.METHOD_PUT },
            { name: "CategoriesDeleteOne", path: "/categories/:id", action: "deleteOne", method: RouteMethod.METHOD_DELETE }
        ]
    },
    /*{
        handler: InviteController,
        groupname: "invites",
        routes: [
            { name: "InviteGetAll", path: "/invites/", action: "getMultiple", method: RouteMethod.METHOD_GET, forcePagination: true },
            { name: "InviteGetOne", path: "/invites/:id", action: "getOne", method: RouteMethod.METHOD_GET },
            { name: "InviteCreateOne", path: "/invites", action: "createOne", method: RouteMethod.METHOD_POST },
            { name: "InviteDeleteOne", path: "/invites/:id", action: "deleteOne", method: RouteMethod.METHOD_DELETE },
            { name: "InviteSendOne", path: "/invites/send", action: "sendInvite", method: RouteMethod.METHOD_POST }
        ]
    },
    {
        handler: AppController,
        groupname: "apps",
        routes: [
            { name: "AppGetAll", path: "/apps/", action: "getMultiple", method: RouteMethod.METHOD_GET, forcePagination: true },
            { name: "AppGetOne", path: "/apps/:id", action: "getOne", method: RouteMethod.METHOD_GET },
            { name: "AppCreateOne", path: "/apps", action: "createOne", method: RouteMethod.METHOD_POST },
            { name: "AppDeleteOne", path: "/apps/:id", action: "deleteOne", method: RouteMethod.METHOD_DELETE },
            { name: "AppUpdateOne", path: "/apps/:id", action: "updateOne", method: RouteMethod.METHOD_PUT }
        ]
    },
    {
        handler: AuthenticationController,
        groupname: "authentication",
        routes: [
            { name: "AuthLoginOne", path: "/auth/signin", action: "loginOne", method: RouteMethod.METHOD_POST },
        ]
    }*/
]