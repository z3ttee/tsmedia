package eu.tsalliance.tsmedia.service.thumbnail;

import eu.tsalliance.tsmedia.models.media.PreviewThumbnail;
import eu.tsalliance.tsmedia.repository.thumbnail.PreviewThumbnailRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.io.File;

@Service
public class PreviewThumbnailService {

    @Autowired
    private PreviewThumbnailRepository thumbnailRepository;

    public PreviewThumbnail create(PreviewThumbnail thumbnail) {
        return this.thumbnailRepository.saveAndFlush(thumbnail);
    }

    public PreviewThumbnail createFromVideoFile(File file) {
        return this.thumbnailRepository.saveAndFlush(new PreviewThumbnail());
    }

    public PreviewThumbnail update(PreviewThumbnail thumbnail) {
        return this.thumbnailRepository.saveAndFlush(thumbnail);
    }

}
