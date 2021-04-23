package eu.tsalliance.tsmedia.controller;

import eu.tsalliance.tsmedia.config.FileStorageConfig;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.core.io.FileSystemResource;
import org.springframework.http.MediaType;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.stereotype.Controller;
import org.springframework.util.StreamUtils;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestMapping;

import javax.servlet.http.HttpServletResponse;
import java.io.File;
import java.io.IOException;
import java.nio.file.Path;

@Controller
@RequestMapping("/api/v1/resources")
public class ResourceController {

    @Autowired
    private FileStorageConfig storageConfig;

    @GetMapping("images/{id}")
    @PreAuthorize("isFullyAuthenticated()")
    public void getImage(@PathVariable("id") String id, HttpServletResponse response) throws IOException {
        System.out.println(id);

        FileSystemResource file = new FileSystemResource(Path.of(this.storageConfig.getUploadDir() + File.separator + "images" + File.separator + id + ".png"));
        System.out.println(file.getURL().toString());

        response.setContentType(MediaType.IMAGE_PNG_VALUE);
        StreamUtils.copy(file.getInputStream(), response.getOutputStream());
    }

}
