import { useState } from 'react';
import { DragDropContext, Droppable, DropResult, BeforeCapture } from 'react-beautiful-dnd';
import { v4 as uuidv4, validate as uuidValidate } from 'uuid';
import { useReorderSection } from '@hooks/useReorderSection';
import { useCreateSection } from '@hooks/useCreateSection';
import { useFetchSections } from '@hooks/useFetchSections';
import { useReorderPluslet } from '@api/guide/PlusletAPI';
import { GuideSectionType } from '@shared/types/guide_types';
import { SectionContainerProvider } from '@context/SectionContainerContext';
import { Section } from './Section';

type SectionContainerProps = {
    tabUUID: string
};

export const SectionContainer = ({ tabUUID }: SectionContainerProps) => {
    const [draggingId, setDraggingId] = useState<string|null>(null);

    const {isLoading, isError, data, error} = useFetchSections(tabUUID);

    const createSectionMutation = useCreateSection(tabUUID);
    const reorderSectionMutation = useReorderSection(tabUUID);
    const reorderPlusletMutation = useReorderPluslet();
    
    const reorderSection = (sourceIndex: number, destinationIndex: number) => {
        reorderSectionMutation.mutate({
            tabUUID: tabUUID,
            sourceSectionIndex: sourceIndex,
            destinationSectionIndex: destinationIndex
        })
    }

    const handleOnDragEnd = (result: DropResult) => {
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

    const handleOnBeforeCapture = (beforeCapture: BeforeCapture) => {
        setDraggingId(beforeCapture.draggableId);
    }

    const addSection = () => {
        if (Array.isArray(data)) {
            const initialSectionData = {
                id: uuidv4(),
                sectionIndex: (data.length > 0 ? data.at(-1).sectionIndex + 1 : 0),
                layout: '4-4-4',
                tab: '/api/tabs/' + tabUUID
            };

            createSectionMutation.mutate(initialSectionData);
        }
    }

    if (isLoading) {
        return (<p>Loading Sections...</p>);
    } else if (isError) {
        console.error(error);
        return (<p>Error: Failed to load sections through API Endpoint!</p>);
    } else if (data) {
        const guideSections = data.map((section: GuideSectionType, index: number) => {
            return (
                <Section key={section.id} sectionUUID={section.id}
                    layout={section.layout} sectionIndex={section.sectionIndex} tabUUID={tabUUID} />
            );
        });

        return (
            <SectionContainerProvider currentDraggingId={draggingId}>
                <DragDropContext onDragEnd={handleOnDragEnd} onBeforeCapture={handleOnBeforeCapture}>
                    <Droppable type="section" droppableId="guide-section-container" direction="vertical">
                        {(provided, snapshot) => (
                            <div className="section-container" {...provided.droppableProps} ref={provided.innerRef}>
                                {guideSections}
                                {provided.placeholder}
                            </div> 
                        )}
                    </Droppable>
                </DragDropContext>
                <div className="text-center mt-1 add-section-container">
                    <button id="add-section" className="btn btn-muted p-1" onClick={addSection}>
                        <span className="section-icon d-block"></span>
                        <span className="fs-xs">Add Section</span>
                    </button>
                </div>
            </SectionContainerProvider>
        );
    } else {
        return (<p>Error: No sections exist for this guide!</p>);
    }
}