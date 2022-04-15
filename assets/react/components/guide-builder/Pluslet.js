import React, { useState } from 'react';
import { useDeletePluslet } from '#api/guide/PlusletAPI';
import { Draggable } from 'react-beautiful-dnd';
import { CKEditor } from 'ckeditor4-react';

function Pluslet({ plusletId, plusletRow, sectionId }) {

    const [editable, setEditable] = useState(false);
    const deletePlusletMutation = useDeletePluslet(sectionId);

    const deletePluslet = () => {
        const confirmed = confirm('Are you sure you want to delete this pluslet?');
        if (confirmed) {
            deletePlusletMutation.mutate({
                plusletId: plusletId,
            });
        }
    }

    const doubleClicked = () => {
        setEditable(!editable);
    }
    
    return (
        <Draggable type="pluslet" key={plusletId.toString()} draggableId={plusletId.toString()} index={plusletRow}>
            {(provided, snapshot) => {
                return (
                    <div className="pluslet" key={plusletId} ref={provided.innerRef} onDoubleClick={doubleClicked}
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
                        <span className="visually-hidden">{'Pluslet ' + plusletId}</span>
                        {editable ? <CKEditor editorUrl="/build/ckeditor/ckeditor.js" initData={<p>Hello from CKEditor 4!</p>}/> : <p>Double click me to edit!</p>}
                    </div>
                );
            }}
        </Draggable>
    )
}

export default Pluslet;