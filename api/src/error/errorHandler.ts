import { ErrorHandler, Errors } from "@tsalliance/rest";
import { ApiError } from "alliance-sdk";
import { Request, Response } from "express";

export class ErrorHandlerImpl implements ErrorHandler {

    public handleError(error: ApiError, request: Request, response: Response) {
        if(!error.statusCode || !error["response"]) {
            console.log(error);
            error = new Errors.InternalError();
        }

        response.status(error.statusCode).json(error["response"]);
    }

}