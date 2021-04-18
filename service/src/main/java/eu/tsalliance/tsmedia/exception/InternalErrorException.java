package eu.tsalliance.tsmedia.exception;

import org.springframework.http.HttpStatus;

public class InternalErrorException extends ApiException {

    public InternalErrorException() {
        super("An internal server error occured.", HttpStatus.INTERNAL_SERVER_ERROR);
    }

    @Override
    protected String getErrorCode() {
        return "INTERNAL_ERROR";
    }
}
