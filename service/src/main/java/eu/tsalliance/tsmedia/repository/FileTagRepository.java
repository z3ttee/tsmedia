package eu.tsalliance.tsmedia.repository;

import eu.tsalliance.tsmedia.models.file.FileTag;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface FileTagRepository extends JpaRepository<FileTag, String> {

    FileTag findByName(String name);

}
