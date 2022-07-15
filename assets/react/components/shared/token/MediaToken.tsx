import { useState, useMemo } from 'react';
import OverlayTrigger from 'react-bootstrap/OverlayTrigger';
import Tooltip from 'react-bootstrap/Tooltip';
import { MediaPreview } from '../../media-browser/MediaPreview';
import { MediaType, ImageSizeType, ImageFileDimensions } from '@shared/types/media_types';

type MediaTokenProps = {
    media: MediaType,
    onClick: React.MouseEventHandler<HTMLDivElement>
}

export const MediaToken = ({ media, onClick }: MediaTokenProps) => {
    const [currentImageSize, setCurrentImageSize] = useState<ImageSizeType>('small');
    const [isChangingImageSize, setIsChangingImageSize] = useState(false);

    const isImage = media.mimeType.includes('image/');
    
    const imageSizeNameKeys:Record<ImageSizeType, string> = {
        small: 'Small',
        medium: 'Medium',
        large: 'Large'
    }

    const imageSizeFileNames:Record<ImageSizeType, string|undefined> = {
        small: media.smallFileName,
        medium: media.mediumFileName,
        large: media.largeFileName,
    };

    const imageFileDimensions:Record<ImageSizeType, ImageFileDimensions> = {
        small: {width: media.smallImageFileWidth, height: media.smallImageFileHeight},
        medium: {width: media.mediumImageFileWidth, height: media.mediumImageFileHeight},
        large: {width: media.largeImageFileWidth, height: media.largeImageFileHeight}
    };

    const fileName = (isImage ? imageSizeFileNames[currentImageSize] : media.fileName);
    const relativeUrl = media.directory + '/' + fileName;

    const hasMultipleImageSizes = useMemo(() => {
        if (!isImage) return false;

        const imageSizes = Object.values(imageSizeFileNames).filter(size => size !== null);
        return imageSizes.length > 1;
    }, [imageSizeFileNames, isImage]);

    const handleImageSizeButtonClick = (evt: React.MouseEvent, imageSize:ImageSizeType) => {
        evt.preventDefault();
        setCurrentImageSize(imageSize);
        setIsChangingImageSize(false);
    }

    const getImageSizeTooltip = (imageSize:ImageSizeType) => {
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
            const sizes = Object.keys(imageFileDimensions) as ImageSizeType[];
            const buttons = sizes.map((key:ImageSizeType) => {
                if (imageSizeFileNames[key]) {
                    return (
                        <OverlayTrigger key={'overlay-trigger-' + key} placement="top" overlay={getImageSizeTooltip(key)}>
                            <a className="btn-link fs-xs ms-1" onClick={(evt) => handleImageSizeButtonClick(evt, key)}>
                                {imageSizeNameKeys[key]}{' '}
                            </a>
                        </OverlayTrigger>
                    );
                }
            });

            return buttons;
        }

        return (
            <span className="fs-xs">
                <b>Size: {imageSizeNameKeys[currentImageSize]}</b>
                {' '}
                {hasMultipleImageSizes && <a href={void(0)} className="btn-link ms-1" 
                    onClick={() => setIsChangingImageSize(hasMultipleImageSizes)}>(change)</a>}
            </span>
        );
    }

    return (
        <div className="media-token" draggable="true" data-media-id={media.mediaId}
            data-mime-type={media.mimeType}
            data-link={relativeUrl}
            data-title={media.title} data-alt-text={media.altText}
            data-caption={media.caption}>
            <div onClick={onClick}>
                <MediaPreview mimeType={media.mimeType} source={relativeUrl} />
                {' '}
                <span className="media-label">{media.title}</span>
            </div>
            {' '}
            {isImage &&
                (<div className="image-size-change">
                    { generateImageSizeComponent() }
                </div>)
            }
        </div>
    );
}