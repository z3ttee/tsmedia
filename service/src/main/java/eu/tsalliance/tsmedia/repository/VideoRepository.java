package eu.tsalliance.tsmedia.repository;

import eu.tsalliance.tsmedia.models.media.Video;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface VideoRepository extends JpaRepository<Video, String> {

    /**
     * Get images sorted by date descending
     * @param pageable Pageable object
     * @return Page of Image
     */
    Page<Video> findAllByOrderByCreatedAtDesc(Pageable pageable);

}
