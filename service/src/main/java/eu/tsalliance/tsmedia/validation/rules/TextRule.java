package eu.tsalliance.tsmedia.validation.rules;

import eu.tsalliance.tsmedia.exception.ValidationException;

import java.util.HashMap;
import java.util.Map;

public class TextRule extends ValidationRule<String> {

    private int maxLen = -1;
    private int minLen = -1;
    private boolean alpha = false;
    private boolean alphaNum = false;

    public TextRule(String fieldname, String subject, boolean required, boolean throwException) {
        super(fieldname, subject, required, throwException);
    }

    /**
     * Set maximum length of subject
     *
     * @param len Maximum length
     */
    public TextRule maxLen(int len) {
        this.maxLen = len;
        return this;
    }

    /**
     * Set minimum length of subject
     *
     * @param len Minimum length
     */
    public TextRule minLen(int len) {
        this.minLen = len;
        return this;
    }

    /**
     * Check if subject consists only of letters
     */
    public TextRule alpha() {
        this.alpha = true;
        this.alphaNum = false;
        return this;
    }

    /**
     * Check if subject consists only of letters and digits
     */
    public TextRule alphaNum() {
        this.alpha = false;
        this.alphaNum = true;
        return this;
    }

    @Override
    public boolean test() throws ValidationException {
        if(this.needsValidation()) {
            if (this.maxLen != -1 && this.getSubject().length() > this.maxLen) {
                putFailedTest("maxLen", this.getSubject().length(), this.maxLen);
            }

            if (this.minLen != -1 && this.getSubject().length() < this.minLen) {
                putFailedTest("minLen", this.getSubject().length(), this.minLen);
            }

            if (this.alpha && !this.getSubject().matches("^[a-zA-Z]*$")) {
                putFailedTest("alpha", false, true);
            }
            if (this.alphaNum && !this.getSubject().matches("^[a-zA-Z0-9]*$")) {
                putFailedTest("alphaNum", false, true);
            }


        } else {
            if(this.isRequired()) {
                putFailedTest("required", false, this.isRequired());

                if (this.shouldThrowException()) throw new ValidationException(this);
                else return false;
            }
        }

        boolean passed = getFailedTests().size() <= 0;

        if (this.shouldThrowException() && !passed) {
            throw new ValidationException(this);
        }

        return passed;
    }

    @Override
    public Map<String, Object> getRequirements() {
        Map<String, Object> requirements = new HashMap<>();

        if(this.maxLen != -1) requirements.put("maxlen", this.maxLen);
        if(this.minLen != -1) requirements.put("minlen", this.minLen);

        requirements.put("alpha", this.alpha);
        requirements.put("alphaNum", this.alphaNum);
        requirements.put("required", this.isRequired());

        return requirements;
    }
}
