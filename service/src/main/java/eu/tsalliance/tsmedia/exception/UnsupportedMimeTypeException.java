package eu.tsalliance.tsmedia.exception;

import eu.tsalliance.tsmedia.config.FileStorageConfig;
import org.springframework.http.HttpStatus;

public class UnsupportedMimeTypeException extends ApiException {

    public UnsupportedMimeTypeException(String mimeType) {
        super("Unsupported mimetype: " + mimeType, HttpStatus.BAD_REQUEST);

        this.putDetail("supported", FileStorageConfig.SUPPORTED_MIME_TYPES);
        this.putDetail("received", mimeType);
    }

    @Override
    protected String getErrorCode() {
        return "UNSUPPORTED_MIME_TYPE";
    }
}
