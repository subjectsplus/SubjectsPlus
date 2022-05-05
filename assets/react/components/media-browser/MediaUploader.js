import React, { useCallback } from 'react';
import FileDropzone from '#components/shared/FileDropzone.js';
import { createMedia } from '#api/media/MediaAPI';

function MediaUploader() {
    const onDrop = useCallback(acceptedFiles => {
        console.log('acceptedFiles: ', acceptedFiles);
        const initialMediaData = { 
            title: 'Untitled', 
            file: acceptedFiles[0]
        };
        createMedia(initialMediaData).then(
            data => console.log('data: ', data)
        ).catch(err => console.error(err));
      }, []);

    return (
        <div id="file-dropzone">
            <FileDropzone multiple={false} onDrop={onDrop} />
        </div>
    );
}

export default MediaUploader;