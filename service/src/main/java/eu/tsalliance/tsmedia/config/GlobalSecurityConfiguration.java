package eu.tsalliance.tsmedia.config;

import eu.tsalliance.tsmedia.security.PermissionEvaluator;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.security.access.expression.method.DefaultMethodSecurityExpressionHandler;
import org.springframework.security.access.expression.method.MethodSecurityExpressionHandler;
import org.springframework.security.config.annotation.method.configuration.EnableGlobalMethodSecurity;
import org.springframework.security.config.annotation.method.configuration.GlobalMethodSecurityConfiguration;

@Configuration
@EnableGlobalMethodSecurity(
        prePostEnabled = true,

        // Enables @Secure annotation on requestmappings
        securedEnabled = true,

        // Allows the use of @RoleAllowed annotation
        jsr250Enabled = true
)
public class GlobalSecurityConfiguration extends GlobalMethodSecurityConfiguration {

    @Bean
    @Override
    protected MethodSecurityExpressionHandler createExpressionHandler() {
        DefaultMethodSecurityExpressionHandler expressionHandler = new DefaultMethodSecurityExpressionHandler();
        expressionHandler.setPermissionEvaluator(new PermissionEvaluator());
        return expressionHandler;
    }

}
