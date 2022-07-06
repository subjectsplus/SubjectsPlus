import { useState } from 'react';
import Button from 'react-bootstrap/Button';
import Alert from 'react-bootstrap/Alert';
import { GuideTabType } from '@shared/types/guide_types';
import { ModalContainer } from '@components/shared/ModalContainer';
import { TabForm } from './TabForm';

type EditTabModalProps = {
    currentTab: GuideTabType,
    show: boolean,
    validated: boolean,
    onHide: () => void,
    onSubmit: React.FormEventHandler<HTMLFormElement>,
    deleteButtonOnClick: React.MouseEventHandler<HTMLButtonElement>,
    savingChanges: boolean
}

export const EditTabModal = ({ currentTab, show, validated, onHide, onSubmit, deleteButtonOnClick, savingChanges }: EditTabModalProps) => {
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
            <TabForm currentTab={currentTab} validated={validated} onSubmit={onSubmit} />    
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