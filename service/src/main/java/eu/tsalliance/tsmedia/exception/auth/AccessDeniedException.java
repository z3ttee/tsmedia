package eu.tsalliance.tsmedia.exception.auth;

public class AccessDeniedException extends org.springframework.security.core.AuthenticationException {

    public AccessDeniedException() {
        super("Access Denied");
    }
}
