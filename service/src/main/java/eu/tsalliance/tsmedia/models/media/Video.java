package eu.tsalliance.tsmedia.models.media;

import javax.persistence.*;

@Entity
@Table(name = "ts_videos")
public class Video extends MediaFile {

    @Column(nullable = false)
    private long duration;

    @OneToOne
    private MediaFile metadata;

    public long getDuration() {
        return duration;
    }

    public void setDuration(long duration) {
        this.duration = duration;
    }

    public MediaFile getMetadata() {
        return metadata;
    }

    public void setMetadata(MediaFile metadata) {
        this.metadata = metadata;
    }
}
