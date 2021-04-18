package eu.tsalliance.tsmedia.controller;

import eu.tsalliance.tsmedia.models.media.MediaFile;
import eu.tsalliance.tsmedia.service.FileStorageService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.security.core.Authentication;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RestController;
import org.springframework.web.multipart.MultipartFile;

@RestController
@RequestMapping("/api/v1/upload")
@PreAuthorize("isFullyAuthenticated()")
public class UploadController {

    @Autowired
    private FileStorageService fileStorageService;

    @PostMapping
    public MediaFile uploadFile(@RequestParam("file") MultipartFile file, Authentication authentication) throws Exception {
        return fileStorageService.storeFile(file, authentication);
    }

}
