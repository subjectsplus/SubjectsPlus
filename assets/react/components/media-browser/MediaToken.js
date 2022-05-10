import React, { useState } from 'react';
import MediaPreview from './MediaPreview';
import OverlayTrigger from 'react-bootstrap/OverlayTrigger';
import Tooltip from 'react-bootstrap/Tooltip';

function MediaToken({ media, defaultImageSize }) {
    const [currentImageSize, setCurrentImageSize] = useState(defaultImageSize);

    const isImage = media.mimeType.includes('image/');
    
    const imageSizeFileNames = {
        large: media.largeFileName,
        medium: media.mediumFileName,
        small: media.smallFileName
    };

    const imageFileDimensions = {
        large: {width: media.largeImageFileWidth, height: media.largeImageFileHeight},
        medium: {width: media.mediumImageFileWidth, height: media.mediumImageFileHeight},
        small: {width: media.smallImageFileWidth, height: media.smallImageFileHeight}
    };

    const fileName = (isImage ? imageSizeFileNames[currentImageSize] : media.fileName);
    const relativeUrl = media.directory + '/' + fileName;

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

    const getImageSizeTooltip = (imageSize) => {
        const width = imageFileDimensions[imageSize]['width'];
        const height = imageFileDimensions[imageSize]['height'];
        
        if (width === undefined || height === undefined) {
            return (
                <Tooltip id={`tooltip-${currentImageSize}`}>
                    Dimensions unavailable!
                </Tooltip>
            );
        }

        return (
            <Tooltip id={`tooltip-${currentImageSize}`}>
                Dimensions (width x height): {width + 'x' + height}
            </Tooltip>
        );
    }

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
                    <OverlayTrigger placement="top" overlay={getImageSizeTooltip('small')}>
                        <button className={getActiveButtonClass('btn btn-outline-primary fs-sm', 'small')}
                            disabled={media.smallFileName === null}
                            onClick={evt => handleImageSizeButtonClick(evt, 'small')}>
                                Small
                        </button>
                    </OverlayTrigger>
                    {' '}
                    <OverlayTrigger placement="top" overlay={getImageSizeTooltip('medium')}>
                        <button className={getActiveButtonClass('btn btn-outline-primary fs-sm', 'medium')}
                            disabled={media.mediumFileName === null}
                            onClick={evt => handleImageSizeButtonClick(evt, 'medium')}>
                                Medium
                        </button>
                    </OverlayTrigger>
                    {' '}
                    <OverlayTrigger placement="top" overlay={getImageSizeTooltip('large')}>
                        <button className={getActiveButtonClass('btn btn-outline-primary fs-sm', 'large')}
                            disabled={media.largeFileName === null}
                            onClick={evt => handleImageSizeButtonClick(evt, 'large')}>
                                Large
                        </button>
                    </OverlayTrigger>
                </div>
            }
        </div>
    );
}

export default MediaToken;