package eu.tsalliance.tsmedia.service;

import eu.tsalliance.tsmedia.exception.NotFoundException;
import eu.tsalliance.tsmedia.exception.ResourceExistsException;
import eu.tsalliance.tsmedia.models.file.FileTag;
import eu.tsalliance.tsmedia.repository.FileTagRepository;
import eu.tsalliance.tsmedia.validation.Validator;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.stereotype.Service;

import java.util.Optional;

@Service
public class FileTagService {

    @Autowired
    private FileTagRepository tagRepository;

    @Autowired
    private Validator validator;

    public Page<FileTag> list(Pageable pageable) {
        return this.tagRepository.findAll(pageable);
    }

    public Optional<FileTag> getOne(String id) {
        return this.tagRepository.findById(id);
    }

    public boolean existsByName(String name) {
        return this.tagRepository.findByName(name) != null;
    }

    public FileTag create(FileTag category) throws Exception {

        this.validator.validateTextAndThrow(category.getName(), "name", true).alpha().minLen(3).maxLen(32).test();

        if(this.existsByName(category.getName())) {
            throw new ResourceExistsException("name", category.getName());
        }

        return this.tagRepository.saveAndFlush(category);
    }

    public FileTag update(String id, FileTag updated) throws Exception {
        Optional<FileTag> oldCategory = this.getOne(id);

        // Check if category exists
        if(oldCategory.isEmpty()) {
            throw new NotFoundException();
        }

        if(this.validator.validateTextAndThrow(updated.getName(), "name", false).alpha().minLen(3).maxLen(32).test() && updated.getName() != null) {
            if(this.existsByName(updated.getName())) throw new ResourceExistsException("name", updated.getName());
            oldCategory.get().setName(updated.getName());
        }

        return this.tagRepository.saveAndFlush(oldCategory.get());
    }

    public void delete(String id) {
        this.tagRepository.deleteById(id);
    }

}
