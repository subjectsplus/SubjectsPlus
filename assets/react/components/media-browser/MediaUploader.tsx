import { useCallback, useState } from 'react';
import { FileDropzone } from '@components/shared/FileDropzone';
import { MediaUploadForm } from './MediaUploadForm';
import { createMedia } from '@api/media/MediaAPI';
import { removeFileExtension } from '@utility/Utility';

type MediaUploaderProps = {
    fileUploadedCallback: () => void
}

export const MediaUploader = ({ fileUploadedCallback }: MediaUploaderProps) => {
    const [fileDropped, setFileDropped] = useState<File|null>(null);
    const [isUploading, setIsUploading] = useState(false);
    const [isErrored, setIsErrored] = useState(false);

    const onDrop = useCallback((acceptedFiles: File[]) => {
        if (acceptedFiles?.length > 0) {
            setFileDropped(acceptedFiles[0]);
        }
      }, []);
    
    const onUploadSubmit = (evt:React.FormEvent<HTMLFormElement>) => {
        evt.preventDefault();
        setIsErrored(false);
        setIsUploading(true);
        
        const form = new FormData(evt.currentTarget);

        const initialMediaData = { 
            title: form.get('title') as string,
            caption: form.get('caption') as string,
            altText: form.get('altText') as string,
            file: fileDropped
        };

        // TODO: Handle bad request errors
        createMedia(initialMediaData).then(
            () => {
                fileUploadedCallback();
                setIsUploading(false);
                setFileDropped(null);
            }
        ).catch(err => {
            console.error(err);
            setIsUploading(false);
            setFileDropped(null);
            setIsErrored(true);
        });
    }

    const onCancel = () => {
        setFileDropped(null);
        setIsUploading(false);
    }

    const getDefaultTitle = (): string => {
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
                    {isErrored && <p style={{color: 'red'}}>An error occurred while uploading, please try again.</p>}
                </div>
            );
        }
    }

    return getContent();
}