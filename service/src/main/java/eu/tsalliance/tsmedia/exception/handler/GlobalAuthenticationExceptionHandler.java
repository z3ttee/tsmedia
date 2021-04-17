package eu.tsalliance.tsmedia.exception.handler;

import com.fasterxml.jackson.databind.ObjectMapper;
import eu.tsalliance.tsmedia.exception.auth.BadSessionException;
import eu.tsalliance.tsmedia.exception.response.ErrorResponseEntity;
import org.springframework.security.access.AccessDeniedException;
import org.springframework.security.authentication.InsufficientAuthenticationException;
import org.springframework.security.core.AuthenticationException;
import org.springframework.security.web.AuthenticationEntryPoint;
import org.springframework.security.web.access.AccessDeniedHandler;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.io.IOException;

public class GlobalAuthenticationExceptionHandler implements AuthenticationEntryPoint, AccessDeniedHandler {

    @Override
    public void commence(HttpServletRequest request, HttpServletResponse response, AuthenticationException exception) throws IOException, ServletException {
        // TODO: A secured route is accessed without supplying any credentials -> Should be denied and an error should be send

        sendError(response, exception);
    }

    public static void sendError(HttpServletResponse response, Exception exception) throws IOException, ServletException {
        ObjectMapper mapper = new ObjectMapper();
        ErrorResponseEntity responseEntity;

        if(exception instanceof BadSessionException || exception instanceof InsufficientAuthenticationException) {
            responseEntity = new ErrorResponseEntity(GlobalExceptionHandler.BAD_SESSION_ERROR, (exception instanceof BadSessionException ? exception : new BadSessionException()));
        } else {
            responseEntity = new ErrorResponseEntity(GlobalExceptionHandler.ACCESS_DENIED_ERROR, exception);
        }

        response.setHeader("Content-Type", "application/json; charset=utf-8");
        response.getOutputStream().println(mapper.writeValueAsString(responseEntity));
    }

    @Override
    public void handle(HttpServletRequest httpServletRequest, HttpServletResponse httpServletResponse, AccessDeniedException e) throws IOException, ServletException {
        GlobalAuthenticationExceptionHandler.sendError(httpServletResponse, new eu.tsalliance.tsmedia.exception.auth.AccessDeniedException());
    }
}
