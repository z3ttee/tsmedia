package eu.tsalliance.tsmedia.exception;

import eu.tsalliance.tsmedia.validation.rules.ValidationRule;

import java.util.HashMap;
import java.util.Map;

public class ValidationException extends Exception {

    private final Map<String, String> rules = new HashMap<>();
    private ValidationRule rule;

    public ValidationException(ValidationRule rule) {
        super(String.format("Value for field '%s' does not match the requirements", rule.getFieldname()));
        this.rule = rule;
    }

    public ValidationRule getRule() {
        return rule;
    }
}
