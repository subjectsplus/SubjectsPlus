import React, { useState, useEffect, useRef } from 'react';
import { useUpdatePluslet, useDeletePluslet } from '#api/guide/PlusletAPI';
import CKEditor from '#components/shared/CKEditor';
import { Draggable } from 'react-beautiful-dnd';
import { useDebouncedCallback } from 'use-debounce';
import DOMPurify from 'dompurify';

function Pluslet({ plusletId, plusletTitle, plusletBody, plusletRow, sectionId, currentEditablePluslet, 
    currentEditablePlusletCallBack }) {

    const [editable, setEditable] = useState(false);
    const [title, setTitle] = useState(plusletTitle);
    const [body, setBody] = useState(plusletBody);

    const updatePlusletMutation = useUpdatePluslet(sectionId);
    const deletePlusletMutation = useDeletePluslet(sectionId);

    useEffect(() => {
        if (editable && currentEditablePluslet !== plusletId) {
            // if another pluslet is being edited, save current content
            // and set this pluslet in view mode
            updatePlusletTitle();
            updatePlusletBody();
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

    const toggleEditable = () => {
        if (currentEditablePluslet !== plusletId) {
            setEditable(true);
            currentEditablePlusletCallBack(plusletId);
        } else {
            setEditable(false);
            currentEditablePlusletCallBack('');
            updatePlusletTitle();
            updatePlusletBody();
        }
    }

    const handleSaveKey = (event) => {
        if (currentEditablePluslet === plusletId) {
            if ((event.ctrlKey || event.metaKey) && event.key === 's') {
                event.preventDefault();
                currentEditablePlusletCallBack('');
                updatePlusletTitle();
                updatePlusletBody();
            }
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

    const updatePlusletBody = () => {
        if (body !== plusletBody) {
            updatePlusletMutation.mutate({
                plusletId: plusletId,
                data: {
                    body: DOMPurify.sanitize(body)
                }
            });
        }
    }

    const debouncedUpdatePlusletBody = useDebouncedCallback(updatePlusletBody, 1000);

    const onCKEditorChanged = evt => {
        if (evt.editor) {
            setBody(evt.editor.getData())
            debouncedUpdatePlusletBody();
        }
    }
    
    const editableTitle = () => {
        if (editable) {
            return (
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
        } else {
            return (<p>{plusletTitle}</p>);
        }
    }

    const editSaveButton = () => {
        if (editable) {
            return (
                <button onClick={toggleEditable} title="Save pluslet">
                    <i className="fas fa-save"></i>
                </button>
            );
        } else {
            return (
                <button onClick={toggleEditable} title="Edit pluslet">
                    <i className="fas fa-pen"></i>
                </button>
            );
        }
    }

    const editor = () => {
        if (editable) {
            return (<CKEditor name="pluslet_ckeditor" initData={body} onKey={evt => handleSaveKey(evt.data.domEvent.$)} onChange={onCKEditorChanged} />);
        } else {
            return (<div dangerouslySetInnerHTML={{__html: DOMPurify.sanitize(body)}} />);
        }
    }
    
    return (
        <Draggable type="pluslet" key={plusletId.toString()} draggableId={plusletId.toString()} index={plusletRow}>
            {(provided, snapshot) => {
                return (
                    <div className="pluslet" key={plusletId} ref={provided.innerRef} onDoubleClick={toggleEditable}
                        onKeyDown={handleSaveKey}
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
                            {editSaveButton()}
                        </div>
                        <span className="visually-hidden">{'Pluslet ' + plusletId}</span>
                        {editableTitle()}
                        {editor()}
                    </div>
                );
            }}
        </Draggable>
    )
}

export default Pluslet;