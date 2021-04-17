package eu.tsalliance.tsmedia.exception;

public class ResourceExistsException extends Exception {

    private final String fieldname;
    private final Object value;

    public ResourceExistsException(String fieldname, Object value) {
        super("A resource with value '" + value + "' in field '" + fieldname + "' already exists.");

        this.fieldname = fieldname;
        this.value = value;
    }

    public String getFieldname() {
        return fieldname;
    }

    public Object getValue() {
        return value;
    }
}
