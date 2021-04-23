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

    @Autowired
    private ResolutionService resolutionService;

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

                // TODO: Get duration

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

    private void processVideoFile() {
        // Gather information about video
        // e.g. mime type, width, height, resolution

        /*new Thread(() -> {
            try {
                File originalFile = this.resolveDestinationFile(file);
                File destinationDirectory = new File(this.storageConfig.getVideosDirPath() + File.separator + file.getId());

                List<Resolution> resolutions = this.resolutionService.getAllAvailable();

                StringBuilder ffmpegCmdBuilder = new StringBuilder("ffmpeg -hide_banner -y -i " + originalFile.getAbsolutePath());

                for(Resolution resolution : resolutions) {
                    String hlsSegmentFilename = destinationDirectory.getAbsolutePath() + File.separator + resolution.getName() + "_%03d.ts";
                    String resolutionPlaylistFilename = destinationDirectory.getAbsolutePath() + File.separator + resolution.getName() + "_%03d" + FileStorageConfig.PLAYLIST_FILE_EXT;

                    ffmpegCmdBuilder.append(" -vf scale=w=" + resolution.getWidth() + ":h=" + resolution.getHeight() + ":force_original_aspect_ratio=decrease -c:a " + FileStorageConfig.AUDIO_CODEC + " -ar 48000 -b:a " + resolution.getAudioBitrate()/1000 + "k -c:v " + FileStorageConfig.VIDEO_CODEC + " -profile:v main -crf 20 -sc_threshold 0 -g 48 -keyint_min 48 -hls_time 4 -hls_playlist_type vod  -b:v " + resolution.getLowMotionBitrate()/1000 + "k -maxrate " + resolution.getHighMotionBitrate()/1000 + "k -bufsize " + resolution.getBufferSize()/1000 + "k -hls_segment_filename " + hlsSegmentFilename + " " + resolutionPlaylistFilename);
                }

                System.out.println(ffmpegCmdBuilder.toString());
            } catch (Exception e) {
                e.printStackTrace();
            }
        }).start();*/
    }

}
