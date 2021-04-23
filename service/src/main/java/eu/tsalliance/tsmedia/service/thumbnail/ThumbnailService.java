package eu.tsalliance.tsmedia.service.thumbnail;

import eu.tsalliance.tsmedia.models.media.Thumbnail;
import eu.tsalliance.tsmedia.repository.thumbnail.ThumbnailRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.io.File;

@Service
public class ThumbnailService {

    @Autowired
    private ThumbnailRepository thumbnailRepository;

    public Thumbnail create(Thumbnail thumbnail) {
        return this.thumbnailRepository.saveAndFlush(thumbnail);
    }

    public Thumbnail createFromVideoFile(File file) {
        return this.thumbnailRepository.saveAndFlush(new Thumbnail());
    }

    public Thumbnail update(Thumbnail thumbnail) {
        return this.thumbnailRepository.saveAndFlush(thumbnail);
    }

}
