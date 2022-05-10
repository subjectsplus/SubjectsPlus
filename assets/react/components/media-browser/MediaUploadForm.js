import React from 'react';

function MediaUploadForm({ disabled, onSubmit }) {
    return (
        <form action={void(0)} onSubmit={onSubmit}>
            <div className="mb-2">
                <fieldset disabled={disabled}>
                    {/* Media Title text field */}
                    <label htmlFor="media-title" className="form-label">
                        <span className="visually-hidden">Title</span>
                    </label>
                    <input
                        type="text"
                        name="title"
                        id="media-title-field"
                        placeholder="Title"
                        autoComplete="off"
                        defaultValue="Untitled"
                        required="required"
                    />

                    {/* Media Caption text field */}
                    <label htmlFor="media-caption-field" className="form-label">
                        <span className="visually-hidden">Caption</span>
                    </label>
                    <input
                        type="text"
                        name="caption"
                        id="media-caption-field"
                        placeholder="Caption"
                        autoComplete="off"
                    />

                    {/* Media AltText text field */}
                    <label htmlFor="media-alt-text-field" className="form-label">
                        <span className="visually-hidden">Alt Text</span>
                    </label>
                    <input
                        type="text"
                        name="altText"
                        id="media-alt-text-field"
                        placeholder="Alt Text"
                        autoComplete="off"
                    />

                    {/* Submit Button */}
                    <input type="submit" value="Upload Media" />
                </fieldset>
            </div>
        </form>
    );
}

export default MediaUploadForm;