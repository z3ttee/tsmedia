package eu.tsalliance.tsmedia.exception.handler;

import eu.tsalliance.tsmedia.exception.*;
import org.apache.tomcat.util.http.fileupload.impl.FileSizeLimitExceededException;
import org.apache.tomcat.util.http.fileupload.impl.SizeLimitExceededException;
import org.springframework.beans.factory.annotation.Autowired;
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

    /*@Autowired
    private FileStorageConfig storageConfig;*/

    public static final String ACCESS_DENIED_ERROR = "ACCESS_DENIED";
    public static final String BAD_SESSION_ERROR = "BAD_SESSION";

    @ExceptionHandler(ApiException.class)
    public Object handleApiException(HttpServletRequest request, ApiException exception) {
        return ResponseEntity.status(exception.getHttpStatus().value()).body(exception.getResponse());
    }

    @ExceptionHandler(Exception.class)
    public Object handleException(HttpServletRequest request, Exception exception) {
        exception.printStackTrace();
        return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR).body(new InternalErrorException().getResponse());
    }

    @ExceptionHandler({EntityNotFoundException.class})
    public Object handleNotFoundException(HttpServletRequest request, Exception exception) {
        return ResponseEntity.status(HttpStatus.NOT_FOUND).body(new NotFoundException().getResponse());
    }

    @Override
    protected ResponseEntity<Object> handleExceptionInternal(Exception exception, Object body, HttpHeaders headers, HttpStatus status, WebRequest request) {
        exception.printStackTrace();
        return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR).body(new InternalErrorException().getResponse());
    }

    @Override
    protected ResponseEntity<Object> handleHttpMessageNotWritable(HttpMessageNotWritableException exception, HttpHeaders headers, HttpStatus status, WebRequest request) {
        return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR).body(new InternalErrorException().getResponse());
    }

    @Override
    protected ResponseEntity<Object> handleHttpMessageNotReadable(HttpMessageNotReadableException exception, HttpHeaders headers, HttpStatus status, WebRequest request) {
        return ResponseEntity.status(HttpStatus.NOT_FOUND).body(new BadBodyException().getResponse());
    }

    @Override
    protected ResponseEntity<Object> handleMethodArgumentNotValid(MethodArgumentNotValidException exception, HttpHeaders headers, HttpStatus status, WebRequest request) {

        Map<String, String> fields = new HashMap<>();
        exception.getBindingResult().getAllErrors().forEach((err) -> {
            fields.put(((FieldError) err).getField(), err.getDefaultMessage());
        });

        return ResponseEntity.status(HttpStatus.NOT_FOUND).body(new BadBodyException(fields).getResponse());
    }

    @ExceptionHandler({SizeLimitExceededException.class, FileSizeLimitExceededException.class})
    public Object handleFileSizeExceeded(HttpServletRequest request, Exception exception) {
        long actualSize = 0;
        long permittedSize = 0;

        if(exception instanceof SizeLimitExceededException) {
            SizeLimitExceededException ex = (SizeLimitExceededException) exception;
            actualSize = ex.getActualSize();
            permittedSize = ex.getPermittedSize();
        }

        if(exception instanceof FileSizeLimitExceededException) {
            FileSizeLimitExceededException ex = (FileSizeLimitExceededException) exception;
            actualSize = ex.getActualSize();
            permittedSize = ex.getPermittedSize();
        }

        Map<String, Object> details = new HashMap<>();

        details.put("limit", permittedSize);
        details.put("size", actualSize);

        return ResponseEntity.status(HttpStatus.BAD_REQUEST).body(new StorageException(details).getResponse());
    }

    /*@ExceptionHandler(ValidationException.class)
    public ResponseEntity<ErrorResponseEntity> handleValidationException(HttpServletRequest request, ValidationException exception) {
        return new ResponseEntity<>(new ErrorResponseEntity(error, VALIDATION_ERROR_ID, exception), HttpStatus.OK);
    }

    @ExceptionHandler(StorageException.class)
    public ResponseEntity<ErrorResponseEntity> handleStorageException(HttpServletRequest request, StorageException exception) {
        Map<String, Object> error = new HashMap<>();

        error.put("details", exception.getDetails());
        return new ResponseEntity<>(new ErrorResponseEntity(error, STORAGE_ERROR, exception), HttpStatus.OK);
    }

    @ExceptionHandler(BadBodyException.class)
    public ResponseEntity<ErrorResponseEntity> handleBadBodyException(HttpServletRequest request, BadBodyException exception) {
        Map<String, Object> error = new HashMap<>();

        error.put("fields", exception.getBodyFieldsWithErrors());

        return new ResponseEntity<>(new ErrorResponseEntity(error, BAD_BODY_ERROR_ID, exception), HttpStatus.OK);
    }*/



    /*@ExceptionHandler(ResourceExistsException.class)
    public ResponseEntity<ErrorResponseEntity> handleNotFoundException(HttpServletRequest request, ResourceExistsException exception) {
        Map<String, Object> error = new HashMap<>();

        error.put("field", exception.getFieldname());
        error.put("value", exception.getValue());

        return new ResponseEntity<>(new ErrorResponseEntity(error, EXISTS_ERROR_ID, exception), HttpStatus.OK);
    }*/





    /*@ExceptionHandler(UnsupportedMimeTypeException.class)
    public ResponseEntity<ErrorResponseEntity> handleException(HttpServletRequest request, ResourceExistsException exception) {
        Map<String, Object> error = new HashMap<>();

        error.put("supported", this.storageConfig.getSUPPORTED_MIME_TYPES());

        return new ResponseEntity<>(new ErrorResponseEntity(error, EXISTS_ERROR_ID, exception), HttpStatus.OK);
    }*/
}
