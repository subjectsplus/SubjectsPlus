import { useState } from 'react';
import { DraggableProvidedDragHandleProps } from 'react-beautiful-dnd';
import { useDebouncedCallback } from 'use-debounce';
import { usePlusletWindow, PlusletWindowType } from '@context/PlusletWindowContext';

type EditableTitleProps = {
    dragHandleProps?: DraggableProvidedDragHandleProps,
    plusletTitle: string,
    savePlusletCallback: (data: object, toggleEditMode?: boolean) => void,
};

export const EditableTitle = ({ dragHandleProps, plusletTitle, savePlusletCallback }: EditableTitleProps) => {
    const { isEditMode } = usePlusletWindow() as PlusletWindowType;
    const [title, setTitle] = useState(plusletTitle);

    const handleOnChange = (evt: React.ChangeEvent<HTMLInputElement>) => {
        evt.preventDefault();
        setTitle(evt.target.value);
        if (evt.target.value !== title) {
            debouncedSaveTitle(evt.target.value);
        }
    }

    const saveTitle = (newTitle: string, toggleEditMode: boolean = false) => {
        savePlusletCallback({ title: newTitle }, toggleEditMode);
    }

    const debouncedSaveTitle = useDebouncedCallback(saveTitle, 500);

    const handleOnKeyDown = (evt: React.KeyboardEvent<HTMLInputElement>) => {
        if (evt.code === 'Enter') {
            evt.preventDefault();
            saveTitle(evt.target.value, true);
        }
    }

    if (isEditMode) {
        return (
            <div className="sp-pluslet-title">
                {/* Label is for accessibility purposes, will not be visible */}
                <label htmlFor="edit-pluslet-title" className="form-label visually-hidden">
                    Enter Box Title
                </label>
                <input
                    type="text"
                    id="edit-pluslet-title"
                    placeholder= "Enter Box Title"
                    className="form-control"
                    autoFocus={plusletTitle.trim() === ''}
                    defaultValue={plusletTitle}
                    autoComplete="off"
                    onChange={handleOnChange}
                    onKeyDown={handleOnKeyDown}
                />
            </div>
        );
    } else {
        return (<p className="sp-pluslet-title" title="Move Box" {...dragHandleProps}>{plusletTitle}</p>);
    }
}