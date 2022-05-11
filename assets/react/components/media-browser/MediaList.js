import React, { useState } from 'react';
import { useFetchMediaByStaff } from '#api/media/MediaAPI';
import MediaToken from './MediaToken';

function MediaList({ refresh, staffId }) {
    const {isLoading, isError, data, error, refetch} = useFetchMediaByStaff(staffId);
    const [currentRefresh, setCurrentRefresh] = useState(refresh);

    if (refresh !== currentRefresh) {
        setCurrentRefresh(refresh);
        refetch();
    }

    const getLargestImageSize = (media) => {
        if (media.largeFileName) {
            return 'large';
        } else if (media.mediumFileName) {
            return 'medium';
        } else if (media.smallFileName) {
            return 'small';
        }  
        return null;
    }

    const pasteToCKEditor = evt => {
        const mediaElement = evt.target.closest('div.media-token');

        if (CKEDITOR?.instances['pluslet_ckeditor']) {
            CKEDITOR.instances['pluslet_ckeditor'].insertHtml(mediaElement.outerHTML);
        } else if (CKEDITOR?.instances['faq_answer']) {
            CKEDITOR.instances['faq_answer'].insertHtml(mediaElement.outerHTML);
        }
    }

    const mediaTokens = () => {
        if (isLoading) {
            return (<p>Loading Media...</p>)
        } else if (isError) {
            console.error(error);
            return (<p>Error: Failed to load media through API Endpoint!</p>);
        } else {
            if (!data?.length) {
                return (<p>No media found.</p>);
            } else {
                return data.map(media => {
                    return (
                        <li key={media.mediaId}>
                            <MediaToken media={media} defaultImageSize={getLargestImageSize(media)} 
                                onClick={pasteToCKEditor} />
                        </li>
                    );
                });
            }
        }
    };

    return (
        <div id="media-list-container">
            <h3>My Media</h3>
            <ul id="media-list" className="list-unstyled">
                {mediaTokens()}
            </ul>
        </div>
    )
}

export default MediaList;