import React from 'react';
import { Draggable } from 'react-beautiful-dnd';

function Pluslet(props) {
    return (
        <Draggable type="pluslet" key={props.plusletId.toString()} draggableId={props.plusletId.toString()} index={props.plusletRow}>
            {(provided, snapshot) => {
                return (
                    <div className="pluslet" key={props.plusletId} ref={provided.innerRef} {...provided.draggableProps} 
                        {...provided.dragHandleProps}
                        style={{
                            ...provided.draggableProps.style,
                            border: '2px dashed blue',
                            height: '200px',
                            width: '150px',
                        }}>
                            {'Pluslet ' + props.plusletId}
                    </div>
                );
            }}
        </Draggable>
    )
}

export default Pluslet;