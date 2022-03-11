import React, { useState, useEffect, useRef } from 'react';
import Utility from '../../../backend/javascript/Utility/Utility.js';
import GuideAPI from '../../apis/GuideAPI.js';
import Notification from '../../shared/Notification.js';
import SectionContainer from './SectionContainer.js';
import DraggableTab from './DraggableTab.js';
import EditTabModal from './EditTabModal.js';
import Tab from 'react-bootstrap/Tab';
import Nav from 'react-bootstrap/Nav';
import ToastContainer from 'react-bootstrap/ToastContainer'
import { DragDropContext, Droppable } from 'react-beautiful-dnd';

function GuideTabContainer(props) {
    const apiLink = '/api/subjects/{subjectId}/tabs';
    const postLink = '/api/tabs';
    const tabLink = '/api/tabs/{tabId}';

    const [tabs, setTabs] = useState(null);
    const [lastTabIndex, setLastTabIndex] = useState(0);
    const [activeKey, setActiveKey] = useState(0);
    const [isErrored, setIsErrored] = useState(false);
    const [showSettings, setShowSettings] = useState(false);
    const [savingChanges, setSavingChanges] = useState(false);
    const [settingsValidated, setSettingsValidated] = useState(false);
    const [deleteTabClicked, setDeleteTabClicked] = useState(false);
    const [deletingTab, setDeletingTab] = useState(false);
    const [numberUntitled, setNumberUntitled] = useState(0);

    const settingsTabName = useRef();
    const settingsExternalUrl = useRef();
    const settingsTabVisibility = useRef();

    useEffect(() => getTabs(), [props.guideId])

    const getAPILink = () => {
        return apiLink.replace('{subjectId}', 
            props.guideId);
    }

    const getTabLink = (tabId) => {
        return tabLink.replace('{tabId}', 
            tabId);
    }

    const getTabs = () => {
        // formulate the results api link for guide
        const resLink = getAPILink();

        // fetch api results
        fetch(resLink).then(response => {
            if (response.ok) {
                return response.json();
            }

            setIsErrored(true);
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

            setTabs(results['hydra:member']);
            setLastTabIndex(results['hydra:member'].at(-1)['tabIndex']);
            setIsErrored(false);
            setNumberUntitled(numberUntitled);
        })
        .catch(err => {
            console.error(err);
            setIsErrored(true);
        });
    }

    const onTabSelect = (eventKey) => {
        if (eventKey === 'new-tab') {
            // Create new tab
            newTab();
        } else if (activeKey.toString() !== eventKey) {
            setActiveKey(Number(eventKey));
            setSettingsValidated(false);
        }
    }

    const newTab = () => {
        const initialTabData = {
            label: (numberUntitled === 0 ? 'Untitled' : 
                        'Untitled ' + (numberUntitled + 1)),
            tabIndex: lastTabIndex + 1,
            visibility: true,
            subject: '/api/subjects/' + props.guideId
        };

        fetch(postLink, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(initialTabData)
        })
        .then(response => response.json())
        .then(data => {
            setTabs([...tabs, data]);
            setLastTabIndex(data.tabIndex);
            setActiveKey(data.tabIndex);
            setSettingsValidated(false);
            setNumberUntitled(numberUntitled + 1)
        })
        .catch((error) => {
            console.error(error);
            //this.addNotification('Error', 'Failed to add new tab!');
        });
    }

    const updateCurrentTab = () => {
        const currentTab = tabs[activeKey];

        const newLabel = Utility.htmlEntityDecode(settingsTabName.current.value.trim());
        const newExternalUrl = Utility.htmlEntityDecode(settingsExternalUrl.current.value.trim());
        const newVisibility = (settingsTabVisibility.current.value === '1');

        let data = {};

        // Check for any changes in tab data
        if (newLabel && newLabel !== currentTab.label) data['label'] = newLabel;
        
        if (newExternalUrl && newExternalUrl !== currentTab.externalUrl) 
            data['externalUrl'] = newExternalUrl;

        if (newVisibility !== currentTab.visibility) data['visibility'] = newVisibility;

        if (!Utility.objectIsEmpty(data)) {
            // changes have been made to tab data
            setSavingChanges(true);

            fetch(getTabLink(currentTab.tabId), {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                // replace old tab data with new tab data
                let newTabs = [...tabs];
                newTabs[activeKey] = data;

                setTabs(newTabs);
                setShowSettings(false);
                setSavingChanges(false);
            })
            .catch((error) => {
                console.error(error);
                //this.addNotification('Error', 'Failed to update tab!');
                setIsErrored(true);
                setShowSettings(false);
                setSavingChanges(false);
                setSettingsValidated(false);
            });
        } else {
            setShowSettings(false);
            setSavingChanges(false);
            setSettingsValidated(false);
        }
    }

    const deleteCurrentTab = () => {
        let currentTab = tabs[activeKey];

        fetch(getTabLink(currentTab.tabId), {
            method: 'DELETE',
            headers: {
                'Content-Type': '*/*',
            }
        }).then(response => {
            if (response.ok) {
                let newTabs = [...tabs];
                let newActiveKey = 0;
                let newLastTabIndex = lastTabIndex - 1;
                
                // remove the deleted tab from tabs
                newTabs.splice(activeKey, 1);

                if (activeKey === lastTabIndex) {
                    // the tab at the end was deleted
                    newActiveKey = lastTabIndex - 1;
                } else if (activeKey !== 0) {
                    newActiveKey = activeKey - 1;
                }
                
                // Update tab index
                Promise.all(
                    newTabs.slice(activeKey, lastTabIndex).map((tab, index) => {
                        index += activeKey;
                        tab.tabIndex = index;
                        return fetch(getTabLink(tab.tabId), {
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
                    setActiveKey(newActiveKey);
                    setLastTabIndex(newLastTabIndex);
                    setTabs(newTabs);
                    setDeleteTabClicked(false);
                    setDeletingTab(false);
                    setShowSettings(false);
                }).catch(error => {
                    console.error(error);
                    //this.addNotification('Error', 'Failed to update tab index of displaced tab!');
                });
            }
        })
        .catch((error) => {
            console.error(error);
            //this.addNotification('Error', 'Failed to delete tab!');
            setIsErrored(true);
            setDeleteTabClicked(false);
            setDeletingTab(false);
        });
    }

    const handleTabDelete = () => {
        if (deleteTabClicked) {
            setDeletingTab(true);
            deleteCurrentTab();
        } else {
            setDeleteTabClicked(true);
        }

        return false;
    }

    const handleSettingsSubmit = (evt) => {
        evt.preventDefault();

        const form = evt.currentTarget;
        if (form.checkValidity() === false) {
          evt.stopPropagation();
        } else {
            updateCurrentTab();
        }

        setSettingsValidated(true);

        return false;
    }

    const toggleSettings = () => {
        setShowSettings(!showSettings);
        setSettingsValidated(false);
        setDeleteTabClicked(false);

        return false;
    }

    const reorderTab = (sourceIndex, destinationIndex) => {
        // copy existing tabs
        let newTabs = [...tabs];

        // reorder tabs
        let [reorderedItem] = newTabs.splice(sourceIndex, 1);
        newTabs.splice(destinationIndex, 0, reorderedItem);

        // Update tab index
        newTabs.map((tab, index) => {
            tab.tabIndex = index;
        });

        setActiveKey(destinationIndex);
        setTabs(newTabs);

        Promise.all(
            newTabs.map((tab, index) => {
                return fetch(getTabLink(tab.tabId), {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        tabIndex: index
                    })
                })
            })
        )
        .then(() => {
            //this.addNotification('Success', 'Successfully updated tabs!');
        })
        .catch(error => {
            console.error(error);
            //this.addNotification('Error', 'Failed to update tab index of displaced tab!');
        });
    }
    
    const handleOnDragEnd = (result) => {
        console.log(result);
        if (result.type === 'tab') {
            // exit if element hasn't changed position
            if (result.source.index === result.destination.index) return;
            reorderTab(result.source.index, result.destination.index);
        } else if (result.type === 'pluslet') {
            console.log('Pluslet onDragEnd Handler');
        }
    }

    const guideTabContent = () => {
        if (tabs) {
            const currentTab = tabs[activeKey];
            console.log('Current tab: ', currentTab);
            console.log('Tabs: ', tabs);
            console.log('Active key: ', activeKey);
            
            // convert tabs data to draggable nav links
            const guideTabs = tabs.map(tab => (
                <DraggableTab key={tab.tabId} tab={tab} active={activeKey === tab.tabIndex} 
                    onClick={toggleSettings}/> 
            ));

            // generate tab content
            const tabsContent = tabs.map(tab => (
                <Tab.Pane key={tab.tabId} eventKey={tab.tabIndex}>
                    <SectionContainer tabId={tab.tabId} />
                </Tab.Pane>
            ));

            return (
                <>
                    {/* Guide Tab Container consisting of individual tab elements */}
                    <DragDropContext onDragEnd={handleOnDragEnd}>
                        <Droppable type="tab" style={{ transform: "none" }} droppableId="guide-tabs-container" direction="horizontal">
                            {(provided) => (
                                <div id="guide-tabs-container" {...provided.droppableProps} ref={provided.innerRef}>
                                    <Tab.Container id="guide-tabs" onSelect={onTabSelect} activeKey={activeKey}>
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
                    <EditTabModal currentTab={currentTab} show={showSettings} onToggle={toggleSettings}
                        validated={settingsValidated} onSubmit={handleSettingsSubmit}
                        settingsTabNameRef={settingsTabName} settingsTabVisibilityRef={settingsTabVisibility}
                        settingsExternalUrlRef={settingsExternalUrl} deleteButtonOnClick={handleTabDelete}
                        deleteButtonDisabled={deleteTabClicked || lastTabIndex === 0}
                        showDeleteConfirmation={deleteTabClicked} 
                        declineDeleteOnClick={() => setDeleteTabClicked(false)}
                        confirmDeleteOnClick={deletingTab ?  null : handleTabDelete}
                        savingChanges={savingChanges}
                    />
                </>
            );
        } else if (isErrored) {
            return (<p>Error: Failed to load tabs through API Endpoint!</p>);
        } else {
            return (<p>Loading Tabs...</p>);
        }
    }

    return guideTabContent();
}

export default GuideTabContainer;