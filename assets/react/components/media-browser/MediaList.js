import React, { useState } from 'react';
import { useFetchMediaByStaff } from '#api/media/MediaAPI';
import MediaPreview from './MediaPreview';

function MediaList({ refresh, staffId }) {
    const {isLoading, isError, data, error, refetch} = useFetchMediaByStaff(staffId);
    const [currentRefresh, setCurrentRefresh] = useState(refresh);

    if (refresh !== currentRefresh) {
        setCurrentRefresh(refresh);
        refetch();
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
                return data.map(token => {
                    const fileName = (token.mimeType.includes('image/') ? 
                        (token.largeFileName ?? token.mediumFileName ?? token.smallFileName) : token.fileName
                    );
                    const relativeUrl = token.directory + '/' + fileName;

                    return (
                        <li key={token.mediaId}>
                            <div className="media-card" draggable="true" data-media-id={token.mediaId}
                                data-mime-type={token.mimeType}
                                data-link={relativeUrl}
                                data-title={token.title} data-alt-text={token.altText}
                                data-caption={token.caption}>
                                    <MediaPreview mimeType={token.mimeType} source={relativeUrl} />
                                    {' '}
                                    <span>{token.title}</span>
                            </div>
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