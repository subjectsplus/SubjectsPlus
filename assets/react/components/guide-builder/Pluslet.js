import React, { useState, useEffect, useRef } from 'react';
import { useUpdatePluslet, useDeletePluslet } from '#api/guide/PlusletAPI';
import CKEditor from '#components/shared/CKEditor';
import DeleteConfirmModal from '#components/shared/DeleteConfirmModal';
import { Draggable } from 'react-beautiful-dnd';
import { useDebouncedCallback } from 'use-debounce';
import DOMPurify from 'dompurify';

function Pluslet({ plusletId, plusletTitle, plusletBody, plusletRow, sectionId, currentDraggingId, currentEditablePluslet, 
    currentEditablePlusletCallBack }) {

    const [editable, setEditable] = useState(currentEditablePluslet === plusletId);
    const [title, setTitle] = useState(plusletTitle);
    const [body, setBody] = useState(plusletBody);
    const [plusletHovered, setPlusletHovered] = useState(false);
    const [deletePlusletClicked, setDeletePlusletClicked] = useState(false);
    
    const plusletDropdownRef = useRef();

    const updatePlusletMutation = useUpdatePluslet(sectionId);
    const deletePlusletMutation = useDeletePluslet(sectionId);

    const isCurrentlyDragging = (('pluslet-' + plusletId) === currentDraggingId);
    const isActiveDropdown = plusletDropdownRef?.current?.classList ? 
        plusletDropdownRef.current.classList.contains('show') : false;

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
        deletePlusletMutation.mutate({
            plusletId: plusletId,
        });
    }

    const handlePlusletDelete = () => {
        if (deletePlusletClicked) {
            deletePluslet();
        } else {
            setDeletePlusletClicked(true);
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
                    body: DOMPurify.sanitize(body, { ADD_TAGS: ["oembed"] })
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
                <div className="sp-pluslet-title">
                    {/* Label is for accessibility purposes, will not be visible */}
                    <label htmlFor="edit-pluslet-title" className="form-label visually-hidden">
                        Enter Pluslet Title
                    </label>
                    <input
                        type="text"
                        id="edit-pluslet-title"
                        placeholder= "Enter Pluslet Title"
                        className="form-control"
                        autoFocus={title.trim() === ''}
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
            return (<p className="sp-pluslet-title">{plusletTitle}</p>);
        }
    }

    const editSaveButton = () => {
        if (editable) {
            return (
                <button onClick={toggleEditable} title="Save pluslet" className="btn btn-muted sp-pluslet-icon-btn">
                    <i className="fas fa-save"></i>
                </button>
            );
        } else {
            return (
                <button onClick={toggleEditable} title="Edit pluslet" className="btn btn-muted sp-pluslet-icon-btn">
                    <i className="fas fa-pen"></i>
                </button>
            );
        }
    }

    const getPlusletClassName = (isDragging) => {
        let className = 'pluslet';

        if (isDragging) {
            className += ' sp-pluslet-dragging';
        } else if (plusletHovered || editable) {
            className += ' sp-pluslet-hover-region';
        }

        return className;
    }

    const getPlusletBodyClassName = (isDragging) => {
        let className = 'sp-pluslet-body';

        if (isDragging) {
            className = 'visually-hidden';
        }

        return className;
    }

    const getVisibility = (className) => {
        if (plusletHovered || editable || isActiveDropdown) {
            return className;
        }
        return className + ' invisible';
    }

    const editor = (isDragging) => {
        if (editable && !isDragging) {
            return (<CKEditor name="pluslet_ckeditor" initData={body} onKey={evt => handleSaveKey(evt.data.domEvent.$)} 
                        onChange={onCKEditorChanged} />);
        } else {
            return (<div className={getPlusletBodyClassName(isDragging)}
                        dangerouslySetInnerHTML={{__html: DOMPurify.sanitize(body, { ADD_TAGS: ["oembed"] })}} />);
        }
    }
    
    return (
        <>
            <Draggable type="pluslet" key={plusletId.toString()} draggableId={'pluslet-' + plusletId} index={plusletRow}>
                {(provided, snapshot) => {
                    return (
                        <div className={getPlusletClassName(snapshot.isDragging || isCurrentlyDragging)} key={plusletId} 
                            ref={provided.innerRef} onDoubleClick={toggleEditable}
                            onKeyDown={handleSaveKey} onMouseEnter={() => setPlusletHovered(true)} 
                            onMouseLeave={() => setPlusletHovered(false)} {...provided.draggableProps}>
                            <span className="visually-hidden">{'Pluslet ' + plusletId}</span>
                            <div className="sp-pluslet-actions-container">
                                {/* Drag Handle */}
                                <div className="drag-handle btn-muted me-1 fs-sm" {...provided.dragHandleProps} title="Move pluslet">
                                    <i className={getVisibility('fas fa-arrows-alt')}></i>
                                </div>

                                {/* Editable Title */}
                                {editableTitle()}

                                <div className={getVisibility('text-end')}>
                                    {/* Edit/Save Button */}
                                    {editSaveButton()}

                                    {/* Dropdown */}
                                    <div className="dropdown basic-dropdown d-inline-block ms-1">
                                        <button className="btn btn-muted sp-pluslet-icon-btn dropdown-toggle" id="sectionMenuOptions" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i className="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul ref={plusletDropdownRef} className="dropdown-menu dropdown-arrow dropdown-menu-end fs-xs" aria-labelledby="plusletMenuOptions">
                                            {/* Make Favorite */}
                                            <li><a className="dropdown-item">Make Favorite</a></li>
                                            <li><hr className="dropdown-divider" /></li>

                                            {/* Delete Pluslet */}
                                            <li><a className="dropdown-item" onClick={handlePlusletDelete}><i
                                                className="fas fa-trash"></i> Delete Pluslet</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            {editor(snapshot.isDragging || isCurrentlyDragging)}
                        </div>
                    );
                }}
            </Draggable>
            <DeleteConfirmModal show={deletePlusletClicked} resourceName="Pluslet" onHide={() => setDeletePlusletClicked(false)}
                confirmOnClick={handlePlusletDelete} />
        </>
    )
}

export default Pluslet;