import React, { useState, useEffect, useRef } from 'react';
import { useUpdatePluslet, useDeletePluslet } from '#api/guide/PlusletAPI';
import CKEditor from '#components/shared/CKEditor';
import { Draggable } from 'react-beautiful-dnd';
import { useDebouncedCallback } from 'use-debounce';

function Pluslet({ plusletId, plusletTitle, plusletRow, sectionId, currentEditablePluslet, currentEditablePlusletCallBack }) {

    const [editable, setEditable] = useState(false);
    const [title, setTitle] = useState(plusletTitle);

    const updatePlusletMutation = useUpdatePluslet(sectionId);
    const deletePlusletMutation = useDeletePluslet(sectionId);

    useEffect(() => {
        if (editable && currentEditablePluslet !== plusletId) {
            // if another pluslet is being edited, save current content
            // and set this pluslet in view mode
            updatePlusletTitle();
            setEditable(false);
        }
    }, [currentEditablePluslet]);

    const deletePluslet = () => {
        const confirmed = confirm('Are you sure you want to delete this pluslet?');
        if (confirmed) {
            deletePlusletMutation.mutate({
                plusletId: plusletId,
            });
        }
    }

    const doubleClicked = () => {
        if (currentEditablePluslet !== plusletId) {
            setEditable(true);
            currentEditablePlusletCallBack(plusletId);
        } else {
            setEditable(false);
            currentEditablePlusletCallBack('');
        }
    }

    const updatePlusletTitle = () => {
        if (title !== plusletTitle) {
            updatePlusletMutation.mutate({
                plusletId: plusletId,
                data: {
                    title: title
                }
            });
        }
    }

    const editableTitle = (
        <div className="mb-2">
            {/* Label is for accessibility purposes, will not be visible */}
            <label htmlFor="edit-pluslet-title" className="form-label">
                <span className="visually-hidden">Enter Pluslet Title</span>
            </label>
            <input
                type="text"
                id="edit-pluslet-title"
                placeholder= "Enter Pluslet Title"
                value={title}
                autoComplete="off"
                onChange={evt => setTitle(evt.target.value)}
                onKeyDown={evt => {
                    if (evt.code === 'Enter') {
                        evt.preventDefault();
                        updatePlusletTitle();
                        setEditable(false);
                    }
                }}
            />
        </div>
    );

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
                        {editable ? editableTitle : <p>{plusletTitle}</p> }
                        {editable ? <CKEditor initData={<p>Hello from CKEditor 4!</p>} /> 
                                        : <p>Double click me to edit!</p>}
                    </div>
                );
            }}
        </Draggable>
    )
}

export default Pluslet;