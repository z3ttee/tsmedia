package eu.tsalliance.tsmedia.exception;

import org.springframework.http.HttpStatus;

public class BadSessionException extends ApiException {

    public BadSessionException() {
        super("Invalid session", HttpStatus.BAD_REQUEST);
    }

    @Override
    protected String getErrorCode() {
        return "INVALID_SESSION";
    }
}
