import { DraggableProvidedDragHandleProps } from 'react-beautiful-dnd';
import { EditableTitle } from './EditableTitle';
import { usePlusletWindow, PlusletWindowType } from '@context/PlusletWindowContext';

type ActionsContainerProps = {
    editSaveOnClick: React.MouseEventHandler<HTMLButtonElement>,
    deletePlusletOnClick: React.MouseEventHandler<HTMLAnchorElement>,
    visible: boolean,
    plusletDropdownRef: React.RefObject<HTMLUListElement>,
    title: string,
    savePlusletCallback: (data: object) => void,
    dragHandleProps?: DraggableProvidedDragHandleProps,
}

export const ActionsContainer = ({ editSaveOnClick, deletePlusletOnClick, visible, plusletDropdownRef, title, savePlusletCallback, dragHandleProps }: ActionsContainerProps) => {
    const { isEditMode } = usePlusletWindow() as PlusletWindowType;

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
            <EditableTitle dragHandleProps={dragHandleProps} plusletTitle={title} savePlusletCallback={savePlusletCallback} />

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