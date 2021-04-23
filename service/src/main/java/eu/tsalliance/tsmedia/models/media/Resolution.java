package eu.tsalliance.tsmedia.models.media;

import javax.persistence.*;
import java.util.List;
import java.util.UUID;

@Entity
@Table(name = "ts_resolutions")
public class Resolution {

    @Id
    private String id = UUID.randomUUID().toString();

    @Column(unique = true)
    private String name;

    @Column(unique = true, length = 120)
    private String title;

    private int videoBitrateK;
    private int audioBitrateK;
    private int bufferSizeK;
    private boolean highFramerate;

    @ManyToMany
    private List<Video> videos;

    public Resolution() { }

    public Resolution(String name, String title, int videoBitrateK, int audioBitrateK, boolean highFramerate) {
        this.name = name;
        this.title = title;
        this.videoBitrateK = videoBitrateK;
        this.audioBitrateK = audioBitrateK;
        this.bufferSizeK = videoBitrateK + audioBitrateK;
        this.highFramerate = highFramerate;
    }

    public boolean isHighFramerate() {
        return highFramerate;
    }

    public void setHighFramerate(boolean highFramerate) {
        this.highFramerate = highFramerate;
    }

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public int getVideoBitrateK() {
        return videoBitrateK;
    }

    public void setVideoBitrateK(int videoBitrateK) {
        this.videoBitrateK = videoBitrateK;
    }

    public int getAudioBitrateK() {
        return audioBitrateK;
    }

    public void setAudioBitrateK(int audioBitrateK) {
        this.audioBitrateK = audioBitrateK;
    }

    public int getBufferSizeK() {
        return bufferSizeK;
    }

    public void setBufferSizeK(int bufferSizeK) {
        this.bufferSizeK = bufferSizeK;
    }
}
