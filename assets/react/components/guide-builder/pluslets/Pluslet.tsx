import { useState, useEffect, useRef, useMemo } from 'react';
import DOMPurify from 'dompurify';
import { Draggable, DraggableProvided, DraggableStateSnapshot } from 'react-beautiful-dnd';
import { CKEditorEventPayload } from 'ckeditor4-react';
import { useUpdatePluslet } from '@hooks/useUpdatePluslet';
import { useDeletePluslet } from '@hooks/useDeletePluslet';
import { CKEditor } from '@components/shared/CKEditor';
import { DeleteConfirmModal } from '@components/shared/DeleteConfirmModal';
import { ActionsContainer}  from './shared/ActionsContainer';
import { hideAllOffcanvas } from '@utility/Utility';
import { useDraggableInPortal } from '@hooks/useDraggableInPortal';
import { useSectionContainer, SectionContainerType } from '@context/SectionContainerContext';
import { PlusletBody } from './shared/PlusletBody';
import { PlusletType } from '@shared/types/guide_types';
import { PlusletWindowProvider } from '@context/PlusletWindowContext';
import { useDebouncedCallback } from 'use-debounce';

type PlusletProps = {
    pluslet: PlusletType,
    plusletRow: number,
    sectionUUID: string
}

export const Pluslet = ({ pluslet, plusletRow, sectionUUID}: PlusletProps) => {
    const { currentDraggingId, currentEditablePluslet, setCurrentEditablePluslet } = useSectionContainer() as SectionContainerType;

    const [isEditMode, setIsEditMode] = useState(currentEditablePluslet === pluslet.id);
    const [plusletHovered, setPlusletHovered] = useState(false);
    const [deletePlusletClicked, setDeletePlusletClicked] = useState(false);
    
    const plusletDropdownRef = useRef<HTMLUListElement>(null);

    const renderDraggable = useDraggableInPortal();

    const updatePlusletMutation = useUpdatePluslet(sectionUUID);
    const deletePlusletMutation = useDeletePluslet(sectionUUID);

    const isCurrentlyDragging = (('pluslet-' + pluslet.id) === currentDraggingId);
    const isBeingDraggedOver = (!isCurrentlyDragging && typeof currentDraggingId === 'string' && 
        currentDraggingId.substring(0, 8) === 'pluslet-');
    const isActiveDropdown = plusletDropdownRef?.current?.classList ? 
        plusletDropdownRef.current.classList.contains('show') : false;

    const savePluslet = (data: object, toggleEditMode: boolean = false) => {
        updatePlusletMutation.mutate({
            plusletUUID: pluslet.id,
            data: data
        });

        if (toggleEditMode) {
            setIsEditMode(false);
            setCurrentEditablePluslet('');
        }
    }

    const deletePluslet = () => {
        deletePlusletMutation.mutate({
            plusletUUID: pluslet.id,
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
        if (currentEditablePluslet !== pluslet.id) {
            setIsEditMode(true);
            setCurrentEditablePluslet(pluslet.id);
        } else {
            setIsEditMode(false);
            setCurrentEditablePluslet('');
        }
    }

    const handleSaveKey = (evt: React.KeyboardEvent) => {
        if (currentEditablePluslet === pluslet.id) {
            if ((evt.ctrlKey || evt.metaKey) && evt.key === 's') {
                evt.preventDefault();
                toggleEditable();
                hideAllOffcanvas();
            }
        }
    }

    const getPlusletClassName = (isDragging: boolean) => {
        let className = 'pluslet';

        if (isDragging) {
            className += ' sp-pluslet-dragging';
        } else if (isBeingDraggedOver) {
            className += ' sp-pluslet-dragging-over'
        } else if (plusletHovered || isEditMode) {
            className += ' sp-pluslet-hover-region';
        }

        return className;
    }
    
    const PlusletWindow = (provided: DraggableProvided|null = null, snapshot: DraggableStateSnapshot|null = null) => (
        <div className={getPlusletClassName(snapshot?.isDragging || isCurrentlyDragging)} key={pluslet.id} 
            ref={provided?.innerRef} onDoubleClick={() => !isEditMode && toggleEditable()}
            onKeyDown={handleSaveKey} onMouseEnter={() => setPlusletHovered(true)} onMouseLeave={() => setPlusletHovered(false)} {...provided?.draggableProps}>
            <span className="visually-hidden">{'Pluslet ' + pluslet.id}</span>

            {/* Actions Container */}
            <ActionsContainer editSaveOnClick={toggleEditable}
                visible={plusletHovered || isEditMode || isActiveDropdown} plusletDropdownRef={plusletDropdownRef} 
                deletePlusletOnClick={handlePlusletDelete}
                dragHandleProps={provided?.dragHandleProps}
                title={pluslet.title}
            />
            
            {/* Pluslet Body */}
            <PlusletBody pluslet={pluslet} isDragging={snapshot?.isDragging || isCurrentlyDragging || isBeingDraggedOver} />
        </div>
    );
    
    const DraggablePluslet = () => (
        <Draggable key={pluslet.id.toString()} draggableId={'pluslet-' + pluslet.id} index={plusletRow}>
            {renderDraggable((provided, snapshot) => PlusletWindow(provided, snapshot))}
        </Draggable>
    )
    
    return (
        <PlusletWindowProvider isEditMode={isEditMode} setIsEditMode={setIsEditMode} savePlusletCallback={savePluslet}>
            {isEditMode ? PlusletWindow() : DraggablePluslet()}
            <DeleteConfirmModal show={deletePlusletClicked} resourceName="Box" 
                onHide={() => setDeletePlusletClicked(false)}
                confirmOnClick={handlePlusletDelete} />
        </PlusletWindowProvider>
    )
}