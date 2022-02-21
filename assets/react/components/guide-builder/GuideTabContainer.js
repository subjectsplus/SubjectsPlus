import React, { Component } from 'react';
import Tabs from 'react-bootstrap/Tabs';
import Tab from 'react-bootstrap/Tab';
import Button from 'react-bootstrap/Button';
import Modal from 'react-bootstrap/Modal';
import Form from 'react-bootstrap/Form';
import FloatingLabel from 'react-bootstrap/FloatingLabel';
import Utility from '../../../js/Utility/Utility.js';
import SectionContainer from './SectionContainer.js';

export default class GuideTabContainer extends Component {
    apiLink = '/api/subjects/{subjectId}/tabs';
    postLink = '/api/tabs';
    putLink = '/api/tabs/{tabId}';

    constructor(props) {
        super(props);
        
        this.state = {
            tabs: null,
            lastTabIndex: 0,
            activeKey: '0',
            isErrored: false,
            showSettings: false,
            savingChanges: false,
            settingsValidated: false,
        };

        this.settingsTabName = React.createRef();
        this.settingsExternalUrl = React.createRef();
        this.settingsTabVisibility = React.createRef();

        this.onTabSelect = this.onTabSelect.bind(this);
        this.toggleSettings = this.toggleSettings.bind(this);
        this.updateTab = this.updateTab.bind(this);
        this.handleSettingsSubmit = this.handleSettingsSubmit.bind(this);
    }

    componentDidMount() {
        this.getTabs();
    }

    getAPILink() {
        return this.apiLink.replace('{subjectId}', 
            this.props.guideId);
    }

    getTabs() {
        // formulate the results api link for guide
        let resLink = this.getAPILink();

        // fetch api results
        fetch(resLink).then(response => {
            if (response.ok) {
                return response.json();
            }

            this.setState({
                isErrored: true
            });
        })
        .then(results => {
            this.setState({
                tabs: results['hydra:member'],
                lastTabIndex: results['hydra:member'].at(-1)['tabIndex'],
                isErrored: false
            });
        }
        )
        .catch(err => {
            console.error(err);
            this.setState({
                isErrored: true
            });
        });
    }

    onTabSelect(eventKey) {
        if (eventKey === 'new-tab') {
            // Create new tab
            this.newTab();
        } else if (this.state.activeKey !== eventKey) {
            this.setState({
                activeKey: eventKey,
                settingsValidated: false
            });
        }
    }

