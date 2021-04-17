package eu.tsalliance.tsmedia.validation.rules;

import eu.tsalliance.tsmedia.exception.ValidationException;

import java.util.HashMap;
import java.util.Map;

public abstract class ValidationRule<T> {

    private final T subject;
    private final String fieldname;
    private final boolean required;
    private boolean throwException = false;

    private final Map<String, Object> failedTests = new HashMap<>();

    public ValidationRule(String fieldname, T subject, boolean required) {
        this.fieldname = fieldname;
        this.subject = subject;
        this.required = required;
    }

    public ValidationRule(String fieldname, T subject, boolean required, boolean throwException) {
        this.fieldname = fieldname;
        this.subject = subject;
        this.required = required;
        this.throwException = throwException;
    }

    /**
     * Execute test
     * @return True or False
     */
    public abstract boolean test() throws ValidationException;
    public abstract Map<String, Object> getRequirements();

    protected boolean isRequired() {
        return required;
    }

    protected T getSubject() {
        return subject;
    }

    protected boolean shouldThrowException() {
        return throwException;
    }

    public String getFieldname() {
        return fieldname;
    }

    public Map<String, Object> getFailedTests() {
        return failedTests;
    }

    public void putFailedTest(String testName, Object foundValue, Object expectedValue) {
        Map<String, Object> value = new HashMap<>();
        value.put("expected", expectedValue);
        value.put("value", foundValue);

        this.failedTests.put(testName, value);
    }

    public boolean needsValidation() {
        boolean needsValidation;

        // Check if subject is null, return false
        if(this.getSubject() == null) {
            return false;
        } else {
            if(this.getSubject() instanceof String) {
                String subj = (String) this.getSubject();
                needsValidation = !(subj.isBlank() || !subj.isEmpty());
            } else {
                needsValidation = true;
            }
            
        }

        // Check if rule needs the field to be not optionally required
        return this.isRequired() && !needsValidation;
    }
}
