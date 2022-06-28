
import React from 'react';

function FormField({fieldId, label, defaultValue, minLength, invalidFeedbackText, required=false}) {
    return (
        <Form.Group className="mb-3" controlId={'formGroup' + fieldId}>
            <FloatingLabel
                controlId={'floating' + fieldId}
                label={label}
                className="mb-3"
            >
                <Form.Control required={required} minLength={minLength} defaultValue={defaultValue} />
                <Form.Control.Feedback type="invalid">
                    {invalidFeedbackText}
                </Form.Control.Feedback>
            </FloatingLabel>
        </Form.Group>
    );
}

export default FormField;