    newTab() {
        let initialTabData = {
            label: 'Untitled',
            tabIndex: this.state.lastTabIndex + 1,
            visibility: true,
            subject: '/api/subjects/' + this.props.guideId
        };

        fetch(this.postLink, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(initialTabData)
        })
        .then(response => response.json())
        .then(data => {
            this.setState({
                tabs: [...this.state.tabs, data],
                lastTabIndex: data.tabIndex,
                activeKey: data.tabIndex,
                settingsValidated: false
            });
        })
        .catch((error) => {
            alert('Error: Failed to add new tab.');
            console.error('Error:', error);
        });
    }

    updateTab() {
        let currentTab = this.state.tabs[this.state.activeKey];

        let newLabel = Utility.htmlEntityDecode(this.settingsTabName.current.value.trim());
        let newExternalUrl = Utility.htmlEntityDecode(this.settingsExternalUrl.current.value.trim());
        let newVisibility = (this.settingsTabVisibility.current.value === '1');

        console.log('Tab Id: ' + currentTab.tabId)
        console.log('New Label: ' + newLabel);
        console.log('New External URL:' + newExternalUrl);
        console.log('New Visibility: ' + newVisibility);

        let data = {};

        // Check for any changes in tab data
        if (newLabel && newLabel !== currentTab.label) data['label'] = newLabel;
        
        if (newExternalUrl && newExternalUrl !== currentTab.externalUrl) 
            data['externalUrl'] = newExternalUrl;

        if (newVisibility !== currentTab.visibility) data['visibility'] = newVisibility;

        if (!Utility.objectIsEmpty(data)) {
            // changes have been made to tab data
            this.setState({
                savingChanges: true
            });

            let resLink = this.putLink.replace('{tabId}', currentTab.tabId);

            fetch(resLink, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                // replace old tab data with new tab data
                let newTabs = [...this.state.tabs];
                newTabs[this.state.activeKey] = data;
                
                this.setState({
                    tabs: newTabs,
                    showSettings: false,
                    savingChanges: false
                });
            })
            .catch((error) => {
                alert('Error: Failed to update tab!');
                console.error('Error:', error);
                this.setState({
                    isErrored: true,
                    showSettings: false,
                    savingChanges: false,
                    settingsValidated: false
                });
            });
        } else {
            this.setState({
                showSettings: false,
                savingChanges: false,
                settingsValidated: false
            });
        }
    }

    handleSettingsSubmit(evt) {
        const form = evt.currentTarget;
        if (form.checkValidity() === false) {
          evt.preventDefault();
          evt.stopPropagation();
        } else {
            this.updateTab();
        }

        this.setState({
            settingsValidated: true
        });
    }

    toggleSettings() {
        this.setState({
            showSettings: !this.state.showSettings,
            settingsValidated: false
        })
    }

    render() {
        if (this.state.tabs) {
            // Map tab results to bootstrap Tab elements
            let guideTabs = this.state.tabs.map((results, index) => {
                let tabIndex = results.tabIndex.toString();
                return (
                    <Tab key={results.tabId} eventKey={tabIndex} 
                        title={
                            <>
                                {Utility.htmlEntityDecode(results.label)}{' '}
                                {this.state.activeKey === tabIndex && 
                                    <a href="#" onClick={this.toggleSettings} key={results.tabId} className="tab-settings-icon">
                                        <i className="fas fa-cog"></i>
                                    </a>}
                            </>
                        }>
                        <SectionContainer tabId={results.tabId} />
                    </Tab>
                );
            });
            
            let currentTab = this.state.tabs[this.state.activeKey];
            console.log('Current tab id: ' + currentTab.tabId);
            console.log(currentTab);

            return (
                <>
                    {/* Guide Tab Container consisting of individual tab elements */}
                    <div id="guide-tabs-container">
                        <Tabs activeKey={this.state.activeKey} id="guide-tabs" onSelect={this.onTabSelect}>
                            {guideTabs}
                            <Tab key="new-tab" eventKey="new-tab" 
                                title={<i className="fas fa-plus"></i>} 
                            />
                        </Tabs>
                    </div>
                    
                    {/* Modal Form for editing tabs */}
                    {console.log('Validated: ' + this.state.settingsValidated)}
                    <Modal show={this.state.showSettings} onHide={this.toggleSettings}>
                        <Modal.Header closeButton>
                            <Modal.Title>Edit Tab</Modal.Title>
                        </Modal.Header>
                        <Modal.Body>
                            <Form noValidate validated={this.state.settingsValidated} onSubmit={this.handleSettingsSubmit} id="settings-form">
                                <Form.Group className="mb-3" controlId="formGroupTabName">
                                    <FloatingLabel
                                        controlId="floatingTabName"
                                        label="Tab Name"
                                        className="mb-3"
                                    >
                                        <Form.Control required ref={this.settingsTabName} minLength="3" defaultValue={currentTab.label || 'Untitled'} />
                                        <Form.Control.Feedback type="valid">Looks good!</Form.Control.Feedback>
                                        <Form.Control.Feedback type="invalid">
                                            Please enter a tab name with a minimum of 3 characters.
                                        </Form.Control.Feedback>
                                    </FloatingLabel>
                                </Form.Group>
                                <Form.Group className="mb-3" controlId="formGroupExternalUrl">
                                    <FloatingLabel controlId="floatingExternalUrl" label="External URL">
                                        <Form.Control ref={this.settingsExternalUrl} type="url" 
                                            defaultValue={currentTab.externalUrl || ''} />
                                            <Form.Control.Feedback type="valid">Looks good!</Form.Control.Feedback>
                                            <Form.Control.Feedback type="invalid">
                                                Please provide a valid URL.
                                            </Form.Control.Feedback>
                                    </FloatingLabel>
                                </Form.Group>
                                < br/>
                                <Form.Group className="mb-3" controlId="formGroupTabVisibility">
                                    <FloatingLabel controlId="floatingTabVisibility" label="Visibility">
                                        <Form.Select ref={this.settingsTabVisibility} size="sm" 
                                            aria-label="Set visibility of tab" defaultValue={currentTab.visibility ? '1' : '0'}>
                                            <option value="0">Hidden</option>
                                            <option value="1">Public</option>
                                        </Form.Select>
                                    </FloatingLabel>
                                </Form.Group>
                            </Form>
                        </Modal.Body>
                        <Modal.Footer>
                            <Button variant="secondary" onClick={this.toggleSettings}>
                                Close
                            </Button>
                            <Button variant="primary" disabled={this.state.savingChanges} form="settings-form" type="submit">
                                {this.state.savingChanges ? 'Saving...' : 'Save Changes'}
                            </Button>
                        </Modal.Footer>
                    </Modal>
                </>
            );
        } else if (this.state.isErrored) {
            return (<p>Error: Failed to load tabs through API Endpoint!</p>);
        } else {
            return (<p>Loading Tabs...</p>);
        }
    }
}