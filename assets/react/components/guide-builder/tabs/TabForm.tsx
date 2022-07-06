import Form from 'react-bootstrap/Form';
import FloatingLabel from 'react-bootstrap/FloatingLabel';
import { GuideTabType } from '@shared/types/guide_types';

type TabFormProps = {
    currentTab: GuideTabType,
    validated: boolean,
    onSubmit: React.FormEventHandler<HTMLFormElement>
};

export const TabForm = ({ currentTab, validated, onSubmit }: TabFormProps) => {
    return (
        <Form noValidate validated={validated} onSubmit={onSubmit} id="settings-form">
            <Form.Group className="mb-3" controlId="formGroupTabName">
                <FloatingLabel
                    controlId="floatingTabName"
                    label="Tab Name"
                    className="mb-3"
                >
                    <Form.Control name="label" required={true} minLength={3} defaultValue={currentTab.label || ''} />
                    <Form.Control.Feedback type="invalid">
                        Please enter a tab name with a minimum of 3 characters.
                    </Form.Control.Feedback>
                </FloatingLabel>
            </Form.Group>
            <Form.Group className="mb-3" controlId="formGroupTabVisibility">
                <FloatingLabel controlId="floatingTabVisibility" label="Visibility">
                    <Form.Select name="visibility" size="sm" 
                        aria-label="Set visibility of tab" defaultValue={currentTab.visibility ? '1' : '0'}>
                        <option value="0">Hidden</option>
                        <option value="1">Public</option>
                    </Form.Select>
                </FloatingLabel>
            </Form.Group>
            <Form.Group className="mb-3" controlId="formGroupExternalUrl">
                <FloatingLabel controlId="floatingExternalUrl" label="Redirect URL (Optional)">
                    <Form.Control name="externalUrl" type="url" defaultValue={currentTab.externalUrl || ''} />
                        <Form.Control.Feedback type="invalid">
                            Please provide a valid URL.
                        </Form.Control.Feedback>
                </FloatingLabel>
            </Form.Group>
        </Form>
    );
}