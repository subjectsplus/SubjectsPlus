import React, { useState } from 'react';
import MediaPreview from './MediaPreview';
import OverlayTrigger from 'react-bootstrap/OverlayTrigger';
import Tooltip from 'react-bootstrap/Tooltip';

function MediaToken({ media, defaultImageSize, onClick }) {
    const [currentImageSize, setCurrentImageSize] = useState(defaultImageSize);
    const [isChangingImageSize, setIsChangingImageSize] = useState(false);

    const isImage = media.mimeType.includes('image/');
    
    const imageSizeNameKeys = {
        small: 'Small',
        medium: 'Medium',
        large: 'Large'
    }

    const imageSizeFileNames = {
        small: media.smallFileName,
        medium: media.mediumFileName,
        large: media.largeFileName,
    };

    const imageFileDimensions = {
        small: {width: media.smallImageFileWidth, height: media.smallImageFileHeight},
        medium: {width: media.mediumImageFileWidth, height: media.mediumImageFileHeight},
        large: {width: media.largeImageFileWidth, height: media.largeImageFileHeight}
    };

    const fileName = (isImage ? imageSizeFileNames[currentImageSize] : media.fileName);
    const relativeUrl = media.directory + '/' + fileName;

    const handleImageSizeButtonClick = (evt, imageSize) => {
        evt.preventDefault();
        setCurrentImageSize(imageSize);
        setIsChangingImageSize(false);
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
                {width + 'px x ' + height + 'px'}
            </Tooltip>
        );
    }


    const generateImageSizeComponent = () => {
        if (isChangingImageSize) {
            const buttons = Object.keys(imageFileDimensions).map(key => {
                if (imageSizeFileNames[key]) {
                    return (
                        <OverlayTrigger key={'overlay-trigger-' + key} placement="top" overlay={getImageSizeTooltip(key)}>
                            <a className="link-primary fs-sm" onClick={evt => handleImageSizeButtonClick(evt, key)}>
                                {imageSizeNameKeys[key]}{' '}
                            </a>
                        </OverlayTrigger>
                    );
                }
            });

            return buttons;
        }

        return (
            <span>
                <b>{imageSizeNameKeys[currentImageSize]}</b>
                {' '}
                <a href={void(0)} className="link-primary fs-sm" onClick={() => setIsChangingImageSize(true)}>change</a>
            </span>
        );
    }

    return (
        <div className="media-token" draggable="true" data-media-id={media.mediaId}
            data-mime-type={media.mimeType}
            data-link={relativeUrl}
            data-title={media.title} data-alt-text={media.altText}
            data-caption={media.caption}>
            <span onClick={onClick}>
                <MediaPreview mimeType={media.mimeType} source={relativeUrl} />
                {' '}
                <span>{media.title}</span>
            </span>
            {' '}
            {isImage &&
                (<div className="image-size-change">
                    { generateImageSizeComponent() }
                </div>)
            }
        </div>
    );
}

export default MediaToken;