function MediaUploadForm({ defaultTitle, disabled, onSubmit, onCancel }) {
    return (
        <form action={void(0)} onSubmit={onSubmit}>
            <div className="mb-3">
                <fieldset disabled={disabled}>
                    {/* Media Title text field */}
                    <div className="mb-1">
                    <label htmlFor="media-title" className="form-label visually-hidden">
                        <span>Title</span>
                    </label>
                    <input
                        type="text"
                        name="title"
                        id="media-title-field"
                        placeholder="Title"
                        autoComplete="off"
                        defaultValue={defaultTitle}
                        required="required"
                        className="form-control form-control-sm"
                    />
                    </div>

                    {/* Media AltText text field */}
                    <div className="mb-1">
                    <label htmlFor="media-alt-text-field" className="form-label visually-hidden">
                        <span>Alt Text</span>
                    </label>
                    <input
                        type="text"
                        name="altText"
                        id="media-alt-text-field"
                        placeholder="Alt Text"
                        autoComplete="off"
                        className="form-control form-control-sm"
                    />
                    </div>

                    {/* Media Caption text field */}
                    <div className="mb-1">
                    <label htmlFor="media-caption-field" className="form-label visually-hidden">
                        <span>Caption</span>
                    </label>
                    <input
                        type="text"
                        name="caption"
                        id="media-caption-field"
                        placeholder="Caption"
                        autoComplete="off"
                        className="form-control form-control-sm"
                    />
                    </div>
                    <div className="text-end mt-2">
                        {/* Cancel Button */}
                        <button onClick={onCancel} className="btn btn-link-default me-1 fs-sm">Cancel</button>

                        {/* Submit Button */}
                        <input type="submit" value="Upload Media" className="btn btn-gradient btn-round btn-sm"/>
                    </div>
                </fieldset>
            </div>
        </form>
    );
}

export default MediaUploadForm;