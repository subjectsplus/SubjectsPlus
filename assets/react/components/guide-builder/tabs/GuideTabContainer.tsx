import { useState, useEffect } from 'react';
import { hideAllOffcanvas } from '@utility/Utility';
import { useFetchTabs } from '@hooks/useFetchTabs';
import { useCreateTab } from '@hooks/useCreateTab';
import { useReorderTab } from '@hooks/useReorderTab';
import Tab from 'react-bootstrap/Tab';
import Nav from 'react-bootstrap/Nav';
import { DragDropContext, Droppable, DragStart, DropResult } from 'react-beautiful-dnd';
import { v4 as uuidv4 } from 'uuid';
import { toast } from 'react-toastify';
import { GuideTabContainerProvider } from '@context/GuideTabContainerContext';
import { GuideTab } from './GuideTab';
import { GuideTabContent } from './GuideTabContent';
import { GuideTabType } from '@shared/types/guide_types';

type GuideTabContainerProps = {
    subjectId: number
}

export const GuideTabContainer = ({ subjectId }: GuideTabContainerProps) => {
    const [lastTabIndex, setLastTabIndex] = useState(0);
    const [activeKey, setActiveKey] = useState(0);
    const [draggingTab, setDraggingTab] = useState(false);

    const {isLoading, isError, data, error} = useFetchTabs(subjectId, !draggingTab);
    
    const reorderTabMutation = useReorderTab(subjectId);
    const createTabMutation = useCreateTab(subjectId);

    useEffect(() => {
        if (data) {
            const lastTab = data.at(-1);
            if (lastTab) {
                setLastTabIndex(lastTab['tabIndex']);
            }
        }
    }, [data]);

    const onTabSelect = (eventKey: string|null) => {
        if (eventKey === 'new-tab') {
            // Create new tab
            newTab();
            hideAllOffcanvas();
        } else if (activeKey.toString() !== eventKey) {
            setActiveKey(Number(eventKey));
            hideAllOffcanvas();
        }
    }

    const newTab = () => {
        const initialTabData = {
            id: uuidv4(),
            label: 'Untitled',
            tabIndex: lastTabIndex + 1,
            visibility: true,
            subject: '/api/subjects/' + subjectId
        };

        createTabMutation.mutate(initialTabData, {
            onSuccess: () => {
                setActiveKey(lastTabIndex + 1);
            },
            onError: () => {
                toast.error('Error has occurred. Failed to create new tab!');
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
        const currentTab = data[activeKey] as GuideTabType;
        
        // convert tabs data to draggable nav links
        const guideTabs = data.map((tab: GuideTabType) => (
            <GuideTab key={'tab-' + tab.tabIndex} tab={tab} />
        ));

        // generate tab content
        const tabsContent = data.map((tab: GuideTabType) => {
            return (
                <GuideTabContent key={'tab-content-' + tab.tabIndex} tab={tab} />
            );
        });

        return (
            <GuideTabContainerProvider subjectId={subjectId} currentTab={currentTab} activeKey={activeKey} setActiveKey={setActiveKey}>
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
            </GuideTabContainerProvider>
        );
    } else {
        return (<p>Error: No tabs exist for this guide!</p>);
    }
}