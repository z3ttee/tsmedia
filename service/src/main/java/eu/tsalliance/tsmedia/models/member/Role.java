package eu.tsalliance.tsmedia.models.member;

import org.springframework.security.core.GrantedAuthority;

import java.util.HashSet;
import java.util.Set;

public class Role {

    private String id;
    private Set<GrantedAuthority> permissions = new HashSet<>();
    private String name;

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public Set<GrantedAuthority> getPermissions() {
        return permissions;
    }

    public void setPermissions(Set<GrantedAuthority> permissions) {
        this.permissions = permissions;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }
}
