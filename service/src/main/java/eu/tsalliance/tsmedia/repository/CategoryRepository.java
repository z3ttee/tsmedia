package eu.tsalliance.tsmedia.repository;

import eu.tsalliance.tsmedia.models.file.FileCategory;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface CategoryRepository extends JpaRepository<FileCategory, String> {

    FileCategory findByName(String name);

}
