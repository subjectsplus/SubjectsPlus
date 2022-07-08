import { useState, useEffect, useRef, useMemo } from 'react';
import DOMPurify from 'dompurify';
import { Draggable } from 'react-beautiful-dnd';
import { useDebouncedCallback } from 'use-debounce';
import { useUpdatePluslet, useDeletePluslet } from '@api/guide/PlusletAPI';
import CKEditor from '@components/shared/CKEditor';
import { DeleteConfirmModal } from '@components/shared/DeleteConfirmModal';
import ActionsContainer from './pluslets/shared/ActionsContainer';
import { hideAllOffcanvas } from '@utility/Utility';
import { useDraggableInPortal } from '@hooks/useDraggableInPortal';
import { useSectionContainer, SectionContainerType } from '@context/SectionContainerContext';

type PlusletProps = {
    plusletUUID: string,
    plusletTitle: string,
    plusletBody: string,
    plusletRow: number,
    sectionUUID: string
}

export const Pluslet = ({ plusletUUID, plusletTitle, plusletBody, plusletRow, sectionUUID}: PlusletProps) => {
    const { currentDraggingId, currentEditablePluslet, setCurrentEditablePluslet } = useSectionContainer() as SectionContainerType;
    
    const [editable, setEditable] = useState(currentEditablePluslet === plusletUUID);
    const [title, setTitle] = useState(plusletTitle);
    const [body, setBody] = useState(plusletBody);
    const [plusletHovered, setPlusletHovered] = useState(false);
    const [deletePlusletClicked, setDeletePlusletClicked] = useState(false);
    
    const plusletDropdownRef = useRef<HTMLUListElement>(null);
    
    const renderDraggable = useDraggableInPortal();

    const updatePlusletMutation = useUpdatePluslet(sectionUUID);
    const deletePlusletMutation = useDeletePluslet(sectionUUID);

    const isCurrentlyDragging = (('pluslet-' + plusletUUID) === currentDraggingId);
    const isBeingDraggedOver = (!isCurrentlyDragging && currentDraggingId && 
        currentDraggingId.substring(0, 8) === 'pluslet-');
    const isActiveDropdown = plusletDropdownRef?.current?.classList ? 
        plusletDropdownRef.current.classList.contains('show') : false;

    useEffect(() => {
        if (editable && currentEditablePluslet !== plusletUUID) {
            // if another pluslet is being edited, save current content
            // and set this pluslet in view mode
            updatePlusletTitle();
            updatePlusletBody();
            setEditable(false);
        }
    }, [currentEditablePluslet]);
    
    const savePluslet = () => {
        setCurrentEditablePluslet('');
        updatePlusletTitle();
        updatePlusletBody();
        hideAllOffcanvas();
    }

    const deletePluslet = () => {
        deletePlusletMutation.mutate({
            plusletUUID: plusletUUID,
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
        if (currentEditablePluslet !== plusletUUID) {
            setEditable(true);
            setCurrentEditablePluslet(plusletUUID);
        } else {
            setEditable(false);
            savePluslet();
        }
    }

    const handleSaveKey = (event) => {
        if (currentEditablePluslet === plusletUUID) {
            if ((event.ctrlKey || event.metaKey) && event.key === 's') {
                event.preventDefault();
                savePluslet();
            }
        }
    }

    const updatePlusletTitle = () => {
        if (title !== plusletTitle) {
            updatePlusletMutation.mutate({
                plusletUUID: plusletUUID,
                data: {
                    title: title
                }
            });
        }
    }

    const updatePlusletBody = () => {
        if (body !== plusletBody) {
            updatePlusletMutation.mutate({
                plusletUUID: plusletUUID,
                data: {
                    body: DOMPurify.sanitize(body, { ADD_TAGS: ["iframe"] })
                }
            });
        }
    }

    const debouncedUpdatePlusletBody = useDebouncedCallback(updatePlusletBody, 1000);

    const handleCKEditorInstanceReady = evt => {
        evt.editor.setData(body);
    }

    const onCKEditorChanged = evt => {
        if (evt.editor) {
            setBody(evt.editor.getData())
            debouncedUpdatePlusletBody();
        }
    }

    const getPlusletClassName = (isDragging) => {
        let className = 'pluslet';

        if (isDragging) {
            className += ' sp-pluslet-dragging';
        } else if (isBeingDraggedOver) {
            className += ' sp-pluslet-dragging-over'
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

    const Editor = (isDragging) => {
        if (editable && !isDragging) {
            return (<CKEditor name="pluslet_ckeditor" onKey={evt => handleSaveKey(evt.data.domEvent.$)} 
                        onInstanceReady={handleCKEditorInstanceReady} onChange={onCKEditorChanged} />);
        } else {
            return (<div className={getPlusletBodyClassName(isDragging)}
                        dangerouslySetInnerHTML={{__html: DOMPurify.sanitize(body, { ADD_TAGS: ["iframe"] })}} />);
        }
    }
    
    const PlusletWindow = (provided, snapshot) => (
        <div className={getPlusletClassName(snapshot?.isDragging || isCurrentlyDragging)} key={plusletUUID} 
            ref={provided?.innerRef} onDoubleClick={() => !editable && toggleEditable()}
            onKeyDown={handleSaveKey} onMouseEnter={() => setPlusletHovered(true)}
            onMouseLeave={() => setPlusletHovered(false)} {...provided?.draggableProps}>
            <span className="visually-hidden">{'Pluslet ' + plusletUUID}</span>

            {/* Old Drag Handle */}
            {/* <div className="drag-handle btn-muted me-1 fs-sm" {...provided.dragHandleProps} title="Move pluslet">
                <i className={getVisibility('fas fa-arrows-alt')}></i>
            </div> */}

            {/* Actions Container */}
            <ActionsContainer isEditMode={editable} editSaveOnClick={toggleEditable} 
                visible={plusletHovered || editable || isActiveDropdown} plusletDropdownRef={plusletDropdownRef} 
                deletePlusletOnClick={handlePlusletDelete}
                dragHandleProps={provided?.dragHandleProps}
                title={title}
                onEditableTitleChange={evt => setTitle(evt.target.value)}
                onEditableTitleKeyDown={evt => {
                    if (evt.code === 'Enter') {
                        evt.preventDefault();
                        updatePlusletTitle();
                        setEditable(false);
                    }
                }}
            />
                
            {/* Editor */}
            {Editor(snapshot?.isDragging || isCurrentlyDragging || isBeingDraggedOver)}
        </div>
    );
    
    const DraggablePluslet = () => (
        <Draggable type="pluslet" key={plusletUUID.toString()} draggableId={'pluslet-' + plusletUUID} index={plusletRow}>
            {renderDraggable((provided, snapshot) => PlusletWindow(provided, snapshot))}
        </Draggable>
    )

    return (
        <>
            {editable ? PlusletWindow() : DraggablePluslet()}
            <DeleteConfirmModal show={deletePlusletClicked} resourceName="Box" 
                onHide={() => setDeletePlusletClicked(false)}
                confirmOnClick={handlePlusletDelete} />
        </>
    )
}

export default Pluslet;