package eu.tsalliance.tsmedia.models.media;

import javax.persistence.*;
import java.util.ArrayList;
import java.util.List;

@Entity
@Table(name = "ts_videos")
public class Video extends MediaFile {

    @Column(nullable = false)
    private long duration;

    @OneToOne
    private Thumbnail thumbnail;

    @OneToOne
    private PreviewThumbnail previewThumbnail;

    @ManyToMany
    private List<Resolution> resolutions = new ArrayList<>();

    public Thumbnail getThumbnail() {
        return thumbnail;
    }

    public void setThumbnail(Thumbnail thumbnail) {
        this.thumbnail = thumbnail;
    }

    public PreviewThumbnail getPreviewThumbnail() {
        return previewThumbnail;
    }

    public void setPreviewThumbnail(PreviewThumbnail previewThumbnail) {
        this.previewThumbnail = previewThumbnail;
    }

    public long getDuration() {
        return duration;
    }

    public void setDuration(long duration) {
        this.duration = duration;
    }
}
