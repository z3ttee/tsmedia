package eu.tsalliance.tsmedia.converter;

import com.fasterxml.jackson.databind.JavaType;
import com.fasterxml.jackson.databind.type.TypeFactory;
import com.fasterxml.jackson.databind.util.Converter;
import eu.tsalliance.tsmedia.models.file.FileType;

public class IntToFiletypeConverter implements Converter<Integer, FileType> {

    @Override
    public FileType convert(Integer integer) {
        FileType type = FileType.get(integer);

        if(type == null) type = FileType.FILE_VIDEO;
        return type;
    }

    @Override
    public JavaType getInputType(TypeFactory typeFactory) {
        return null;
    }

    @Override
    public JavaType getOutputType(TypeFactory typeFactory) {
        return null;
    }
}
