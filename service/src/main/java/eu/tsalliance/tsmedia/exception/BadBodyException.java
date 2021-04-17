package eu.tsalliance.tsmedia.exception;

public class BadBodyException extends Exception {

    private String[] bodyFieldsWithErrors;

    public BadBodyException(String[] bodyFieldsWithErrors) {
        super("There were errors in some fields of the provided request body.");
        this.bodyFieldsWithErrors = bodyFieldsWithErrors;
    }

    public BadBodyException() {
        super("There were some mismatches in data types of some fields in the provided request body.");
    }

    public String[] getBodyFieldsWithErrors() {
        return bodyFieldsWithErrors;
    }
}
