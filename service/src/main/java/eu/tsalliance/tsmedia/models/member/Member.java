package eu.tsalliance.tsmedia.models.member;

import org.springframework.security.core.GrantedAuthority;
import org.springframework.security.core.userdetails.UserDetails;

import java.util.Collection;
import java.util.HashSet;
import java.util.Map;

public class Member implements UserDetails {

    private String id;
    private String name;
    private Role role;

    public String getId() {
        return id;
    }

    public Role getRole() {
        return role;
    }

    public void setRole(Role role) {
        this.role = role;
    }

    @Override
    public Collection<? extends GrantedAuthority> getAuthorities() {
        if(this.role != null) {
            return this.role.getPermissions();
        }

        return new HashSet<>();
    }

    @Override
    public String getPassword() {
        return null;
    }

    @Override
    public String getUsername() {
        return this.name;
    }

    @Override
    public boolean isAccountNonExpired() {
        return true;
    }

    @Override
    public boolean isAccountNonLocked() {
        return true;
    }

    @Override
    public boolean isCredentialsNonExpired() {
        return true;
    }

    @Override
    public boolean isEnabled() {
        return true;
    }

    public void setName(String name) {
        this.name = name;
    }

    public void setId(String id) {
        this.id = id;
    }

    public static Member anonymous() {
        return new Member();
    }

    public static class MemberResponseEntity {
        private int statusCode;
        private Map<String, Object> data;

        public int getStatusCode() {
            return statusCode;
        }

        public Map<String, Object> getData() {
            return data;
        }
    }
}
