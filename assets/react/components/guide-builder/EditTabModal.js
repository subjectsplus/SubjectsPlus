import React, { Component } from 'react';
import Button from 'react-bootstrap/Button';
import Modal from 'react-bootstrap/Modal';
import Form from 'react-bootstrap/Form';
import FloatingLabel from 'react-bootstrap/FloatingLabel';
import Alert from 'react-bootstrap/Alert';

export default class EditTabModal extends Component {

    constructor(props) {
        super(props);
    }

    render() {
        return (
            <Modal show={this.props.show} onHide={this.props.onToggle}>
                <Modal.Header closeButton>
                    <Modal.Title>{'Edit Tab (' + this.props.currentTab.tabId + ')'}</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <Form noValidate validated={this.props.validated} onSubmit={this.props.onSubmit} id="settings-form">
                        <Form.Group className="mb-3" controlId="formGroupTabName">
                            <FloatingLabel
                                controlId="floatingTabName"
                                label="Tab Name"
                                className="mb-3"
                            >
                                <Form.Control required ref={this.props.settingsTabNameRef} minLength="3" defaultValue={this.props.currentTab.label || 'Untitled'} />
                                <Form.Control.Feedback type="invalid">
                                    Please enter a tab name with a minimum of 3 characters.
                                </Form.Control.Feedback>
                            </FloatingLabel>
                        </Form.Group>
                        <Form.Group className="mb-3" controlId="formGroupTabVisibility">
                            <FloatingLabel controlId="floatingTabVisibility" label="Visibility">
                                <Form.Select ref={this.props.settingsTabVisibilityRef} size="sm" 
                                    aria-label="Set visibility of tab" defaultValue={this.props.currentTab.visibility ? '1' : '0'}>
                                    <option value="0">Hidden</option>
                                    <option value="1">Public</option>
                                </Form.Select>
                            </FloatingLabel>
                        </Form.Group>
                        <Form.Group className="mb-3" controlId="formGroupExternalUrl">
                            <FloatingLabel controlId="floatingExternalUrl" label="Redirect URL (Optional)">
                                <Form.Control ref={this.props.settingsExternalUrlRef} type="url" 
                                    defaultValue={this.props.currentTab.externalUrl || ''} />
                                    <Form.Control.Feedback type="invalid">
                                        Please provide a valid URL.
                                    </Form.Control.Feedback>
                            </FloatingLabel>
                        </Form.Group>
                    </Form>
                    <Button variant="danger" onClick={this.props.deleteButtonOnClick} disabled={this.props.deleteButtonDisabled}>
                        <i className="fas fa-trash"></i>{' '}
                        Delete Tab
                    </Button>
                    {this.props.showDeleteConfirmation && (
                        <Alert variant="danger">
                            <>
                                Are you sure you want to delete this tab?{' '}
                                <Button variant="link" onClick={this.props.declineDeleteOnClick}>No</Button>
                                <Button variant="link" onClick={this.props.confirmDeleteOnClick}>Yes</Button>
                            </>
                        </Alert>
                    )}
                </Modal.Body>
                <Modal.Footer>
                    <Button variant="secondary" onClick={this.props.onToggle}>
                        Close
                    </Button>
                    <Button variant="secondary" disabled={this.props.savingChanges} form="settings-form" type="submit">
                        {this.props.savingChanges ? 'Saving...' : 'Save Changes'}
                    </Button>
                </Modal.Footer>
            </Modal>
        );
    }
}