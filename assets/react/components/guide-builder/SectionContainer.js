import React from 'react';
import { useFetchSections, useCreateSection, useReorderSection } from '#api/guide/SectionAPI';
import { useReorderPluslet } from '#api/guide/PlusletAPI';
import Section from './Section';
import { DragDropContext, Droppable } from 'react-beautiful-dnd';

function SectionContainer({ tabId }) {
    const {isLoading, isError, data, error} = useFetchSections(tabId);

    const createSectionMutation = useCreateSection(tabId);
    const reorderSectionMutation = useReorderSection(tabId);
    const reorderPlusletMutation = useReorderPluslet();

    const padding = 8;

    const getSectionContainerStyle = (isDraggingOver) => ({
        background: isDraggingOver ? 'lightblue' : 'transparent',
        padding: padding,
        display: 'block',
        width: '400px'
    });

    const reorderSection = (sourceIndex, destinationIndex) => {
        reorderSectionMutation.mutate({
            tabId: tabId,
            sourceSectionIndex: sourceIndex,
            destinationSectionIndex: destinationIndex
        })
    }

    const handleOnDragEnd = (result) => {
        if (result.source === undefined || result.source === null || 
            result.destination === undefined || result.destination === null) return;
        if (result.source.index === undefined || result.destination.index === undefined) return;

        if (result.type === 'section') {
            // exit if element hasn't changed position
            if (result.source.index === result.destination.index) return;

            reorderSection(result.source.index, result.destination.index);
        } else if (result.type === 'pluslet') {
            console.log('Source: ', result.source);
            console.log('Destination: ', result.destination);
            console.log('Source Droppable Id: ', result.source.droppableId);
            console.log('Destination Droppable Id: ', result.destination.droppableId);
            // Source details
            const sourceId = result.source.droppableId.split('-');
            const sourceSection = Number(sourceId[1]);
            const sourceColumn = Number(sourceId[3]);
            const sourceIndex = result.source.index;

            // Destination details
            const destinationId = result.destination.droppableId.split('-');
            const destinationSection = Number(destinationId[1]);
            const destinationColumn = Number(destinationId[3]);
            const destinationIndex = result.destination.index;

            if (sourceSection === destinationSection && sourceColumn === destinationColumn
                    && sourceIndex === destinationIndex) return;
            
            // TODO: Handle case where plusletRow is incorrect and not ordered
            // TODO: Add a debounce to prevent spam requests
            reorderPlusletMutation.mutate({
                sourceSection: sourceSection,
                sourceColumn: sourceColumn,
                sourceIndex: sourceIndex,
                destinationSection: destinationSection,
                destinationColumn: destinationColumn,
                destinationIndex: destinationIndex
            });
        }
    }

    const addSection = () => {
        if (Array.isArray(data)) {
            const initialSectionData = {
                sectionIndex: (data.length > 0 ? data.at(-1).sectionIndex + 1 : 0),
                layout: '4-4-4',
                tab: '/api/tabs/' + tabId
            };

            createSectionMutation.mutate(initialSectionData);
        }
    }

    const containerContent = () => {
        if (isLoading) {
            return (<p>Loading Sections...</p>);
        } else if (isError) {
            console.error(error);
            return (<p>Error: Failed to load sections through API Endpoint!</p>);
        } else {
            const guideSections = data.map((section, index) => {
                return (
                    <Section key={section.sectionId} sectionId={section.sectionId} 
                        layout={section.layout} sectionIndex={section.sectionIndex} tabId={tabId} />
                );
            });

            return (
                <>
                    <button id="add-section" onClick={addSection}>
                        <i className="fas fa-plus"></i>
                    </button>
                    <DragDropContext onDragEnd={handleOnDragEnd}>
                        <Droppable type="section" style={{ transform: "none" }} droppableId="guide-section-container" direction="vertical">
                            {(provided, snapshot) => (
                                <div className="section-container" {...provided.droppableProps} ref={provided.innerRef}
                                style={getSectionContainerStyle(snapshot.isDraggingOver)}>
                                    {guideSections}
                                    {provided.placeholder}
                                </div> 
                            )}
                        </Droppable>
                    </DragDropContext>
                </>
            );
        }
    };

    return containerContent();
}

export default SectionContainer;