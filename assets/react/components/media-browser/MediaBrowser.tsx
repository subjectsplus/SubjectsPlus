import { useState } from 'react';
import { MediaUploader } from './MediaUploader';
import { MediaList } from './MediaList';

type MediaBrowserProps = {
    staffId: number
};

export const MediaBrowser = ({ staffId }: MediaBrowserProps) => {
    const [refresh, setRefresh] = useState(0);

    const performRefresh = () => {
        setRefresh(prev => prev + 1);
    }

    return (
        <div id="media-browser">
            <MediaUploader fileUploadedCallback={performRefresh}/>
            <MediaList refresh={refresh} staffId={staffId} />
        </div>
    );
}

export default MediaBrowser;