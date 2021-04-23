package eu.tsalliance.tsmedia.service;

import eu.tsalliance.tsmedia.models.media.Resolution;
import eu.tsalliance.tsmedia.repository.ResolutionRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import javax.annotation.PostConstruct;
import java.util.Arrays;

@Service
public class ResolutionService {

    @Autowired
    private ResolutionRepository repository;

    @PostConstruct
    private void generateResolutions(){
        if(this.repository.countAllBy() <= 0) {
            this.repository.saveAll(Arrays.asList(
                    new Resolution("360p", "360p", 900, 96, false),
                    new Resolution("480p", "480p", 1600, 128, false),
                    new Resolution("720p", "HD 720p", 3200, 128, false),
                    new Resolution("720p60", "HD 720p 60fps", 4400, 128, true),
                    new Resolution("1080p", "FHD 1080p", 5300, 192, false),
                    new Resolution("1080p60", "FHD 1080p 60fps", 7400, 192, true)
            ));
        }
    }

}
