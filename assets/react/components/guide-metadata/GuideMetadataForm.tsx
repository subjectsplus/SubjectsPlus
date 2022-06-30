import { GuideType } from '@shared/types/guide_types';

type GuideMetadataFormProps = {
    guide: GuideType,
    disabled: boolean,
    onSubmit: any
}

export const GuideMetadataForm = ({ guide, disabled, onSubmit }: GuideMetadataFormProps) => (
    <form action={void(0)} onSubmit={onSubmit}>
        <fieldset disabled={disabled}>
            {/* Guide Subject text field */}
            <div id="guide-subject" className="mb-2">
                <div className="row">
                    <div className="col-3">
                        <label htmlFor="guide-subject-field" className="form-label fs-sm form-required">
                            Guide Title
                        </label>
                        {' '}
                    </div>
                    <div className="col-9">
                        <input
                            type="text"
                            name="subject"
                            id="guide-subject-field"
                            placeholder="Title"
                            autoComplete="off"
                            defaultValue={guide.subject}
                            required={true}
                            className="form-control form-control-sm"
                        />
                    </div>
                </div>
            </div>

            {/* Guide Shortform text field */}
            <div id="guide-shortform" className="mb-2">
                <div className="row">
                    <div className="col-3">
                        <label htmlFor="guide-shortform-field" className="form-label fs-sm form-required">
                            Short Form
                        </label>
                        {' '}
                    </div>
                    <div className="col-9">
                        <input
                            type="text"
                            name="shortform"
                            id="guide-shortform-field"
                            placeholder="Shortform"
                            autoComplete="off"
                            defaultValue={guide.shortform}
                            required={true}
                            className="form-control form-control-sm text-lowercase"
                        />
                    </div>
                </div>
            </div>
            
            {/* Guide Type select field */}
            {/* TODO: Apply react-select style */}
                <div id="guide-type" className="mb-2">
                    <div className="row">
                        <div className="col-3">
                            <label htmlFor="guide-type-field" className="form-label fs-sm form-required">
                                Type
                            </label>
                            {' '}
                        </div>
                        <div className="col-9">
                            <select id="guide-type-field" name="type" defaultValue={guide.type} className="form-select form-select-sm">
                                <option value="Subject">Subject</option>
                                <option value="Topic">Topic</option>
                                <option value="Course">Course</option>
                                <option value="Placeholder">Placeholder</option>
                            </select>
                        </div>
                    </div>
            </div>

            {/* Guide Active select field */}
            {/* TODO: Apply react-select style */}
            <div id="guide-active" className="mb-2">
                <div className="row">
                    <div className="col-3">
                        <label htmlFor="guide-active-field" className="form-label fs-sm form-required">
                            Status
                        </label>
                        {' '}
                    </div>
                    <div className="col-9">
                        <select id="guide-active-field" name="active" defaultValue={guide.active} className="form-select form-select-sm">
                            <option value="0">Inactive</option>
                            <option value="1">Active</option>
                            <option value="2">Suppressed</option>
                        </select>
                    </div>
                </div>
            </div>

            {/* Guide Description textarea field */}
            <div id="guide-description" className="mb-3">
                <label htmlFor="guide-description-field" className="form-label fs-sm">
                    Description
                </label>
                {' '}
                <textarea id="guide-description-field" name="description" cols={30} rows={6} defaultValue={guide.description} className="form-control form-control-sm" />
            </div>

            {/* Submit Button */}
            <div id="save-guide-metadata">
                <input type="submit" value="Save Guide Details" className="btn btn-gradient btn-round btn-sm" />
            </div>
        </fieldset>
    </form>
);