import React from 'react';
import MediaList from './MediaList';

function MediaBrowser() {

    /**
     * <Media>
     *      <MediaUploader />
     *      <MediaList />
     * </Media>
     */
    return <MediaList staffId={CURRENT_STAFF_ID} />
}

export default MediaBrowser;