package eu.tsalliance.tsmedia.models.file;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.Id;
import javax.persistence.Table;
import java.util.UUID;

@Entity
@Table(name = "ts_categories")
@JsonIgnoreProperties({"hibernateLazyInitializer", "handler"})
public class FileCategory {

    @Id
    private String id = UUID.randomUUID().toString();

    @Column(length = 32, nullable = false, unique = true)
    private String name;

    @Column(nullable = false)
    private FileType allowedFiletype = FileType.FILE_VIDEO;

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

    public FileType getAllowedFiletype() {
        return allowedFiletype;
    }

    public void setAllowedFiletype(FileType allowedFiletype) {
        this.allowedFiletype = allowedFiletype;
    }
}
