import { useForm, SubmitHandler } from "react-hook-form";
import Form from 'react-bootstrap/Form';
import FloatingLabel from 'react-bootstrap/FloatingLabel';
import { GuideTabType } from '@shared/types/guide_types';
import { GuideTabFormInputs } from '@shared/types/guide_form_types';
import { isValidHttpUrl } from '@utility/Utility';

type TabFormProps = {
    currentTab: GuideTabType,
    onSubmit: SubmitHandler<GuideTabFormInputs>
};

export const TabForm = ({ currentTab, onSubmit }: TabFormProps) => {
    const { register, handleSubmit, formState: { errors } } = useForm<GuideTabFormInputs>();

    return (
        <Form noValidate={true} onSubmit={handleSubmit(onSubmit)} id="settings-form">
            <Form.Group className="mb-3" controlId="formGroupTabName">
                <FloatingLabel controlId="floatingTabName" label="Tab Name" className="mb-3">
                    <Form.Control defaultValue={currentTab.label || ''} autoComplete="off" autoFocus={true}
                        isInvalid={errors.hasOwnProperty('label')} {...register('label', { required: true, minLength: 3})} />
                    <Form.Control.Feedback type="invalid">
                        Please enter a tab name with a minimum of 3 characters.
                    </Form.Control.Feedback>
                </FloatingLabel>
            </Form.Group>
            <Form.Group className="mb-3" controlId="formGroupTabVisibility">
                <FloatingLabel controlId="floatingTabVisibility" label="Visibility">
                    <Form.Select size="sm" aria-label="Set visibility of tab" defaultValue={currentTab.visibility ? '1' : '0'}
                        isInvalid={errors.hasOwnProperty('visibility')} {...register('visibility', { required: true })}>
                        <option value="0">Hidden</option>
                        <option value="1">Public</option>
                    </Form.Select>
                </FloatingLabel>
            </Form.Group>
            <Form.Group className="mb-3" controlId="formGroupExternalUrl">
                <FloatingLabel controlId="floatingExternalUrl" label="Redirect URL (Optional)">
                    <Form.Control type="url" defaultValue={currentTab.externalUrl || ''} autoComplete="off"
                        isInvalid={errors.hasOwnProperty('externalUrl')} {...register('externalUrl', { validate: url => url === '' || isValidHttpUrl(url) })}/>
                        <Form.Control.Feedback type="invalid">
                            Please provide a valid URL. (Ex: https://www.subjectsplus.com)
                        </Form.Control.Feedback>
                </FloatingLabel>
            </Form.Group>
        </Form>
    );
}