import React from 'react';
import { useFetchMediaByStaff } from '#api/media/MediaAPI';
import MediaPreview from './MediaPreview';

function MediaList({ staffId }) {
    const {isLoading, isError, data, error} = useFetchMediaByStaff(staffId);

    const mediaTokens = () => {
        if (isLoading) {
            return (<p>Loading Media...</p>)
        } else if (isError) {
            console.error(error);
            return (<p>Error: Failed to load media through API Endpoint!</p>);
        } else {
            console.log('media: ', data);
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
                                    <span>{token.title}</span>
                            </div>
                        </li>
                    );
                });
            }
        }
    };

    return (
        <ul id="media-list" className="list-unstyled">
            {mediaTokens()}
        </ul>
    )
}

export default MediaList;