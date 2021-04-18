package eu.tsalliance.tsmedia.models.media;

import javax.persistence.*;

@Entity
@Table(name = "ts_videos")
public class Video extends MediaFile {

    @Column(nullable = false)
    private long duration;

    public long getDuration() {
        return duration;
    }

    public void setDuration(long duration) {
        this.duration = duration;
    }
}
