import React, { useState } from 'react';
import { useFetchSections, useCreateSection, useReorderSection } from '#api/guide/SectionAPI';
import { useReorderPluslet } from '#api/guide/PlusletAPI';
import Section from './Section';
import { DragDropContext, Droppable } from 'react-beautiful-dnd';
import { v4 as uuidv4, validate as uuidValidate } from 'uuid';

function SectionContainer({ tabId }) {
    const [currentEditablePluslet, setCurrentEditablePluslet] = useState('');
    const [draggingId, setDraggingId] = useState(null);

    const {isLoading, isError, data, error} = useFetchSections(tabId);

    const createSectionMutation = useCreateSection(tabId);
    const reorderSectionMutation = useReorderSection(tabId);
    const reorderPlusletMutation = useReorderPluslet();
    
    const reorderSection = (sourceIndex, destinationIndex) => {
        reorderSectionMutation.mutate({
            tabId: tabId,
            sourceSectionIndex: sourceIndex,
            destinationSectionIndex: destinationIndex
        })
    }

    const handleOnDragEnd = (result) => {
        // reset dragging id
        setDraggingId(null);

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
            const sourceId = result.source.droppableId.split('|');
            const sourceSection = sourceId[1];
            const sourceColumn = Number(sourceId[3]);
            const sourceIndex = result.source.index;

            // Destination details
            const destinationId = result.destination.droppableId.split('|');
            const destinationSection = destinationId[1];
            const destinationColumn = Number(destinationId[3]);
            const destinationIndex = result.destination.index;

            // Error if source/destination section id are not uuid's
            if (!uuidValidate(sourceSection)) {
                throw new Error("Source section id must be a valid uuid.");
            } else if (!uuidValidate(destinationSection)) {
                throw new Error("Destination section id must be a valid uuid.");
            }

            // If no positioning has changed, do not mutate
            if (sourceSection === destinationSection && sourceColumn === destinationColumn
                    && sourceIndex === destinationIndex) return;
            
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

    const handleOnBeforeCapture = (beforeCapture) => {
        setDraggingId(beforeCapture.draggableId);
    }

    const addSection = () => {
        if (Array.isArray(data)) {
            const initialSectionData = {
                id: uuidv4(),
                sectionIndex: (data.length > 0 ? data.at(-1).sectionIndex + 1 : 0),
                layout: '4-4-4',
                tab: '/api/tabs/' + tabId
            };

            createSectionMutation.mutate(initialSectionData);
        }
    }

    const [addSectionStyle, setStyle] = useState({visibility: 'hidden'});

    const containerContent = () => {
        if (isLoading) {
            return (<p>Loading Sections...</p>);
        } else if (isError) {
            console.error(error);
            return (<p>Error: Failed to load sections through API Endpoint!</p>);
        } else {
            const guideSections = data.map((section, index) => {
                return (
                    <Section key={section.id} sectionId={section.id} 
                        layout={section.layout} sectionIndex={section.sectionIndex} tabId={tabId} 
                        currentEditablePluslet={currentEditablePluslet}
                        isCurrentlyDragging={'section-' + section.id === draggingId}
                        currentEditablePlusletCallBack={setCurrentEditablePluslet} />
                );
            });

            return (
                <>
                    <DragDropContext onDragEnd={handleOnDragEnd}>
                        <Droppable type="section" style={{ transform: "none" }} droppableId="guide-section-container" direction="vertical">
                            {(provided, snapshot) => (
                                <div className="section-container" {...provided.droppableProps} ref={provided.innerRef}>
                                    {guideSections}
                                    {provided.placeholder}
                                </div> 
                            )}
                        </Droppable>
                    </DragDropContext>
                    <div className="text-center mt-1">
                        <button
                            id="add-section"
                            className="btn btn-muted p-1"
                            onClick={addSection}
                            onMouseEnter={e => {
                                setStyle({visibility: 'visible'});
                            }}
                            onMouseLeave={e => {
                                setStyle({visibility: 'hidden'})
                            }}
                        >
                            <i className="fas fa-plus-circle d-block"></i>
                            <span className="fs-xs" style={addSectionStyle}>Add Section</span>
                        </button>
                    </div>
                </>
            );
        }
    };

    return containerContent();
}

export default SectionContainer;