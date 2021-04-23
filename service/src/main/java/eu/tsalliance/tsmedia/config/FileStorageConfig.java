package eu.tsalliance.tsmedia.config;

import org.springframework.stereotype.Component;

import javax.annotation.PostConstruct;
import java.util.*;

@Component
public class FileStorageConfig {

    private String uploadDir = System.getProperty("user.dir") + "/uploads";

    public static final List<String> SUPPORTED_MIME_TYPES = new ArrayList<>(Arrays.asList(
            "image/png",
            "image/jpg",
            "image/jpeg",
            "video/mp4"
    ));

    private final Map<String, String> mimeTypeExtensions = new HashMap<>();

    @PostConstruct
    private void postConstruct() {
        this.mimeTypeExtensions.put("image/png", ".png");
        this.mimeTypeExtensions.put("image/jpg", ".jpg");
        this.mimeTypeExtensions.put("image/jpeg", ".jpeg");
        this.mimeTypeExtensions.put("video/mp4", ".mp4");
    }

    public Map<String, String> getMimeTypeExtensions() {
        return mimeTypeExtensions;
    }

    public String getUploadDir() {
        return uploadDir;
    }

    public void setUploadDir(String uploadDir) {
        this.uploadDir = uploadDir;
    }

    public boolean isSupportedMimetype(String mimeType) {
        return SUPPORTED_MIME_TYPES.contains(mimeType);
    }

    public List<String> getSUPPORTED_MIME_TYPES() {
        return SUPPORTED_MIME_TYPES;
    }
}
