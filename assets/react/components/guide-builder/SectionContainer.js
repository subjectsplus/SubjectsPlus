import React, { useState, useMemo } from 'react';
import { useFetchSections, useCreateSection, useReorderSection } from '#api/guide/SectionAPI';
import ErrorBoundary from '#components/shared/ErrorBoundary';
import Section from './Section';
import { DragDropContext, Droppable } from 'react-beautiful-dnd';

function SectionContainer({ tabId }) {
    const {isLoading, isError, data, error} = useFetchSections(tabId);

    const createSectionMutation = useCreateSection(tabId);
    const reorderSectionMutation = useReorderSection(tabId);

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
        console.log(result);
        if (result.type === 'section') {
            // exit if element hasn't changed position
            if (result.source === undefined || result.destination === undefined) return;
            if (result.source.index === undefined || result.destination.index === undefined) return;
            if (result.source.index === result.destination.index) return;

            // perform the reordering
            reorderSection(result.source.index, result.destination.index);
        } else if (result.type === 'pluslet') {
            console.log('Pluslet onDragEnd Handler, result: ', result);
        }
    }

    const addSection = () => {
        console.log('Data: ', data);
        if (data && typeof data === 'array') {
            const initialSectionData = {
                sectionIndex: (data.length > 0 ? data.at(-1).sectionIndex + 1 : 0),
                tab: '/api/tabs/' + tabId
            };

            createSectionMutation.mutate(initialSectionData);
        }
    }

    const containerContent = useMemo(() => {
        if (isLoading) {
            return (<p>Loading Sections...</p>);
        } else if (isError) {
            console.error(error);
            return (<p>Error: Failed to load sections through API Endpoint!</p>);
        } else {
            const guideSections = data.map((section, index) => {
                return (
                    <Section key={section.sectionId || 'section-' + index} sectionId={section.sectionId || 'section-' + index} 
                        layout={section.layout || '4-4-4'} sectionIndex={section.sectionIndex || index} tabId={tabId} />
                );
            });

            return (
                <ErrorBoundary>
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
                </ErrorBoundary>
            );
        }
    }, [data, isError, isLoading]);

    return containerContent;
}

export default SectionContainer;