import { DraggableProvidedDragHandleProps } from 'react-beautiful-dnd';
import { EditableTitle } from './EditableTitle';

type ActionsContainerProps = {
    isEditMode: boolean,
    editSaveOnClick: React.MouseEventHandler<HTMLButtonElement>,
    deletePlusletOnClick: React.MouseEventHandler<HTMLAnchorElement>,
    visible: boolean,
    plusletDropdownRef: React.RefObject<HTMLUListElement>,
    title: string,
    dragHandleProps?: DraggableProvidedDragHandleProps,
    onEditableTitleChange: React.ChangeEventHandler<HTMLInputElement>
    onEditableTitleKeyDown: React.KeyboardEventHandler<HTMLInputElement>
}

export const ActionsContainer = ({ isEditMode, editSaveOnClick, deletePlusletOnClick, visible, plusletDropdownRef, 
    title, dragHandleProps, onEditableTitleChange, onEditableTitleKeyDown}: ActionsContainerProps) => {
    const EditSaveButton = () => {
        if (isEditMode) {
            return (
                <button onClick={editSaveOnClick} title="Save Box" className="btn btn-link sp-pluslet-icon-btn">
                    <i className="fas fa-save"></i>
                </button>
            );
        } else {
            return (
                <button onClick={editSaveOnClick} title="Edit Box" className="btn btn-muted sp-pluslet-icon-btn">
                    <i className="fas fa-pen"></i>
                </button>
            );
        }
    }

    return (
        <div className="sp-pluslet-actions-container">
            {/* Editable Title */}
            <EditableTitle isEditMode={isEditMode} dragHandleProps={dragHandleProps}
                title={title} onChange={onEditableTitleChange} onKeyDown={onEditableTitleKeyDown} />

            <div className={'text-end' + (visible ? '' : ' invisible')}>
                {/* Edit/Save Button */}
                {EditSaveButton()}

                {/* Dropdown */}
                <div className="dropdown basic-dropdown d-inline-block ms-1">
                    <button className="btn btn-muted sp-pluslet-icon-btn dropdown-toggle" id="sectionMenuOptions" data-bs-toggle="dropdown" 
                        aria-expanded="false" title="Box Options">
                        <i className="fas fa-ellipsis-v"></i>
                    </button>
                    <ul ref={plusletDropdownRef} className="dropdown-menu dropdown-arrow dropdown-menu-end fs-xs" aria-labelledby="plusletMenuOptions">
                        {/* Make Favorite */}
                        <li><a className="dropdown-item">Make Favorite</a></li>
                        <li><hr className="dropdown-divider" /></li>

                        {/* Delete Pluslet */}
                        <li><a className="dropdown-item" onClick={deletePlusletOnClick}><i
                            className="fas fa-trash"></i> Delete Box</a></li>
                    </ul>
                </div>
            </div>
        </div>
    );
}