import React, { useState } from 'react';
import MediaUploader from './MediaUploader';
import MediaList from './MediaList';

function MediaBrowser() {
    const [refresh, setRefresh] = useState(0);

    const performRefresh = () => {
        setRefresh(prev => prev + 1);
    }

    return (
        <div id="media-browser">
            <MediaUploader fileUploadedCallback={performRefresh}/>
            <MediaList refresh={refresh} staffId={CURRENT_STAFF_ID} />
        </div>
    );
}

export default MediaBrowser;