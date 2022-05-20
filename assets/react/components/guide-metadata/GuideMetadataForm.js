import React from 'react';

function GuideMetadataForm({ guide, disabled, onSubmit }) {
    return (
        <form action={void(0)} onSubmit={onSubmit}>
                <fieldset disabled={disabled}>
                    {/* Guide Shortform text field */}
                    <div id="guide-shortform">
                        <label htmlFor="guide-shortform-field" className="form-label">
                            Shortform
                        </label>
                        {' '}
                        <input
                            type="text"
                            name="shortform"
                            id="guide-shortform-field"
                            placeholder="Shortform"
                            autoComplete="off"
                            defaultValue={guide.shortform}
                            required="required"
                        />
                    </div>

                    {/* Guide Subject text field */}
                    <div id="guide-subject">
                        <label htmlFor="guide-subject-field" className="form-label">
                            Guide Title
                        </label>
                        {' '}
                        <input
                            type="text"
                            name="subject"
                            id="guide-subject-field"
                            placeholder="Title"
                            autoComplete="off"
                            defaultValue={guide.subject}
                            required="required"
                        />
                    </div>
                    
                     {/* Guide Type select field */}
                     <div id="guide-type">
                        <label htmlFor="guide-type-field" className="form-label">
                            Type
                        </label>
                        {' '}
                        <select id="guide-type-field" name="type" defaultValue={guide.type}>
                            <option value="Subject">Subject</option>
                            <option value="Topic">Topic</option>
                            <option value="Course">Course</option>
                            <option value="Placeholder">Placeholder</option>
                        </select>
                    </div>

                    {/* Guide Active select field */}
                    <div id="guide-active">
                        <label htmlFor="guide-active-field" className="form-label">
                            Status
                        </label>
                        {' '}
                        <select id="guide-active-field" name="active" defaultValue={guide.active}>
                            <option value="0">Inactive</option>
                            <option value="1">Active</option>
                            <option value="2">Suppressed</option>
                        </select>
                    </div>

                    {/* Guide Description textarea field */}
                    <div id="guide-description">
                        <label htmlFor="guide-description-field" className="form-label">
                            Description
                        </label>
                        {' '}
                        <textarea id="guide-description-field" name="description" cols="30" rows="8" defaultValue={guide.description} />
                    </div>

                    {/* Submit Button */}
                    <div id="save-guide-metadata">
                        <input type="submit" value="Save Guide Details" />
                    </div>
                </fieldset>
        </form>
    );
}

export default GuideMetadataForm;