package eu.tsalliance.tsmedia.exception;

import org.springframework.http.HttpStatus;

import java.util.Collections;
import java.util.Map;

public class BadBodyException extends ApiException {

    public BadBodyException(String[] bodyFieldsWithErrors) {
        super("There were errors in some fields of the provided request body.", Collections.singletonMap("fields", bodyFieldsWithErrors) , HttpStatus.BAD_REQUEST);
    }

    public BadBodyException(Map<String, String> fields) {
        super("There were errors in some fields of the provided request body.", Collections.singletonMap("fields", fields) , HttpStatus.BAD_REQUEST);
    }

    public BadBodyException() {
        super("There were some mismatches in data types of some fields in the provided request body.", HttpStatus.BAD_REQUEST);
    }

    @Override
    protected String getErrorCode() {
        return "BAD_REQUEST";
    }
}
