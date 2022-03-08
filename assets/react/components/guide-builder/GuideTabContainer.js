import React, { Component } from 'react';
import Utility from '../../../backend/javascript/Utility/Utility.js';
import Notification from '../../shared/Notification.js';
import SectionContainer from './SectionContainer.js';
import DraggableTab from './DraggableTab.js';
import EditTabModal from './EditTabModal.js';
import Tab from 'react-bootstrap/Tab';
import Nav from 'react-bootstrap/Nav';
import ToastContainer from 'react-bootstrap/ToastContainer'
import { DragDropContext, Droppable } from 'react-beautiful-dnd';

export default class GuideTabContainer extends Component {
    apiLink = '/api/subjects/{subjectId}/tabs';
    postLink = '/api/tabs';
    tabLink = '/api/tabs/{tabId}';
    putTabsLink = '/api/subjects/{subjectId}';

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
            deletingTab: false,
            numberUntitled: 0,
            notifications: [],
        };

        this.settingsTabName = React.createRef();
        this.settingsExternalUrl = React.createRef();
        this.settingsTabVisibility = React.createRef();

        this.onTabSelect = this.onTabSelect.bind(this);
        this.toggleSettings = this.toggleSettings.bind(this);
        this.updateCurrentTab = this.updateCurrentTab.bind(this);
        this.handleTabDelete = this.handleTabDelete.bind(this);
        this.handleSettingsSubmit = this.handleSettingsSubmit.bind(this);
        this.handleOnDragEnd = this.handleOnDragEnd.bind(this);
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
            // Retrieve highest untitled count
            let numberUntitled = 0;
            results['hydra:member'].forEach(result => {
                if (result.label.includes('Untitled')) {
                    if (result.label.match(/\d+/)) {
                        numberUntitled = Math.max(result.label.match(/\d+/)[0], numberUntitled);
                    } else {
                        numberUntitled = Math.max(numberUntitled, 1);
                    }
                }
            });

            this.setState({
                tabs: results['hydra:member'],
                lastTabIndex: results['hydra:member'].at(-1)['tabIndex'],
                isErrored: false,
                numberUntitled: numberUntitled
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
        } else if (this.state.activeKey.toString() !== eventKey) {
            this.setState({
                activeKey: Number(eventKey),
                settingsValidated: false
            });
        }
    }

    newTab() {
        let initialTabData = {
            label: (this.state.numberUntitled === 0 ? 'Untitled' : 
                        'Untitled ' + (this.state.numberUntitled + 1)),
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
            console.error(error);
            this.addNotification('Error', 'Failed to add new tab!');
        });
    }

    updateCurrentTab() {
        let currentTab = this.state.tabs[this.state.activeKey];

        let newLabel = Utility.htmlEntityDecode(this.settingsTabName.current.value.trim());
        let newExternalUrl = Utility.htmlEntityDecode(this.settingsExternalUrl.current.value.trim());
        let newVisibility = (this.settingsTabVisibility.current.value === '1');

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

                this.setState({
                    tabs: newTabs,
                    showSettings: false,
                    savingChanges: false
                });
            })
            .catch((error) => {
                console.error(error);
                this.addNotification('Error', 'Failed to update tab!');
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
                let newTabs = [...this.state.tabs];
                let newActiveKey = 0;
                let newLastTabIndex = this.state.lastTabIndex - 1;
                
                // remove the deleted tab from tabs
                newTabs.splice(this.state.activeKey, 1);

                if (this.state.activeKey === this.state.lastTabIndex) {
                    // the tab at the end was deleted
                    newActiveKey = this.state.lastTabIndex - 1;
                } else if (this.state.activeKey !== 0) {
                    newActiveKey = this.state.activeKey - 1;
                }
                
                // Update tab index
                Promise.all(
                    newTabs.slice(this.state.activeKey, newLastTabIndex + 1).map((tab, index) => {
                        index += this.state.activeKey;
                        tab.tabIndex = index;
                        return fetch(this.getTabLink(tab.tabId), {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                tabIndex: index
                            })
                        });
                    })
                ).then(() => {
                    this.setState({
                        tabs: newTabs,
                        activeKey: newActiveKey,
                        lastTabIndex: newLastTabIndex,
                        deleteTabClicked: false,
                        deletingTab: false,
                        showSettings: false
                    });
                }).catch(error => {
                    console.error(error);
                    this.addNotification('Error', 'Failed to update tab index of displaced tab!');
                });
            }
        })
        .catch((error) => {
            console.error(error);
            this.addNotification('Error', 'Failed to delete tab!');
            this.setState({
                isErrored: true,
                deleteTabClicked: false,
                deletingTab: false
            });
        });
    }

    handleTabDelete() {
        if (this.state.deleteTabClicked) {
            this.setState({
                deletingTab: true
            }, () => {
                this.deleteCurrentTab();
            });
        } else {
            this.setState({
                deleteTabClicked: true
            })
        }

        return false;
    }

    handleSettingsSubmit(evt) {
        evt.preventDefault();

        const form = evt.currentTarget;
        if (form.checkValidity() === false) {
          evt.stopPropagation();
        } else {
            this.updateCurrentTab();
        }

        this.setState({
            settingsValidated: true
        });

        return false;
    }

    toggleSettings() {
        this.setState({
            showSettings: !this.state.showSettings,
            settingsValidated: false,
            deleteTabClicked: false
        });

        return false;
    }

    handleOnDragEnd(result) {
        // exit if tab hasn't changed position
        if (result.source.index === result.destination.index) return;

        // copy existing tabs
        let newTabs = [...this.state.tabs];

        // reorder tabs
        let [reorderedItem] = newTabs.splice(result.source.index, 1);
        newTabs.splice(result.destination.index, 0, reorderedItem);

        // Update tab index
        newTabs.map((tab, index) => {
            tab.tabIndex = index;
        });

        this.setState({
            tabs: newTabs,
            activeKey: result.destination.index,
        }, () => Promise.all(
                newTabs.map((tab, index) => {
                    return fetch(this.getTabLink(tab.tabId), {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            tabIndex: index
                        })
                    })
                })
            ).then(() => {
                this.addNotification('Success', 'Successfully updated tabs!');
            })
            .catch(error => {
                console.error(error);
                this.addNotification('Error', 'Failed to update tab index of displaced tab!');
            })
        );
    }

    addNotification(title="Notification", body) {
        this.setState({
            notifications: [...this.state.notifications, {
                title: title,
                body: body
            }]
        })
    }

    render() {
        if (this.state.tabs) {
            // convert tabs data to draggable nav links
            let guideTabs = this.state.tabs.map(tab => (
              <DraggableTab key={tab.tabId} tab={tab} active={this.state.activeKey === tab.tabIndex} 
                onClick={this.toggleSettings}/> 
            ));
            
            // generate tab content
            let tabsContent = this.state.tabs.map(results => (
                <Tab.Pane key={results.tabId} eventKey={results.tabIndex}>
                    <SectionContainer tabId={results.tabId} />
                </Tab.Pane>
            ))
            
            let currentTab = this.state.tabs[this.state.activeKey];

            return (
                <>
                    {/* Guide Tab Container consisting of individual tab elements */}
                    <DragDropContext onDragEnd={this.handleOnDragEnd}>
                        <Droppable style={{ transform: "none" }} droppableId="guide-tabs-container" direction="horizontal">
                            {(provided) => (
                                <div id="guide-tabs-container" {...provided.droppableProps} ref={provided.innerRef}>
                                    <Tab.Container id="guide-tabs" onSelect={this.onTabSelect}>
                                        <Nav variant="tabs">
                                            {guideTabs}
                                            {provided.placeholder}
                                            <Nav.Link as="div" key="new-tab" eventKey="new-tab">
                                                <i className="fas fa-plus"></i>
                                            </Nav.Link>
                                        </Nav>
                                        <Tab.Content>
                                            {tabsContent}
                                        </Tab.Content>
                                    </Tab.Container>
                                </div>
                            )}
                        </Droppable>
                    </DragDropContext>
                    
                    {/* Modal Form for editing tabs */}
                    <EditTabModal currentTab={currentTab} show={this.state.showSettings} onToggle={this.toggleSettings}
                        validated={this.state.settingsValidated} onSubmit={this.handleSettingsSubmit}
                        settingsTabNameRef={this.settingsTabName} settingsTabVisibilityRef={this.settingsTabVisibility}
                        settingsExternalUrlRef={this.settingsExternalUrl} deleteButtonOnClick={this.handleTabDelete}
                        deleteButtonDisabled={this.state.deleteTabClicked || this.state.lastTabIndex === 0}
                        showDeleteConfirmation={this.state.deleteTabClicked} 
                        declineDeleteOnClick={() => this.setState({deleteTabClicked: false})}
                        confirmDeleteOnClick={this.state.deletingTab ?  null : this.handleTabDelete}
                        savingChanges={this.state.savingChanges}
                    />

                    {/* Notifications */}
                    <ToastContainer position="top-end">
                        {this.state.notifications.map(notification => {
                            <Notification visible={true} title={notification.title} body={notification.body} />
                        })}
                    </ToastContainer>
                </>
            );
        } else if (this.state.isErrored) {
            return (<p>Error: Failed to load tabs through API Endpoint!</p>);
        } else {
            return (<p>Loading Tabs...</p>);
        }
    }
}