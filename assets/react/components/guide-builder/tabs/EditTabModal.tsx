import { useState } from 'react';
import { SubmitHandler } from "react-hook-form"
import Button from 'react-bootstrap/Button';
import Alert from 'react-bootstrap/Alert';
import { GuideTabType } from '@shared/types/guide_types';
import { ModalContainer } from '@components/shared/ModalContainer';
import { TabForm } from './TabForm';
import { GuideTabFormInputs } from '@shared/types/guide_form_types';

type EditTabModalProps = {
    currentTab: GuideTabType,
    show: boolean,
    onHide: () => void,
    onSubmit: SubmitHandler<GuideTabFormInputs>,
    deleteButtonOnClick: React.MouseEventHandler<HTMLButtonElement>,
    savingChanges: boolean
}

export const EditTabModal = ({ currentTab, show, onHide, onSubmit, deleteButtonOnClick, savingChanges }: EditTabModalProps) => {
    const [deleteButtonClicked, setDeleteButtonClicked] = useState<boolean>(false);

    const modalTitle = (<>Edit Tab <span className="fs-xs d-block">{'ID:' + currentTab.tabId}</span></>);
    const modalFooter = (
        <>
            <Button variant="link" className="btn-link-default" onClick={onHide}>
                Close
            </Button>
            <Button variant="primary" className="btn-gradient btn-round" disabled={savingChanges} form="settings-form" type="submit">
                {savingChanges ? 'Saving...' : 'Save Changes'}
            </Button>
        </>
    );

    return (
        <ModalContainer title={modalTitle} footer={modalFooter} show={show} onHide={onHide}>
            <TabForm currentTab={currentTab} onSubmit={onSubmit} />    
            <Button variant="link" className="btn-icon-default p-0 fs-sm" onClick={() => setDeleteButtonClicked(true)} disabled={deleteButtonClicked} title="Delete Tab">
                <i className="fas fa-trash"></i>{' '}
                Delete Tab
            </Button>
            {deleteButtonClicked && (
                <Alert variant="danger" className="fs-sm">
                    <>
                        Are you sure you want to delete this tab?{' '}
                        <Button variant="link" className="btn-link-default fs-sm" onClick={() => setDeleteButtonClicked(false)}>No</Button>
                        <Button variant="link" className="btn-link-default fs-sm" onClick={deleteButtonOnClick}>Yes</Button>
                    </>
                </Alert>
            )}
        </ModalContainer>
    );
}