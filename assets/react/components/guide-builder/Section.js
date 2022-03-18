import React, { useMemo } from 'react';
import Pluslet from './Pluslet';
import { useFetchPluslets } from '#api/guide/PlusletAPI';
import { DragDropContext, Droppable } from 'react-beautiful-dnd';

function Section({ sectionId, layout }) {
    const {isLoading, isError, data, error} = useFetchPluslets(sectionId);

    const sectionContent = useMemo(() => {
        if (isLoading) {
            return (<p>Loading Pluslets...</p>);
        } else if (isError) {
            console.error(error);
            return (<p>Error: Failed to load sections through API Endpoint!</p>);
        } else {
            const pluslets = data.map((pluslet, index) => 
                <Pluslet key={pluslet.plusletId} plusletId={pluslet.plusletId} plusletRow={index} />
            );

            return (
                <Droppable type="pluslet" key={sectionId.toString()} style={{ transform: "none" }} 
                    droppableId={sectionId.toString()} direction="vertical">
                    {(provided) => (
                        <div className="guide-section" data-layout={layout} {...provided.droppableProps} ref={provided.innerRef}
                            style={{
                                border: '2px solid black',
                                height: '500px',
                                width: '750px',
                            }}>
                            {pluslets}
                            {provided.placeholder}
                        </div>
                    )}
                </Droppable>
            );
        }
    }, [data]);

    return sectionContent;
}

export default Section;