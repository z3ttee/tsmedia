package eu.tsalliance.tsmedia.repository.thumbnail;

import eu.tsalliance.tsmedia.models.media.Thumbnail;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface ThumbnailRepository extends JpaRepository<Thumbnail, String> {

}
