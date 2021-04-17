package eu.tsalliance.tsmedia.service;

import eu.tsalliance.tsmedia.exception.NotFoundException;
import eu.tsalliance.tsmedia.exception.ResourceExistsException;
import eu.tsalliance.tsmedia.models.file.FileCategory;
import eu.tsalliance.tsmedia.repository.CategoryRepository;
import eu.tsalliance.tsmedia.validation.Validator;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.stereotype.Service;

@Service
public class CategoryService {

    @Autowired
    private CategoryRepository categoryRepository;

    @Autowired
    private Validator validator;

    public Page<FileCategory> list(Pageable pageable) {
        return this.categoryRepository.findAll(pageable);
    }

    public FileCategory getOne(String id) {
        return this.categoryRepository.getOne(id);
    }

    public boolean existsByName(String name) {
        return this.categoryRepository.findByName(name) != null;
    }

    public FileCategory create(FileCategory category) throws Exception {

        this.validator.validateTextAndThrow(category.getName(), "name", true).alpha().minLen(3).maxLen(32).test();

        if(this.existsByName(category.getName())) {
            throw new ResourceExistsException("name", category.getName());
        }

        return this.categoryRepository.saveAndFlush(category);
    }

    public FileCategory update(String id, FileCategory updated) throws Exception {
        FileCategory oldCategory = this.getOne(id);

        // Check if category exists
        if(oldCategory == null) {
            throw new NotFoundException();
        }

        if(this.validator.validateTextAndThrow(updated.getName(), "name", false).alphaNum().minLen(3).maxLen(32).test() && updated.getName() != null) {
            if(this.existsByName(updated.getName())) throw new ResourceExistsException("name", updated.getName());
            oldCategory.setName(updated.getName());
        }

        return this.categoryRepository.saveAndFlush(oldCategory);
    }

    public void delete(String id) {
        this.categoryRepository.deleteById(id);
    }

}
