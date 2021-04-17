package eu.tsalliance.tsmedia.models.file;

import eu.tsalliance.tsmedia.models.media.MediaFile;

import javax.persistence.*;
import java.util.HashSet;
import java.util.Set;
import java.util.UUID;

@Entity
@Table(name = "ts_tags")
@Embeddable
public class FileTag {

    @Id
    private String id = UUID.randomUUID().toString();

    @Column(nullable = false, unique = true, length = 32)
    private String name;

    @ManyToMany(mappedBy = "tags")
    private Set<MediaFile> files = new HashSet<>();

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

    public Set<MediaFile> getFiles() {
        return files;
    }

    public void setFiles(Set<MediaFile> files) {
        this.files = files;
    }
}
