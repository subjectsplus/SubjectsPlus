import React, { Component } from 'react';
import Tabs from 'react-bootstrap/Tabs';
import Tab from 'react-bootstrap/Tab';
import Button from 'react-bootstrap/Button';
import Modal from 'react-bootstrap/Modal';
import Form from 'react-bootstrap/Form';
import FloatingLabel from 'react-bootstrap/FloatingLabel';
import Alert from 'react-bootstrap/Alert'
import Utility from '../../../js/Utility/Utility.js';
import SectionContainer from './SectionContainer.js';
import { array } from 'prop-types';

export default class GuideTabContainer extends Component {
    apiLink = '/api/subjects/{subjectId}/tabs';
    postLink = '/api/tabs';
    tabLink = '/api/tabs/{tabId}';

    constructor(props) {
        super(props);
        
        this.state = {
            tabs: null,
            lastTabIndex: 0,
            activeKey: 0,
            isErrored: false,
            showSettings: false,
            savingChanges: false,
            settingsValidated: false,
            deleteTabClicked: false,
            numberUntitled: 0
        };

        this.settingsTabName = React.createRef();
        this.settingsExternalUrl = React.createRef();
        this.settingsTabVisibility = React.createRef();

        this.onTabSelect = this.onTabSelect.bind(this);
        this.toggleSettings = this.toggleSettings.bind(this);
        this.updateCurrentTab = this.updateCurrentTab.bind(this);
        this.handleTabDelete = this.handleTabDelete.bind(this);
        this.handleSettingsSubmit = this.handleSettingsSubmit.bind(this);
    }

    componentDidMount() {
        this.getTabs();
    }

    getAPILink() {
        return this.apiLink.replace('{subjectId}', 
            this.props.guideId);
    }

    getTabLink(tabId) {
        return this.tabLink.replace('{tabId}', 
            tabId);
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
        console.log('Active Key: ', this.state.activeKey.toString());
        if (eventKey === 'new-tab') {
            // Create new tab
            this.newTab();
        } else if (this.state.activeKey.toString() !== eventKey) {
            this.setState({
                activeKey: Number(eventKey),
                settingsValidated: false
            });
        }
    }

