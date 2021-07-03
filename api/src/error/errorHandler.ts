import { ErrorHandler } from "alliance-rest/lib/error/errorHandler";
import { ApiError } from "alliance-sdk";
import { Request, Response } from "express";

import { InternalError } from "alliance-rest/lib/error/internalError"

export class ErrorHandlerImpl implements ErrorHandler {

    public handleError(error: ApiError, request: Request, response: Response) {
        if(!error.statusCode || !error["response"]) {
            console.log(error);
            error = new InternalError();
        }

        response.status(error.statusCode).json(error["response"]);
    }

}