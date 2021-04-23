package eu.tsalliance.tsmedia.repository;

import eu.tsalliance.tsmedia.models.media.Resolution;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface ResolutionRepository extends JpaRepository<Resolution, String> {

    int countAllBy();

}
