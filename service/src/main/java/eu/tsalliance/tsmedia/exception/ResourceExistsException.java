package eu.tsalliance.tsmedia.exception;

import org.springframework.http.HttpStatus;

public class ResourceExistsException extends ApiException {

    public ResourceExistsException(String fieldname, Object value) {
        super("A resource with value '" + value + "' in field '" + fieldname + "' already exists.", HttpStatus.BAD_REQUEST);

        this.putDetail("fieldname", fieldname);
        this.putDetail("value", value);
    }

    @Override
    protected String getErrorCode() {
        return "RESOURCE_EXISTS";
    }
}
