package eu.tsalliance.tsmedia.repository.file;

import eu.tsalliance.tsmedia.models.media.Image;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface ImageRepository extends JpaRepository<Image, String> {

    /**
     * Get images sorted by date descending
     * @param pageable Pageable object
     * @return Page of Image
     */
    Page<Image> findAllByOrderByCreatedAtDesc(Pageable pageable);

}
