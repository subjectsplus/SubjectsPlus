import { useState, useEffect } from 'react';
import produce from 'immer';
import Tab from 'react-bootstrap/Tab';
import Nav from 'react-bootstrap/Nav';
import { DragDropContext, Droppable, DragStart, DropResult } from 'react-beautiful-dnd';
import { v4 as uuidv4 } from 'uuid';
import { toast } from 'react-toastify';
import { hideAllOffcanvas } from '@utility/Utility';
import { useFetchTabs } from '@hooks/useFetchTabs';
import { useCreateTab } from '@hooks/useCreateTab';
import { useReorderTab } from '@hooks/useReorderTab';
import { useGuideTabContainer, GuideTabContainerType } from '@context/GuideTabContainerContext';
import { GuideTab } from './GuideTab';
import { GuideTabContent } from './GuideTabContent';
import { GuideTabType } from '@shared/types/guide_types';
import { CreateTabModal } from './CreateTabModal';
import { GuideTabFormInputs } from '@shared/types/guide_form_types';

export const GuideTabContainer = () => {
    const [lastTabIndex, setLastTabIndex] = useState<number>(0);
    const [draggingTab, setDraggingTab] = useState<boolean>(false);
    const [showNewTabForm, setShowNewTabForm] = useState<boolean>(false);
    const [isCreatingNewTab, setIsCreatingNewTab] = useState<boolean>(false);

    const { subjectId, setCurrentTab, activeKey, setActiveKey } = useGuideTabContainer() as GuideTabContainerType;
    const {isLoading, isError, data, error} = useFetchTabs(subjectId, !draggingTab);
    
    const reorderTabMutation = useReorderTab(subjectId);
    const createTabMutation = useCreateTab(subjectId);

    const initialTabData = {
        id: '',
        label: '',
        tabIndex: 0,
        visibility: true,
        externalUrl: '',
        subject: '/api/subjects/' + subjectId
    };

    useEffect(() => {
        if (data) {
            const lastTab = data.at(-1);
            if (lastTab) {
                setLastTabIndex(lastTab['tabIndex']);
            }

            setCurrentTab(data[activeKey]);
        }
    }, [data, activeKey]);

    const onTabSelect = (eventKey: string|null) => {
        if (eventKey) {
            if (eventKey === 'new-tab') {
                // Create new tab
                setShowNewTabForm(true);
                hideAllOffcanvas();
            } else if (activeKey.toString() !== eventKey) {
                setActiveKey(Number(eventKey));
                hideAllOffcanvas();
            }
        }
    }

    const handleNewTab = (data: GuideTabFormInputs) => {
        setIsCreatingNewTab(true);
        
        const newTabData = produce<GuideTabType>(initialTabData, draftTabData => {
            draftTabData['id'] = uuidv4();
            draftTabData['label'] = data['label'];
            draftTabData['visibility'] = (data['visibility'] === '1');
            draftTabData['tabIndex'] = lastTabIndex + 1;

            if (data['externalUrl'].trim() !== '') {
                draftTabData['externalUrl'] = data['externalUrl'];
            } else {
                draftTabData['externalUrl'] = null;
            }
        });

        createTabMutation.mutate(newTabData, {
            onSuccess: () => {
                setActiveKey(lastTabIndex + 1);
            },
            onError: () => {
                toast.error('Error has occurred. Failed to create new tab!');
            },
            onSettled: () => {
                setShowNewTabForm(false);
                setIsCreatingNewTab(false);
            }
        });
    }

    const reorderTab = async (sourceIndex: number, destinationIndex: number) => {
        // focus the tab container to the destination tab
        // todo: figure out whether we want to move only the current active tab at any given time
        setActiveKey(destinationIndex);

        // perform the reorder mutation in the background
        reorderTabMutation.mutate({
            subjectId: subjectId, 
            sourceTabIndex: sourceIndex,
            destinationTabIndex: destinationIndex
        });
    }
    
    const handleOnDragStart = (initial: DragStart) => {
        if (initial.type === 'tab') {
            setDraggingTab(true);
        }
    }

    const handleOnDragEnd = (result: DropResult) => {
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

    if (isLoading) {
        return (<p>Loading tabs...</p>);
    } else if (isError) {
        console.error(error);
        return (<p>Error: Failed to load tabs through API Endpoint!</p>);
    } else if (data) {
        // convert tabs data to draggable nav links
        const guideTabs = data.map(tab => (
            <GuideTab key={'tab-' + tab.tabIndex} tab={tab} />
        ));

        // generate tab content
        const tabsContent = data.map(tab => {
            return (
                <GuideTabContent key={'tab-content-' + tab.tabIndex} tab={tab} />
            );
        });

        return (
            <>
                {/* Guide Tab Container consisting of individual tab elements */}
                <DragDropContext onDragStart={handleOnDragStart} onDragEnd={handleOnDragEnd}>
                    <Droppable type="tab" droppableId="guide-tabs-container" direction="horizontal">
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

                {/* Create New Tab Modal */}
                <CreateTabModal currentTab={initialTabData} show={showNewTabForm} onHide={() => setShowNewTabForm(false)}
                    onSubmit={handleNewTab} savingChanges={isCreatingNewTab} />
            </>
        );
    } else {
        return (<p>Error: No tabs exist for this guide!</p>);
    }
}