package eu.tsalliance.tsmedia.exception;

import org.springframework.http.HttpStatus;

public class AccessDeniedException extends ApiException {
    public AccessDeniedException() {
        super("Access denied", HttpStatus.FORBIDDEN);
    }

    @Override
    protected String getErrorCode() {
        return "ACCESS_DENIED";
    }
}
