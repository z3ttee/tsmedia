package eu.tsalliance.tsmedia.exception.auth;

public class BadSessionException extends org.springframework.security.core.AuthenticationException {

    public BadSessionException() {
        super("Authentication is required by this route");
    }
}