    newTab() {
        let numberUntitled = this.state.numberUntitled;
        let initialTabData = {
            label: (numberUntitled === 0 ? 'Untitled' : 'Untitled ' + numberUntitled),
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
                settingsValidated: false,
                numberUntitled: this.state.numberUntitled + 1
            });
        })
        .catch((error) => {
            alert('Error: Failed to add new tab.');
            console.error('Error:', error);
        });
    }

    updateCurrentTab() {
        let currentTab = this.state.tabs[this.state.activeKey];

        let newLabel = Utility.htmlEntityDecode(this.settingsTabName.current.value.trim());
        let newExternalUrl = Utility.htmlEntityDecode(this.settingsExternalUrl.current.value.trim());
        let newVisibility = (this.settingsTabVisibility.current.value === '1');

        // console.log('Tab Id: ' + currentTab.tabId)
        // console.log('New Label: ' + newLabel);
        // console.log('New External URL:' + newExternalUrl);
        // console.log('New Visibility: ' + newVisibility);

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

            let resLink = this.getTabLink(currentTab.tabId);

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
                
                // check if tab label changed from untitled
                let numberUntitled = this.state.numberUntitled;
                if (currentTab.label.includes('Untitled')) {
                    if (!data.label.includes('Untitled')) {
                        numberUntitled--;
                    }
                }

                this.setState({
                    tabs: newTabs,
                    showSettings: false,
                    savingChanges: false,
                    numberUntitled: numberUntitled
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

    deleteCurrentTab() {
        let currentTab = this.state.tabs[this.state.activeKey];
        let resLink = this.getTabLink(currentTab.tabId);

        fetch(resLink, {
            method: 'DELETE',
            headers: {
                'Content-Type': '*/*',
            }
        }).then(response => {
            if (response.ok) {
                // TODO: reorganize tab index
                let newTabs = [...this.state.tabs];
                let newActiveKey = 0;
                let newLastTabIndex = this.state.lastTabIndex - 1;
                
                // remove the deleted tab from tabs state
                newTabs.splice(this.state.activeKey, 1);
                console.log('Old Tabs: ', this.state.tabs);
                console.log('New Tabs: ', newTabs);
                console.log('activeKey: ', this.state.activeKey);
                console.log('lastTabIndex: ', this.state.lastTabIndex);
                if (this.state.activeKey === this.state.lastTabIndex) {
                    // the tab at the end was deleted
                    newActiveKey = this.state.lastTabIndex - 1;
                } else if (this.state.activeKey !== 0) {
                    newActiveKey = this.state.activeKey - 1;
                }
                
                // Update tab index
                for (let index = this.state.activeKey; index <= newLastTabIndex; index++) {
                    console.log('Index: ', index);
                    newTabs[index].tabIndex = index;
                    
                    fetch(this.getTabLink(newTabs[index].tabId), {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            tabIndex: index
                        })
                    }).catch(error => {
                        alert('Error: Failed to update tab index of displaced tab!');
                        console.error('Error: ', error);
                    })
                }

                console.log('New activeKey: ', newActiveKey);
                console.log('New lastTabindex: ', newLastTabIndex);
                this.setState({
                    tabs: newTabs,
                    activeKey: newActiveKey,
                    lastTabIndex: newLastTabIndex,
                    deleteTabClicked: false,
                    showSettings: false
                });
            }
        })
        .catch((error) => {
            alert('Error: Failed to delete tab!');
            console.error('Error:', error);
            this.setState({
                isErrored: true,
                deleteTabClicked: false
            });
        });
    }

    handleTabDelete() {
        if (this.state.deleteTabClicked) {
            this.deleteCurrentTab();
        } else {
            this.setState({
                deleteTabClicked: true
            })
        }
    }

    handleSettingsSubmit(evt) {
        const form = evt.currentTarget;
        if (form.checkValidity() === false) {
          evt.preventDefault();
          evt.stopPropagation();
        } else {
            this.updateCurrentTab();
        }

        this.setState({
            settingsValidated: true
        });
    }

    toggleSettings() {
        this.setState({
            showSettings: !this.state.showSettings,
            settingsValidated: false,
            deleteTabClicked: false
        })
    }

    render() {
        if (this.state.tabs) {
            // Map tab results to bootstrap Tab elements
            let guideTabs = this.state.tabs.map((results, index) => {
                return (
                    <Tab key={results.tabId} eventKey={results.tabIndex} 
                        title={
                            <>
                                {Utility.htmlEntityDecode(results.label)}{' '}
                                {this.state.activeKey === results.tabIndex && 
                                    <a href="#" onClick={this.toggleSettings} key={results.tabId} className="tab-settings-icon">
                                        <i className="fas fa-cog"></i>
                                    </a>}
                            </>
                        }>
                        <SectionContainer tabId={results.tabId} />
                    </Tab>
                );
            });
            
            console.log(this.state.activeKey);
            let currentTab = this.state.tabs[this.state.activeKey];
            console.log('Current tab id: ' + currentTab.tabId);

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
                                        <Form.Control.Feedback type="invalid">
                                            Please enter a tab name with a minimum of 3 characters.
                                        </Form.Control.Feedback>
                                    </FloatingLabel>
                                </Form.Group>
                                <Form.Group className="mb-3" controlId="formGroupTabVisibility">
                                    <FloatingLabel controlId="floatingTabVisibility" label="Visibility">
                                        <Form.Select ref={this.settingsTabVisibility} size="sm" 
                                            aria-label="Set visibility of tab" defaultValue={currentTab.visibility ? '1' : '0'}>
                                            <option value="0">Hidden</option>
                                            <option value="1">Public</option>
                                        </Form.Select>
                                    </FloatingLabel>
                                </Form.Group>
                                <Form.Group className="mb-3" controlId="formGroupExternalUrl">
                                    <FloatingLabel controlId="floatingExternalUrl" label="Redirect URL (Optional)">
                                        <Form.Control ref={this.settingsExternalUrl} type="url" 
                                            defaultValue={currentTab.externalUrl || ''} />
                                            <Form.Control.Feedback type="invalid">
                                                Please provide a valid URL.
                                            </Form.Control.Feedback>
                                    </FloatingLabel>
                                </Form.Group>
                            </Form>
                            <Button variant="danger" onClick={this.handleTabDelete} disabled={this.state.deleteTabClicked || 
                                this.state.lastTabIndex === 0}>
                                <i className="fas fa-trash"></i>{' '}
                                Delete Tab
                            </Button>
                            {this.state.deleteTabClicked && (
                                <Alert variant="danger">
                                    <>
                                        Are you sure you want to delete this tab?{' '}
                                        <a href="#" onClick={() => this.setState({deleteTabClicked: false})}>No</a>{' '}
                                        <a href="#" onClick={this.handleTabDelete}>Yes</a>
                                    </>
                                </Alert>
                            )}
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