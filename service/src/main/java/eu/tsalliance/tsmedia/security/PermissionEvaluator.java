package eu.tsalliance.tsmedia.security;

import org.springframework.security.core.Authentication;
import org.springframework.security.core.GrantedAuthority;

import java.io.Serializable;

public class PermissionEvaluator implements org.springframework.security.access.PermissionEvaluator {

    @Override
    public boolean hasPermission(Authentication authentication, Object targetDomainObject, Object permission) {
        if ((authentication == null) || !(permission instanceof String)){
            return false;
        }

        return hasPrivilege(authentication, permission.toString().toUpperCase());
    }

    @Override
    public boolean hasPermission(Authentication authentication, Serializable targetId, String targetType, Object permission) {
        if ((authentication == null) || !(permission instanceof String)) {
            return false;
        }

        return hasPrivilege(authentication, permission.toString().toUpperCase());
    }

    private boolean hasPrivilege(Authentication auth, String permission) {
        for (GrantedAuthority grantedAuth : auth.getAuthorities()) {
            if (grantedAuth.getAuthority().contains(permission) || grantedAuth.getAuthority().contains("*")) {
                return true;
            }
        }
        return false;
    }

}
