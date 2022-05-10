import React, { useState } from 'react';
import MediaPreview from './MediaPreview';

function MediaToken({ media, defaultImageSize }) {
    const [currentImageSize, setCurrentImageSize] = useState(defaultImageSize);

    const handleImageSizeButtonClick = (evt, imageSize) => {
        evt.preventDefault();
        setCurrentImageSize(imageSize);
    }

    const getActiveButtonClass = (className, imageSize) => {
        if (currentImageSize === imageSize) {
            return className + ' active';
        }
        return className;
    }

    const isImage = media.mimeType.includes('image/');
    
    const imageSizeFileNames = {
        large: media.largeFileName,
        medium: media.mediumFileName,
        small: media.smallFileName
    };

    const fileName = (isImage ? imageSizeFileNames[currentImageSize] : media.fileName);
    const relativeUrl = media.directory + '/' + fileName;

    return (
        <div className="media-token" draggable="true" data-media-id={media.mediaId}
            data-mime-type={media.mimeType}
            data-link={relativeUrl}
            data-title={media.title} data-alt-text={media.altText}
            data-caption={media.caption}>
            <MediaPreview mimeType={media.mimeType} source={relativeUrl} />
            {' '}
            <span>{media.title}</span>
            {' '}
            {isImage &&
                <div className="image-file-sizes">
                    <button className={getActiveButtonClass('btn btn-outline-primary fs-sm', 'small')}
                        disabled={media.smallFileName === null}
                        onClick={evt => handleImageSizeButtonClick(evt, 'small')}
                        >Small</button>
                    {' '}
                    <button className={getActiveButtonClass('btn btn-outline-primary fs-sm', 'medium')}
                        disabled={media.mediumFileName === null}
                        onClick={evt => handleImageSizeButtonClick(evt, 'medium')}
                        >Medium</button>
                    {' '}
                    <button className={getActiveButtonClass('btn btn-outline-primary fs-sm', 'large')}
                        disabled={media.largeFileName === null}
                        onClick={evt => handleImageSizeButtonClick(evt, 'large')}
                        >Large</button>
                </div>
            }
        </div>
    );
}

export default MediaToken;