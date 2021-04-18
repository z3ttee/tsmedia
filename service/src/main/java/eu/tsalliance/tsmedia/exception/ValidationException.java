package eu.tsalliance.tsmedia.exception;

import eu.tsalliance.tsmedia.validation.rules.ValidationRule;
import org.springframework.http.HttpStatus;

public class ValidationException extends ApiException {

    public ValidationException(ValidationRule rule) {
        super("Value for field '" + rule.getFieldname() + "' does not match the requirements", HttpStatus.BAD_REQUEST);

        this.putDetail("fieldname", rule.getFieldname());
        this.putDetail("failedTests", rule.getFailedTests());
        this.putDetail("requirements", rule.getRequirements());
    }

    @Override
    protected String getErrorCode() {
        return "VALIDATION_ERROR";
    }
}
