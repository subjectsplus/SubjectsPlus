import { SubmitHandler } from "react-hook-form"
import Button from 'react-bootstrap/Button';
import { GuideTabType } from '@shared/types/guide_types';
import { ModalContainer } from '@components/shared/ModalContainer';
import { TabForm } from './TabForm';
import { GuideTabFormInputs } from '@shared/types/guide_form_types';

type CreateTabModalProps = {
    currentTab: GuideTabType,
    show: boolean,
    onHide: () => void,
    onSubmit: SubmitHandler<GuideTabFormInputs>,
    savingChanges: boolean
}

export const CreateTabModal = ({ currentTab, show, onHide, onSubmit, savingChanges }: CreateTabModalProps) => {
    const modalTitle = "Create Tab";
    const modalFooter = (
        <>
            <Button variant="link" className="btn-link-default" onClick={onHide}>
                Close
            </Button>
            <Button variant="primary" className="btn-gradient btn-round" disabled={savingChanges} form="settings-form" type="submit">
                {savingChanges ? 'Creating...' : 'Create'}
            </Button>
        </>
    );

    return (
        <ModalContainer title={modalTitle} footer={modalFooter} show={show} onHide={onHide}>
            <TabForm currentTab={currentTab} onSubmit={onSubmit} />
        </ModalContainer>
    );
}