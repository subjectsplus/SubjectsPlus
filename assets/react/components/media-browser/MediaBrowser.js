import React from 'react';
import MediaUploader from './MediaUploader';
import MediaList from './MediaList';

function MediaBrowser() {

    /**
     * <Media>
     *      <MediaUploader />
     *      <MediaList />
     * </Media>
     */
    return (
        <div id="media-browser">
            <MediaUploader />
            <MediaList staffId={CURRENT_STAFF_ID} />
        </div>
    );
}

export default MediaBrowser;