
function Form() {
    return (
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
    );
}