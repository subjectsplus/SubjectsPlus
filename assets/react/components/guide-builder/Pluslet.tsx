import { useState, useEffect, useRef, useMemo } from 'react';
import DOMPurify from 'dompurify';
import { Draggable, DraggableProvided, DraggableStateSnapshot } from 'react-beautiful-dnd';
import { CKEditorEventPayload } from 'ckeditor4-react';
import { useDebouncedCallback } from 'use-debounce';
import { useUpdatePluslet } from '@hooks/useUpdatePluslet';
import { useDeletePluslet } from '@hooks/useDeletePluslet';
import { CKEditor } from '@components/shared/CKEditor';
import { DeleteConfirmModal } from '@components/shared/DeleteConfirmModal';
import { ActionsContainer}  from './pluslets/shared/ActionsContainer';
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
    const isBeingDraggedOver = (!isCurrentlyDragging && typeof currentDraggingId === 'string' && 
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

    const handleCKEditorSaveKey = (evt: CKEditorEventPayload<'key'>) => {
        if (currentEditablePluslet === plusletUUID) {
            if (evt.data && evt.data.hasOwnProperty('domEvent')) {
                const data: Record<string, any> = evt.data;
                const domEvent = data['domEvent'].$;

                if (domEvent) {
                    if ((domEvent.ctrlKey || domEvent.metaKey) && domEvent.key === 's') {
                        domEvent.preventDefault();
                        savePluslet();
                    }
                }
            }
        }
    }

    const handleSaveKey = (evt: React.KeyboardEvent) => {
        if (currentEditablePluslet === plusletUUID) {
            if ((evt.ctrlKey || evt.metaKey) && evt.key === 's') {
                evt.preventDefault();
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

    const handleCKEditorInstanceReady = (evt: CKEditorEventPayload<'instanceReady'>) => {
        if (evt.editor) {
            evt.editor.setData(body);
        }
    }

    const onCKEditorChanged = (evt: CKEditorEventPayload<'change'>) => {
        if (evt.editor) {
            setBody(evt.editor.getData())
            debouncedUpdatePlusletBody();
        }
    }

    const getPlusletClassName = (isDragging: boolean) => {
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

    const getPlusletBodyClassName = (isDragging: boolean) => {
        let className = 'sp-pluslet-body';

        if (isDragging) {
            className = 'visually-hidden';
        }

        return className;
    }

    const Editor = (isDragging: boolean) => {
        if (editable && !isDragging) {
            return (<CKEditor name="pluslet_ckeditor" onKey={handleCKEditorSaveKey} 
                        onInstanceReady={handleCKEditorInstanceReady} onChange={onCKEditorChanged} />);
        } else {
            return (<div className={getPlusletBodyClassName(isDragging)}
                        dangerouslySetInnerHTML={{__html: DOMPurify.sanitize(body, { ADD_TAGS: ["iframe"] })}} />);
        }
    }
    
    const PlusletWindow = (provided: DraggableProvided|null = null, snapshot: DraggableStateSnapshot|null = null) => (
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
        <Draggable key={plusletUUID.toString()} draggableId={'pluslet-' + plusletUUID} index={plusletRow}>
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