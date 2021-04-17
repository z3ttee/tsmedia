package eu.tsalliance.tsmedia.exception;

public class NotFoundException extends Exception {

    public NotFoundException() {
        super("This resource does not exist.");
    }
}
