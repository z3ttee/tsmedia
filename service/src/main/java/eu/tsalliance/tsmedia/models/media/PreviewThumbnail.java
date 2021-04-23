package eu.tsalliance.tsmedia.models.media;

import javax.persistence.Entity;
import javax.persistence.Id;
import javax.persistence.Table;
import java.util.UUID;

@Entity
@Table(name = "ts_videos_previews")
public class PreviewThumbnail {

    @Id
    private String id = UUID.randomUUID().toString();
    private String uri;

}
