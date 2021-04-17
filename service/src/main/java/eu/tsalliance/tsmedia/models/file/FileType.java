package eu.tsalliance.tsmedia.models.file;

public enum FileType {

    FILE_VIDEO(1, "FILE_VIDEO"),
    FILE_IMAGE(2, "FILE_IMAGE");

    private final int id;
    private final String identifier;

    FileType(int id, String identifier) {
        this.id = id;
        this.identifier = identifier;
    }

    public int getId() {
        return id;
    }

    public String getIdentifier() {
        return identifier;
    }

    public static FileType get(int id) {
        for(FileType type : values()) {
            if(type.id == id) return type;
        }

        return null;
    }
}
