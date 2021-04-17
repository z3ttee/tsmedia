package eu.tsalliance.tsmedia.controller;

import eu.tsalliance.tsmedia.models.file.FileTag;
import eu.tsalliance.tsmedia.service.FileTagService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.web.PageableDefault;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.web.bind.annotation.*;

import java.util.Optional;

@RestController
@RequestMapping("/api/v1/tags")
public class FileTagController {

    @Autowired
    private FileTagService tagService;

    @GetMapping("")
    public Page<FileTag> list(@PageableDefault(size = 15) Pageable pageable) {
        return this.tagService.list(pageable);
    }

    @GetMapping("{id}")
    public Optional<FileTag> get(@PathVariable("id") String id) {
        return this.tagService.getOne(id);
    }

    @PostMapping("")
    @PreAuthorize("hasPermission(#authentication, 'permission.tsmedia.tag.write')")
    public FileTag create(@RequestBody FileTag category) throws Exception {
        return this.tagService.create(category);
    }

    @PutMapping("{id}")
    @PreAuthorize("hasPermission(#authentication, 'permission.tsmedia.tag.write')")
    public FileTag update(@RequestBody FileTag category, @PathVariable("id") String id) throws Exception {
        return this.tagService.update(id, category);
    }

    @DeleteMapping("{id}")
    @PreAuthorize("hasPermission(#authentication, 'permission.tsmedia.tag.write')")
    public void delete(@PathVariable("id") String id) {
        this.tagService.delete(id);
    }

}
