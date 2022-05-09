import React, { useCallback, useState } from 'react';
import FileDropzone from '#components/shared/FileDropzone.js';
import MediaUploadForm from './MediaUploadForm';
import { createMedia } from '#api/media/MediaAPI';
import { removeFileExtension } from '#utility/Utility';

function MediaUploader({ fileUploadedCallback }) {
    const [fileDropped, setFileDropped] = useState(null);
    const [isUploading, setIsUploading] = useState(false);

    const onDrop = useCallback(acceptedFiles => {
        if (acceptedFiles?.length > 0) {
            setFileDropped(acceptedFiles[0]);
        }
      }, []);
    
    const onUploadSubmit = evt => {
        evt.preventDefault();
        setIsUploading(true);
        const initialMediaData = { 
            title: evt.target.title.value || 'Untitled',
            caption: evt.target.caption.value,
            altText: evt.target.altText.value,
            file: fileDropped
        };
        // TODO: Create media attachment
        // TODO: Handle bad request errors
        createMedia(initialMediaData).then(
            () => {
                fileUploadedCallback();
                setIsUploading(false);
                setFileDropped(null);
            }
        ).catch(err => console.error(err));
    }

    const onCancel = evt => {
        evt.preventDefault();
        setFileDropped(null);
        setIsUploading(false);
    }

    const getDefaultTitle = () => {
        if (fileDropped?.name) {
            return removeFileExtension(fileDropped.name);
        }
        return 'Untitled';
    }

    const getContent = () => {
        if (fileDropped) {
            return (<MediaUploadForm disabled={isUploading} 
                        defaultTitle={getDefaultTitle()}
                        onCancel={onCancel}
                        onSubmit={onUploadSubmit} />);
        } else {
            return (
                <div id="file-dropzone">
                    <FileDropzone multiple={false} onDrop={onDrop} />
                </div>
            );
        }
    }

    return getContent();
}

export default MediaUploader;