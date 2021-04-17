package eu.tsalliance.tsmedia.controller;

import eu.tsalliance.tsmedia.exception.NotFoundException;
import eu.tsalliance.tsmedia.exception.ValidationException;
import eu.tsalliance.tsmedia.models.file.FileCategory;
import eu.tsalliance.tsmedia.service.CategoryService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.web.PageableDefault;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.security.core.Authentication;
import org.springframework.web.bind.annotation.*;

@RestController
@RequestMapping("/api/v1/categories")
public class CategoriesController {

    @Autowired
    private CategoryService categoryService;

    @GetMapping("")
    public Page<FileCategory> list(@PageableDefault(size = 15) Pageable pageable) {
        return this.categoryService.list(pageable);
    }

    @GetMapping("{id}")
    public FileCategory get(@PathVariable("id") String id) {
        return this.categoryService.getOne(id);
    }

    @PostMapping("")
    @PreAuthorize("hasPermission(#authentication, 'permission.tsmedia.category.write')")
    public FileCategory create(@RequestBody FileCategory category) throws Exception {
        return this.categoryService.create(category);
    }

    @PutMapping("{id}")
    @PreAuthorize("hasPermission(#authentication, 'permission.tsmedia.category.write')")
    public FileCategory update(@RequestBody FileCategory category, @PathVariable("id") String id) throws Exception {
        return this.categoryService.update(id, category);
    }

    @DeleteMapping("{id}")
    @PreAuthorize("hasPermission(#authentication, 'permission.tsmedia.category.write')")
    public void delete(@PathVariable("id") String id) {
        this.categoryService.delete(id);
    }

}
