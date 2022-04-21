import React, { useState, useEffect, useRef } from 'react';
import { htmlEntityDecode, objectIsEmpty } from '#utility/Utility';
import { useReorderTab, useFetchTabs, useCreateTab, useUpdateTab, useDeleteTab } from '#api/guide/TabAPI';
import SectionContainer from './SectionContainer';
import DraggableTab from './DraggableTab';
import EditTabModal from './EditTabModal';
import Tab from 'react-bootstrap/Tab';
import Nav from 'react-bootstrap/Nav';
import { DragDropContext, Droppable } from 'react-beautiful-dnd';
import { v4 as uuidv4 } from 'uuid';
import { toast } from 'react-toastify';

function GuideTabContainer(props) {
    const [lastTabIndex, setLastTabIndex] = useState(0);
    const [activeKey, setActiveKey] = useState(0);
    const [showSettings, setShowSettings] = useState(false);
    const [savingChanges, setSavingChanges] = useState(false);
    const [settingsValidated, setSettingsValidated] = useState(false);
    const [deleteTabClicked, setDeleteTabClicked] = useState(false);
    const [deletingTab, setDeletingTab] = useState(false);
    const [numberUntitled, setNumberUntitled] = useState(0);
    const [draggingTab, setDraggingTab] = useState(false);

    const settingsTabName = useRef();
    const settingsExternalUrl = useRef();
    const settingsTabVisibility = useRef();

    const {isLoading, isError, data, error} = useFetchTabs(props.subjectId, !draggingTab);
    
    const reorderTabMutation = useReorderTab(props.subjectId);
    const createTabMutation = useCreateTab(props.subjectId);
    const updateTabMutation = useUpdateTab(props.subjectId);
    const deleteTabMutation = useDeleteTab(props.subjectId);

    useEffect(() => {
        if (data) {
            setLastTabIndex(data.at(-1)['tabIndex']);
            setNumberUntitled(Math.max.apply(Math, data.map(function(result) {
                if (result.label.includes('Untitled')) {
                    return (result.label.match(/\d+/) ? result.label.match(/\d+/)[0] : 1);
                } else {
                    return 0;
                }
            })));
        }
    }, [data]);

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
            id: uuidv4(),
            label: (numberUntitled === 0 ? 'Untitled' : 
                        'Untitled ' + (numberUntitled + 1)),
            tabIndex: lastTabIndex + 1,
            visibility: true,
            subject: '/api/subjects/' + props.subjectId
        };

        createTabMutation.mutate(initialTabData, {
            onSuccess: () => {
                setActiveKey(lastTabIndex + 1);
                setSettingsValidated(false);
                toast.success('Created new tab successfully!');
            },
            onError: () => toast.error('Error has occurred. Failed to create new tab!')
        });
    }

    const updateCurrentTab = () => {
        const currentTab = data[activeKey];

        const newLabel = htmlEntityDecode(settingsTabName.current.value.trim());
        const newExternalUrl = htmlEntityDecode(settingsExternalUrl.current.value.trim());
        const newVisibility = (settingsTabVisibility.current.value === '1');

        const changes = {};

        // Check for any changes in tab data
        if (newLabel && newLabel !== currentTab.label) changes['label'] = newLabel;
        
        if (newExternalUrl && newExternalUrl !== currentTab.externalUrl) 
            changes['externalUrl'] = newExternalUrl;

        if (newVisibility !== currentTab.visibility) changes['visibility'] = newVisibility;

        if (!objectIsEmpty(changes)) {
            // changes have been made to tab data
            setSavingChanges(true);

            updateTabMutation.mutate({
                id: currentTab.id,
                tabIndex: currentTab.tabIndex,
                data: changes,
                optimisticResult: {
                    ...currentTab,
                    'label': changes['label'] ?? currentTab['label'],
                    'externalUrl': changes['externalUrl'] ?? currentTab['externalUrl'],
                    'visibility': changes['visibility'] ?? currentTab['visibility']
                }
            }, {
                onSettled: () => {
                    setShowSettings(false);
                    setSavingChanges(false);
                },
                onSuccess: () => toast.success('Updated tab successfully!'),
                onError: () => toast.error('Error has occurred. Failed to update tab!')
            });
        } else {
            setShowSettings(false);
            setSavingChanges(false);
            setSettingsValidated(false);
        }
    }

    const deleteCurrentTab = () => {
        const currentTab = data[activeKey];
        let newActiveKey = 0;

        if (activeKey === lastTabIndex) {
            // the tab at the end was deleted
            newActiveKey = lastTabIndex - 1;
        } else if (activeKey !== 0) {
            newActiveKey = activeKey - 1;
        }

        setActiveKey(newActiveKey);

        deleteTabMutation.mutate({
            tabId: currentTab.id
        }, {
            onSuccess: () => toast.success('Deleted tab successfully!'),
            onError: () => toast.error('Error has occurred. Failed to delete tab!')
        });

        setDeleteTabClicked(false);
        setDeletingTab(false);
        setShowSettings(false);
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

    const reorderTab = async (sourceIndex, destinationIndex) => {
        // focus the tab container to the destination tab
        // todo: figure out whether we want to move only the current active tab at any given time
        setActiveKey(destinationIndex);

        // perform the reorder mutation in the background
        reorderTabMutation.mutate({
            subjectId: props.subjectId, 
            sourceTabIndex: sourceIndex,
            destinationTabIndex: destinationIndex
        });
    }
    
    const handleOnDragStart = (initial) => {
        if (initial.type === 'tab') {
            setDraggingTab(true);
        }
    }

    const handleOnDragEnd = (result) => {
        // exit if the necessary drag data needed is not available
        if (result.source === undefined || result.source === null || 
            result.destination === undefined || result.destination === null) return;
        if (result.source.index === undefined || result.destination.index === undefined) return;

        if (result.type === 'tab') {
            setDraggingTab(false);

            // exit if element hasn't changed position
            if (result.source.index === result.destination.index) return;

            // perform the reordering
            reorderTab(result.source.index, result.destination.index);
        }
    }

    const guideTabContent = () => {
        if (isLoading) {
            return (<p>Loading tabs...</p>);
        } else if (isError) {
            console.error(error);
            return (<p>Error: Failed to load tabs through API Endpoint!</p>);
        } else {
            const currentTab = data[activeKey];
            
            // convert tabs data to draggable nav links
            const guideTabs = data.map(tab => (
                <DraggableTab key={'tab-' + tab.tabIndex} tabId={tab.id} tabIndex={tab.tabIndex} label={tab.label}
                    active={activeKey === tab.tabIndex} onClick={() => setShowSettings(!showSettings)} /> 
            ));

            // generate tab content
            const tabsContent = data.map(tab => {
                if (currentTab.tabIndex == tab.tabIndex) {
                    return (
                        <Tab.Pane id={'guide-tabs-tabpane-' + tab.tabIndex} className={(activeKey === tab.tabIndex ? 'active': '')}
                            key={'tab-pane-' + tab.tabIndex} eventKey={tab.tabIndex} aria-labelledby={'guide-tabs-tab-' + tab.tabIndex}>
                                <SectionContainer tabId={tab.id} />
                        </Tab.Pane>
                    );
                }
            });

            return (
                <>
                    {/* Guide Tab Container consisting of individual tab elements */}
                    <DragDropContext onDragStart={handleOnDragStart} onDragEnd={handleOnDragEnd}>
                        <Droppable type="tab" style={{ transform: "none" }} droppableId="guide-tabs-container" direction="horizontal">
                            {(provided) => (
                                <div id="guide-tabs-container" {...provided.droppableProps} ref={provided.innerRef}>
                                    <Tab.Container id="guide-tabs" onSelect={onTabSelect} activeKey={activeKey}>
                                        <Nav variant="tabs">
                                            {guideTabs}
                                            {provided.placeholder}
                                            <Nav.Link as="div" key="new-tab" eventKey="new-tab" title="Add New Tab">
                                                <i className="fas fa-plus"></i>
                                            </Nav.Link>
                                        </Nav>
                                    </Tab.Container>
                                </div>
                            )}
                        </Droppable>
                    </DragDropContext>
                    
                    {/* Tab Content */}
                    <Tab.Content className="sp-tab-content">
                        {tabsContent}
                    </Tab.Content>
                    
                    {/* Modal Form for editing tabs */}
                    <EditTabModal currentTab={currentTab} show={showSettings} onHide={() => setShowSettings(false)}
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
        }
    }

    return guideTabContent();
}

export default GuideTabContainer;