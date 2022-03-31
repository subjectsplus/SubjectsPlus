import React from 'react';
import { Draggable } from 'react-beautiful-dnd';

function Pluslet({ plusletId, plusletRow }) {
    return (
        <Draggable type="pluslet" key={plusletId.toString()} draggableId={plusletId.toString()} index={plusletRow}>
            {(provided, snapshot) => {
                return (
                    <div className="pluslet" key={plusletId} ref={provided.innerRef} 
                        {...provided.draggableProps} {...provided.dragHandleProps}
                        style={{
                            ...provided.draggableProps.style,
                            backgroundColor: '#d5d5d5',
                            height: '200px',
                        }}>
                            {'Pluslet ' + plusletId}
                    </div>
                );
            }}
        </Draggable>
    )
}

export default Pluslet;