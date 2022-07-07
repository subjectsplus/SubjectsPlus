import { ModalContainer } from './ModalContainer';
import Button from 'react-bootstrap/Button';

type DeleteConfirmModalProps = {
    resourceName: string,
    show: boolean,
    onHide: () => void,
    confirmOnClick: React.MouseEventHandler<HTMLButtonElement>
};

export const DeleteConfirmModal = ({ resourceName, show, onHide, confirmOnClick}: DeleteConfirmModalProps) => {
    const modalTitle = (
        <>
            <i className="fas fa-exclamation-circle btn-link-danger"></i>
            {' '}
            Delete {resourceName}
        </>
    );

    const modalFooter = (
        <>
            <Button className="me-2" variant="link-default" onClick={onHide}>Cancel</Button>
            <Button className="btn-round" variant="danger" onClick={confirmOnClick}>
                <i className="fas fa-trash"></i>
                {' '}
                Yes, delete
            </Button>
        </>
    );

    return (
        <ModalContainer title={modalTitle} footer={modalFooter} show={show} onHide={onHide}>
            <p>Are you sure you want to delete this {resourceName}?</p>
        </ModalContainer>
    );
}