import React, { useState, useEffect, useRef } from 'react';
import { useUpdatePluslet, useDeletePluslet } from '#api/guide/PlusletAPI';
import CKEditor from '#components/shared/CKEditor';
import DeleteConfirmModal from '#components/shared/DeleteConfirmModal';
import { Draggable } from 'react-beautiful-dnd';
import { useDebouncedCallback } from 'use-debounce';
import DOMPurify from 'dompurify';

function Pluslet({ plusletId, plusletTitle, plusletBody, plusletRow, sectionId, currentEditablePluslet, 
    currentEditablePlusletCallBack }) {

    const [editable, setEditable] = useState(false);
    const [title, setTitle] = useState(plusletTitle);
    const [body, setBody] = useState(plusletBody);
    const [plusletHovered, setPlusletHovered] = useState(false);
    const [deletePlusletClicked, setDeletePlusletClicked] = useState(false);

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

    const getPlusletClassName = () => {
        let className = 'pluslet';

        if (plusletHovered || editable) {
            className += ' sp-pluslet-hover-region';
        }

        return className;
    }

    const getVisibility = (className) => {
        if (plusletHovered || editable) {
            return className;
        }
        return className + ' invisible';
    }

    const editor = () => {
        if (editable) {
            return (<CKEditor name="pluslet_ckeditor" initData={body} onKey={evt => handleSaveKey(evt.data.domEvent.$)} onChange={onCKEditorChanged} />);
        } else {
            // TODO: Fix issue where Media-Embed turns every link into an iframe (caused by autoembed plugin)
            return (<div className="sp-pluslet-body" dangerouslySetInnerHTML={{__html: DOMPurify.sanitize(body, { ADD_TAGS: ["oembed"] })}} />);
        }
    }
    
    return (
        <>
            <Draggable type="pluslet" key={plusletId.toString()} draggableId={plusletId.toString()} index={plusletRow}>
                {(provided, snapshot) => {
                    return (
                        <div className={getPlusletClassName()} key={plusletId} ref={provided.innerRef} onDoubleClick={toggleEditable}
                            onKeyDown={handleSaveKey} onMouseEnter={() => setPlusletHovered(true)} onMouseLeave={() => setPlusletHovered(false)}
                            {...provided.draggableProps}
                            style={{
                                ...provided.draggableProps.style,
                                height: 'auto'
                            }}>
                            {/* TODO: Add styles when dragging pluslet, reduce height and only show title bar area
                                    background: isDragging ? 'rgba(63,194,198, 15%)' : 'transparent'
                            */}

                            <span className="visually-hidden">{'Pluslet ' + plusletId}</span>
                            <div className="sp-pluslet-actions-container">
                                <div className="drag-handle btn-muted me-1 fs-sm" {...provided.dragHandleProps} title="Move pluslet">
                                    <i className={getVisibility('fas fa-arrows-alt')}></i>
                                </div>
                                {editableTitle()}
                                <div className={getVisibility('text-end')}>
                                    {editSaveButton()}

                                    <div className="dropdown basic-dropdown d-inline-block ms-1">
                                        <button className="btn btn-muted sp-pluslet-icon-btn dropdown-toggle" id="sectionMenuOptions" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i className="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul className="dropdown-menu dropdown-arrow dropdown-menu-end fs-xs" aria-labelledby="plusletMenuOptions">
                                            <li><a className="dropdown-item">Make Favorite</a></li>
                                            <li><hr className="dropdown-divider" /></li>
                                            <li><a className="dropdown-item" onClick={handlePlusletDelete}><i
                                                className="fas fa-trash"></i> Delete Pluslet</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            {editor()}
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