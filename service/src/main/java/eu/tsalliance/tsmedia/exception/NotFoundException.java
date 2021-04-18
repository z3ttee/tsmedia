package eu.tsalliance.tsmedia.exception;

import org.springframework.http.HttpStatus;

public class NotFoundException extends ApiException {

    public NotFoundException() {
        super("This resource does not exist.", HttpStatus.NOT_FOUND);
    }

    @Override
    protected String getErrorCode() {
        return "NOT_FOUND";
    }
}
