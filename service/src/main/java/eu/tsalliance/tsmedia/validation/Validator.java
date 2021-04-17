package eu.tsalliance.tsmedia.validation;

import eu.tsalliance.tsmedia.validation.rules.NumberRule;
import eu.tsalliance.tsmedia.validation.rules.TextRule;
import org.springframework.stereotype.Component;
import org.springframework.web.context.annotation.RequestScope;

/**
 * Validator is created as a bean by spring on every request
 */
@Component
@RequestScope
public class Validator {

    public TextRule validateTextAndThrow(String subject, String fieldname, boolean required) {
        return new TextRule(fieldname, subject, required, true);
    }

    public NumberRule validateNumberAndThrow(String subject, String fieldname, boolean required) {
        return new NumberRule(fieldname, subject, required, true);
    }

    public TextRule validateText(String subject, String fieldname, boolean required) {
        return new TextRule(fieldname, subject, required, false);
    }

    public boolean isNotEmptyString(String subject) {
        boolean passed = subject != null && !subject.isEmpty() && !subject.isBlank();
        System.out.println(passed);
        return passed;
    }

}
