package eu.tsalliance.tsmedia.exception.handler;

import eu.tsalliance.tsmedia.exception.BadBodyException;
import eu.tsalliance.tsmedia.exception.NotFoundException;
import eu.tsalliance.tsmedia.exception.ResourceExistsException;
import eu.tsalliance.tsmedia.exception.ValidationException;
import eu.tsalliance.tsmedia.exception.response.ErrorResponseEntity;
import org.springframework.http.HttpHeaders;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.http.converter.HttpMessageNotReadableException;
import org.springframework.http.converter.HttpMessageNotWritableException;
import org.springframework.validation.FieldError;
import org.springframework.web.bind.MethodArgumentNotValidException;
import org.springframework.web.bind.annotation.ControllerAdvice;
import org.springframework.web.bind.annotation.ExceptionHandler;
import org.springframework.web.context.request.WebRequest;
import org.springframework.web.servlet.mvc.method.annotation.ResponseEntityExceptionHandler;

import javax.persistence.EntityNotFoundException;
import javax.servlet.http.HttpServletRequest;
import java.util.HashMap;
import java.util.Map;

@ControllerAdvice
public class GlobalExceptionHandler extends ResponseEntityExceptionHandler {

    public static final String INTERNAL_ERROR_ID = "INTERNAL_ERROR";
    public static final String VALIDATION_ERROR_ID = "VALIDATION_ERROR";
    public static final String NOTFOUND_ERROR_ID = "NOT_FOUND";
    public static final String BAD_BODY_ERROR_ID = "BAD_REQUEST_BODY";
    public static final String EXISTS_ERROR_ID = "RESOURCE_EXISTS";
    public static final String BINDING_ERROR = "INVALID_DATA_TYPES";
    public static final String ACCESS_DENIED_ERROR = "ACCESS_DENIED";
    public static final String BAD_SESSION_ERROR = "BAD_SESSION";

    @Override
    protected ResponseEntity<Object> handleExceptionInternal(Exception exception, Object body, HttpHeaders headers, HttpStatus status, WebRequest request) {
        exception.printStackTrace();
        return new ResponseEntity<>(new ErrorResponseEntity(INTERNAL_ERROR_ID, exception), HttpStatus.OK);
    }

    @Override
    protected ResponseEntity<Object> handleMethodArgumentNotValid(MethodArgumentNotValidException exception, HttpHeaders headers, HttpStatus status, WebRequest request) {
        Map<String, Object> error = new HashMap<>();

        Map<String, String> fields = new HashMap<>();
        exception.getBindingResult().getAllErrors().forEach((err) -> {
            fields.put(((FieldError) err).getField(), err.getDefaultMessage());
        });

        error.put("fields", fields);
        return new ResponseEntity<>(new ErrorResponseEntity(error, BINDING_ERROR, exception), HttpStatus.OK);
    }

    @Override
    protected ResponseEntity<Object> handleHttpMessageNotReadable(HttpMessageNotReadableException exception, HttpHeaders headers, HttpStatus status, WebRequest request) {
        return new ResponseEntity<>(new ErrorResponseEntity(BINDING_ERROR, new BadBodyException()), HttpStatus.OK);
    }

    @ExceptionHandler(ValidationException.class)
    public ResponseEntity<ErrorResponseEntity> handleValidationException(HttpServletRequest request, ValidationException exception) {
        Map<String, Object> error = new HashMap<>();

        error.put("fieldname", exception.getRule().getFieldname());
        error.put("failedTests", exception.getRule().getFailedTests());
        error.put("requirements", exception.getRule().getRequirements());

        return new ResponseEntity<>(new ErrorResponseEntity(error, VALIDATION_ERROR_ID, exception), HttpStatus.OK);
    }

    @ExceptionHandler(BadBodyException.class)
    public ResponseEntity<ErrorResponseEntity> handleBadBodyException(HttpServletRequest request, BadBodyException exception) {
        Map<String, Object> error = new HashMap<>();

        error.put("fields", exception.getBodyFieldsWithErrors());

        return new ResponseEntity<>(new ErrorResponseEntity(error, BAD_BODY_ERROR_ID, exception), HttpStatus.OK);
    }

    @ExceptionHandler({NotFoundException.class, EntityNotFoundException.class})
    public ResponseEntity<ErrorResponseEntity> handleNotFoundException(HttpServletRequest request, Exception exception) {
        System.out.println("exception handler");
        return new ResponseEntity<>(new ErrorResponseEntity(NOTFOUND_ERROR_ID, new NotFoundException()), HttpStatus.OK);
    }

    @ExceptionHandler(ResourceExistsException.class)
    public ResponseEntity<ErrorResponseEntity> handleNotFoundException(HttpServletRequest request, ResourceExistsException exception) {
        Map<String, Object> error = new HashMap<>();

        error.put("field", exception.getFieldname());
        error.put("value", exception.getValue());

        return new ResponseEntity<>(new ErrorResponseEntity(error, EXISTS_ERROR_ID, exception), HttpStatus.OK);
    }

    @Override
    protected ResponseEntity<Object> handleHttpMessageNotWritable(HttpMessageNotWritableException ex, HttpHeaders headers, HttpStatus status, WebRequest request) {
        return new ResponseEntity<>(new ErrorResponseEntity(INTERNAL_ERROR_ID, new Exception()), HttpStatus.OK);
    }
}
