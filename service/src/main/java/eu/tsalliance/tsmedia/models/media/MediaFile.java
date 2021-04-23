package eu.tsalliance.tsmedia.models.media;

import eu.tsalliance.tsmedia.models.file.FileCategory;
import eu.tsalliance.tsmedia.models.file.FileTag;
import eu.tsalliance.tsmedia.models.file.FileType;

import javax.persistence.*;
import java.sql.Date;
import java.util.ArrayList;
import java.util.List;
import java.util.UUID;

@Entity
@Table(name = "ts_file")
@Inheritance(strategy = InheritanceType.JOINED)
public abstract class MediaFile {

    @Id
    private String id = UUID.randomUUID().toString();

    @Column(nullable = false)
    private String mimeType;

    @Column(nullable = false)
    private String title;

    @Column(nullable = false)
    private String fileHash;

    @Column(nullable = false)
    private String uri;                 // URI format: alliance:media:<filetype.id>:<id>

    @Column(nullable = false)
    private FileType fileType;

    @Column(nullable = false)
    private long fileSize;

    @Column(nullable = false)
    private Date createdAt = new Date(System.currentTimeMillis());

    @ManyToMany(cascade = CascadeType.ALL) // Delete relation when either file or tag is deleted
    private List<FileTag> tags = new ArrayList<>();

    @ManyToOne(cascade = CascadeType.REFRESH)
    private FileCategory category;

    @Column(nullable = false)
    private String memberId;

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public Date getCreatedAt() {
        return createdAt;
    }

    public void setCreatedAt(Date createdAt) {
        this.createdAt = createdAt;
    }

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getMimeType() {
        return mimeType;
    }

    public void setMimeType(String mimeType) {
        this.mimeType = mimeType;
    }

    public String getUri() {
        return uri;
    }

    public void setUri(String uri) {
        this.uri = uri;
    }

    public FileType getFileType() {
        return fileType;
    }

    public void setFileType(FileType fileType) {
        this.fileType = fileType;
    }

    public long getFileSize() {
        return fileSize;
    }

    public void setFileSize(long fileSize) {
        this.fileSize = fileSize;
    }

    public List<FileTag> getTags() {
        return tags;
    }

    public void setTags(List<FileTag> tags) {
        this.tags = tags;
    }

    public FileCategory getCategory() {
        return category;
    }

    public void setCategory(FileCategory category) {
        this.category = category;
    }

    public String getMemberId() {
        return memberId;
    }

    public void setMemberId(String memberId) {
        this.memberId = memberId;
    }
}
