package eu.tsalliance.tsmedia.service;

import eu.tsalliance.tsmedia.config.FileStorageConfig;
import eu.tsalliance.tsmedia.exception.InternalErrorException;
import eu.tsalliance.tsmedia.exception.StorageException;
import eu.tsalliance.tsmedia.exception.UnsupportedMimeTypeException;
import eu.tsalliance.tsmedia.models.file.FileType;
import eu.tsalliance.tsmedia.models.media.*;
import eu.tsalliance.tsmedia.models.member.Member;
import eu.tsalliance.tsmedia.service.thumbnail.PreviewThumbnailService;
import eu.tsalliance.tsmedia.service.thumbnail.ThumbnailService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.core.Authentication;
import org.springframework.stereotype.Service;
import org.springframework.util.StringUtils;
import org.springframework.web.multipart.MultipartFile;

import java.io.File;
import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.StandardCopyOption;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

@Service
public class FileStorageService {

    @Autowired
    private VideoService videoService;

    @Autowired
    private ImageService imageService;

    @Autowired
    private FileStorageConfig storageConfig;

    @Autowired
    private ThumbnailService thumbnailService;

    @Autowired
    private PreviewThumbnailService previewThumbnailService;

    /**
     * Store a multipart file on the file system
     * @param file Multipart file to save
     * @return Media File
     * @throws Exception
     */
    public MediaFile storeFile(MultipartFile file, Authentication authentication) throws Exception {
        try {

            if(!(authentication.getPrincipal() instanceof Member)) {
                throw new InternalErrorException();
            }

            Member member = (Member) authentication.getPrincipal();

            // Normalize file name
            String fileName = StringUtils.cleanPath(file.getOriginalFilename());

            // Check if the file's name contains invalid characters
            if(fileName.contains("..")) {
                throw new Exception("Filename contains invalid path sequence " + fileName);
            }

            if(!this.storageConfig.isSupportedMimetype(file.getContentType())) {
                throw new UnsupportedMimeTypeException(file.getContentType());
            }

            String mimeType = file.getContentType();

            if(mimeType.equalsIgnoreCase("video/mp4")) {
                Video video = new Video();
                video.setFileType(FileType.FILE_VIDEO);
                video.setMimeType(mimeType);
                video.setMemberId(member.getId());
                video.setFileSize(file.getSize());
                video.setTitle(this.resolveFilenameWithoutExt(fileName));
                video.setUri("alliance:media:" + FileType.FILE_VIDEO.getId() + ":" + video.getId());

                Thumbnail thumbnail = new Thumbnail();
                video.setThumbnail(this.thumbnailService.create(thumbnail));

                PreviewThumbnail previewThumbnail = new PreviewThumbnail();
                video.setPreviewThumbnail(this.previewThumbnailService.create(previewThumbnail));

                this.transfer(file, video);
                return this.videoService.create(video);
            } else {
                Image image = new Image();
                image.setFileType(FileType.FILE_IMAGE);
                image.setMimeType(mimeType);
                image.setMemberId(member.getId());
                image.setFileSize(file.getSize());
                image.setTitle(this.resolveFilenameWithoutExt(fileName));
                image.setUri("alliance:media:" + FileType.FILE_IMAGE.getId() + ":" + image.getId());

                this.transfer(file, image);
                return this.imageService.create(image);
            }
        } catch (Exception ex) {
            ex.printStackTrace();
            throw new StorageException(file);
        }
    }

    /**
     * Transfer a multipart file to a matching directory
     * @param file Multipart file
     * @param metadata File metadata
     * @throws IOException
     */
    private void transfer(MultipartFile file, MediaFile metadata) throws IOException, UnsupportedMimeTypeException {
        File destinationDirectory;

        if(metadata.getFileType() == FileType.FILE_IMAGE) {
            destinationDirectory = new File(this.storageConfig.getUploadDir() + File.separator + "images");
        } else {
            destinationDirectory = new File(this.storageConfig.getUploadDir() + File.separator + "videos");
        }

        File destinationFile = new File(destinationDirectory.getAbsolutePath(), metadata.getId() + this.resolveFileExtFromMimetype(metadata.getMimeType()));

        Files.createDirectories(destinationDirectory.toPath());
        Files.copy(file.getInputStream(), destinationFile.toPath(), StandardCopyOption.REPLACE_EXISTING);
    }

    /**
     * Resolve the file extensions (including '.' ) fitting the mime type
     * @param mimetype Mimetype
     * @return String
     */
    private String resolveFileExtFromMimetype(String mimetype) throws UnsupportedMimeTypeException {
        String ext = this.storageConfig.getMimeTypeExtensions().get(mimetype);

        if(ext == null) {
            throw new UnsupportedMimeTypeException(mimetype);
        }

        return ext;
    }

    /**
     * Get the normalized filename without the file extension
     * @param filename Filename
     * @return Filename
     */
    private String resolveFilenameWithoutExt(String filename) {
        String normalizedFilename = StringUtils.cleanPath(filename);

        List<String> fileParts = new ArrayList<>(Arrays.asList(normalizedFilename.split("\\.")));

        if(fileParts.size() > 0) {
            fileParts.remove(fileParts.size() - 1);
            return String.join(".", fileParts);
        } else {
            return filename;
        }
    }

}
