import React, { useMemo } from 'react';
import { useFetchSections } from '#api/guide/SectionAPI';
import Section from './Section';
import { DragDropContext, Droppable } from 'react-beautiful-dnd';

function SectionContainer({ tabId }) {
    const {isLoading, isError, data, error} = useFetchSections(tabId);

    const handleOnDragEnd = (result) => {
        console.log(result);
        if (result.type === 'section') {
            console.log('Section onDragEnd Handler');
        } else if (result.type === 'pluslet') {
            console.log('Pluslet onDragEnd Handler');
        }
    }

    const containerContent = useMemo(() => {
        if (isError) {
            console.error(error);
            return (<p>Error: Failed to load sections through API Endpoint!</p>);
        } else if (isLoading) {
            return (<p>Loading Sections...</p>);
        } else {
            const guideSections = data.map(section => 
                <Section key={section.sectionId} sectionId={section.sectionId} layout={section.layout} />);
            return (
                <DragDropContext onDragEnd={handleOnDragEnd}>
                    <Droppable type="section" style={{ transform: "none" }} droppableId="guide-section-container" direction="vertical">
                        {(provided) => (
                            <div className="section-container" {...provided.droppableProps} ref={provided.innerRef}>
                                {guideSections}
                                {provided.placeholder}
                            </div> 
                        )}
                    </Droppable>
                </DragDropContext>
            );
        }
    }, [data]);

    return containerContent;
}

export default SectionContainer;