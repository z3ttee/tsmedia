package eu.tsalliance.tsmedia.exception;

import org.springframework.http.HttpStatus;
import org.springframework.web.multipart.MultipartFile;

import java.util.HashMap;
import java.util.Map;

public class StorageException extends ApiException {

    private final Map<String, Object> details = new HashMap<>();

    public StorageException(MultipartFile file) {
        super("There were errors uploading a file", HttpStatus.BAD_REQUEST);

        this.putDetail("size", file.getSize());
        this.putDetail("name", file.getOriginalFilename());
    }

    public StorageException(Map<String, Object> details) {
        super("There were errors uploading a file", details, HttpStatus.BAD_REQUEST);
    }

    public Map<String, Object> getDetails() {
        return details;
    }

    @Override
    protected String getErrorCode() {
        return "STORAGE_ERROR";
    }
}
