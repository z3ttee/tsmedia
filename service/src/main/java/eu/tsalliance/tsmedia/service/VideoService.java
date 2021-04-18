package eu.tsalliance.tsmedia.service;

import eu.tsalliance.tsmedia.models.media.Video;
import eu.tsalliance.tsmedia.repository.VideoRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

@Service
public class VideoService {

    @Autowired
    private VideoRepository repository;

    public Video create(Video video) {
        return this.repository.saveAndFlush(video);
    }

}
