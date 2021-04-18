package eu.tsalliance.tsmedia.exception;

import org.springframework.http.HttpStatus;

import java.util.Date;
import java.util.HashMap;
import java.util.Map;

public abstract class ApiException extends Exception {

    private final Map<String, Object> response = new HashMap<>();
    private final HttpStatus httpStatus;

    public ApiException(String message, HttpStatus httpStatus) {
        super(message);

        response.put("timestamp", new Date());
        response.put("error", this.getErrorCode());
        response.put("message", message);

        this.httpStatus = httpStatus;
    }

    public ApiException(String message, Map<String, Object> details, HttpStatus httpStatus) {
        super(message);

        response.put("timestamp", System.currentTimeMillis());
        response.put("error", this.getErrorCode());
        response.put("message", message);
        response.put("details", details);

        this.httpStatus = httpStatus;
    }

    protected abstract String getErrorCode();

    protected void putDetail(String key, Object value) {
        Map<String, Object> details = (Map<String, Object>) this.response.getOrDefault("details", new HashMap());
        details.put(key, value);

        this.response.put("details", details);
    }

    public Map<String, Object> getResponse() {
        return response;
    }
    public HttpStatus getHttpStatus() {
        return httpStatus;
    }
}
