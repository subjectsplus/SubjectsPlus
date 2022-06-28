import React, { useState } from 'react';
import Modal from 'react-bootstrap/Modal';

function ModalContainer({title, children, onSubmit, onHide}) {
    const [show, setShow] = useState(false);

    return (
        <Modal show={show} onHide={onHide}>
            <Modal.Header closeButton>
                <Modal.Title>
                    {title}
                </Modal.Title>
            </Modal.Header>
            <Modal.Body>
                {children}
            </Modal.Body>
            <Modal.Footer>
                <Button variant="link" className="btn-link-default" onClick={() => setShow(false)}>
                    {closeText}
                </Button>
                <Button variant="primary" className="btn-gradient btn-round" disabled={isSubmitDisabled} form="settings-form" type="submit">
                    {submitText}
                </Button>
            </Modal.Footer>
        </Modal>
    );
}

export default Modal;