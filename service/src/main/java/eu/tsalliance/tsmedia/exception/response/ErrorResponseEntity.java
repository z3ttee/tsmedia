package eu.tsalliance.tsmedia.exception.response;

import java.util.*;

public class ErrorResponseEntity {

    private final List<Map<String, Object>> errors;

    public ErrorResponseEntity(String errorId, Exception exception) {
        Map<String, Object> error = new HashMap<>();

        error.put("timestamp", new Date());
        error.put("message", exception.getMessage());
        error.put("errorId", errorId);

        this.errors = Collections.singletonList(error);
    }

    public ErrorResponseEntity(Map<String, Object> error, String errorId, Exception exception) {
        error.put("timestamp", new Date());
        error.put("message", exception.getMessage());
        error.put("errorId", errorId);

        this.errors = Collections.singletonList(error);
    }

    public List<Map<String, Object>> getErrors() {
        return errors;
    }

}
