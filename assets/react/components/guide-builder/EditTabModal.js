import React, { useState } from 'react';
import Button from 'react-bootstrap/Button';
import Modal from 'react-bootstrap/Modal';
import Form from 'react-bootstrap/Form';
import FloatingLabel from 'react-bootstrap/FloatingLabel';
import Alert from 'react-bootstrap/Alert';

function EditTabModal(props) {

    return (
        <Modal show={props.show} onHide={props.onHide}>
            <Modal.Header closeButton>
                <Modal.Title>Edit Tab
                    <span className="fs-xs d-block">{'ID:' + props.currentTab.tabId}</span>
                </Modal.Title>
            </Modal.Header>
            <Modal.Body>
                <Form noValidate validated={props.validated} onSubmit={props.onSubmit} id="settings-form">
                    <Form.Group className="mb-3" controlId="formGroupTabName">
                        <FloatingLabel
                            controlId="floatingTabName"
                            label="Tab Name"
                            className="mb-3"
                        >
                            <Form.Control required ref={props.settingsTabNameRef} minLength="3" defaultValue={props.currentTab.label || 'Untitled'} />
                            <Form.Control.Feedback type="invalid">
                                Please enter a tab name with a minimum of 3 characters.
                            </Form.Control.Feedback>
                        </FloatingLabel>
                    </Form.Group>
                    <Form.Group className="mb-3" controlId="formGroupTabVisibility">
                        <FloatingLabel controlId="floatingTabVisibility" label="Visibility">
                            <Form.Select ref={props.settingsTabVisibilityRef} size="sm" 
                                aria-label="Set visibility of tab" defaultValue={props.currentTab.visibility ? '1' : '0'}>
                                <option value="0">Hidden</option>
                                <option value="1">Public</option>
                            </Form.Select>
                        </FloatingLabel>
                    </Form.Group>
                    <Form.Group className="mb-3" controlId="formGroupExternalUrl">
                        <FloatingLabel controlId="floatingExternalUrl" label="Redirect URL (Optional)">
                            <Form.Control ref={props.settingsExternalUrlRef} type="url" 
                                defaultValue={props.currentTab.externalUrl || ''} />
                                <Form.Control.Feedback type="invalid">
                                    Please provide a valid URL.
                                </Form.Control.Feedback>
                        </FloatingLabel>
                    </Form.Group>
                </Form>
                <Button variant="danger" onClick={props.deleteButtonOnClick} disabled={props.deleteButtonDisabled}>
                    <i className="fas fa-trash"></i>{' '}
                    Delete Tab
                </Button>
                {props.showDeleteConfirmation && (
                    <Alert variant="danger">
                        <>
                            Are you sure you want to delete this tab?{' '}
                            <Button variant="link" onClick={props.declineDeleteOnClick}>No</Button>
                            <Button variant="link" onClick={props.confirmDeleteOnClick}>Yes</Button>
                        </>
                    </Alert>
                )}
            </Modal.Body>
            <Modal.Footer>
                <Button variant="link" className="btn-link-default" onClick={props.onToggle}>
                    Close
                </Button>
                <Button variant="primary" className="btn-gradient btn-round" disabled={props.savingChanges} form="settings-form" type="submit">
                    {props.savingChanges ? 'Saving...' : 'Save Changes'}
                </Button>
            </Modal.Footer>
        </Modal>
    )
}

export default EditTabModal;