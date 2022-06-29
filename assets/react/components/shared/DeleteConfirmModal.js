import Modal from 'react-bootstrap/Modal';
import Button from 'react-bootstrap/Button';

function DeleteConfirmModal({ resourceName, show, onHide, confirmOnClick}) {
    return (
        <Modal show={show} onHide={onHide}>
            <Modal.Header closeButton>
                <Modal.Title>
                    <i className="fas fa-exclamation-circle btn-link-danger"></i>
                    {' '}
                    Delete {resourceName}
                </Modal.Title>
            </Modal.Header>

            <Modal.Body>
                <p>Are you sure you want to delete this {resourceName}?</p>
            </Modal.Body>

            <Modal.Footer>
                <Button className="me-2" variant="link-default" onClick={onHide}>Cancel</Button>
                <Button className="btn-round" variant="danger" onClick={confirmOnClick}>
                    <i className="fas fa-trash"></i>
                    {' '}
                    Yes, delete
                </Button>
            </Modal.Footer>
        </Modal>
    );
}

export default DeleteConfirmModal;