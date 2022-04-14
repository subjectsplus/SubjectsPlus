import React from 'react';
import { useDeletePluslet } from '#api/guide/PlusletAPI';
import { Draggable } from 'react-beautiful-dnd';

function Pluslet({ plusletId, plusletRow, sectionId }) {

    const deletePlusletMutation = useDeletePluslet(sectionId);

    const deletePluslet = () => {
        const confirmed = confirm('Are you sure you want to delete this pluslet?');
        if (confirmed) {
            deletePlusletMutation.mutate({
                plusletId: plusletId,
            });
        }
    }

    return (
        <Draggable type="pluslet" key={plusletId.toString()} draggableId={plusletId.toString()} index={plusletRow}>
            {(provided, snapshot) => {
                return (
                    <div className="pluslet" key={plusletId} ref={provided.innerRef} 
                        {...provided.draggableProps}
                        style={{
                            ...provided.draggableProps.style,
                            backgroundColor: '#d5d5d5',
                            height: '200px',
                        }}>
                            <div>
                                <div className="drag-handle" {...provided.dragHandleProps} title="Move pluslet">
                                    <i className="fas fa-arrows-alt"></i>
                                </div>
                                <button onClick={deletePluslet} title="Delete pluslet">
                                    <i className="fas fa-trash"></i>
                                </button>
                            </div>
                            {'Pluslet ' + plusletId}
                    </div>
                );
            }}
        </Draggable>
    )
}

export default Pluslet;