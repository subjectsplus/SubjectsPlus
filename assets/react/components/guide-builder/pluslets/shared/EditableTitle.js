import React from 'react';

function EditableTitle({isEditMode, dragHandleProps, title, onChange, onKeyDown}) {
    if (isEditMode) {
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
                    onChange={onChange}
                    onKeyDown={onKeyDown}
                />
            </div>
        );
    } else {
        return (<p className="sp-pluslet-title" {...dragHandleProps}>{title}</p>);
    }
}

export default EditableTitle;