package eu.tsalliance.tsmedia.validation.rules;

import eu.tsalliance.tsmedia.exception.ValidationException;

import java.util.HashMap;
import java.util.Map;

public class NumberRule extends ValidationRule<String> {

    private int max = -1;
    private int min = -1;

    public NumberRule(String fieldname, String subject, boolean required, boolean throwException) {
        super(fieldname, subject, required, throwException);
    }

    /**
     * Set maximum of subject
     *
     * @param len Maximum
     */
    public NumberRule max(int len) {
        this.max = len;
        return this;
    }

    /**
     * Set minimum of subject
     *
     * @param len Minimum
     */
    public NumberRule min(int len) {
        this.min = len;
        return this;
    }

    @Override
    public boolean test() throws ValidationException {
        boolean passed = false;

        if(this.isRequired() && !this.needsValidation()) {
            if(this.shouldThrowException()) throw new ValidationException(this);
            else return false;
        }

        int subjectInt;

        try {
            subjectInt = Integer.parseInt(this.getSubject());

            if (this.max != -1) {
                passed = subjectInt <= this.max;
            }

            if (this.min != -1) {
                passed = subjectInt >= this.min;
            }

        } catch (NumberFormatException exception) {
            passed = false;
        }

        if(this.shouldThrowException() && !passed) {
            throw new ValidationException(this);
        }

        return passed;
    }

    @Override
    public Map<String, Object> getRequirements() {
        Map<String, Object> requirements = new HashMap<>();

        if(this.max != -1) requirements.put("max", this.max);
        if(this.min != -1) requirements.put("min", this.min);

        requirements.put("required", this.isRequired());

        return requirements;
    }
}
