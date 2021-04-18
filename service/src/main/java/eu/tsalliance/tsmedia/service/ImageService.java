package eu.tsalliance.tsmedia.service;

import eu.tsalliance.tsmedia.models.media.Image;
import eu.tsalliance.tsmedia.repository.ImageRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.stereotype.Service;

@Service
public class ImageService {

    @Autowired
    private ImageRepository imageRepository;

    public Image create(Image image) {
        return this.imageRepository.saveAndFlush(image);
    }


    public Page<Image> getLatest(Pageable pageable) {
        return this.imageRepository.findAllByOrderByCreatedAtDesc(pageable);
    }

}
