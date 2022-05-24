
import React from 'react';

const mimeTypeIcons = {
    'audio/': 'fas fa-file-audio',
    'video/': 'fas fa-file-video',
    'application/pdf': 'fas fa-file-pdf',
    'application/msword': 'fas fa-file-word',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 'fas fa-file-word',
    'application/vnd.ms-excel': 'fas fa-file-excel',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': 'fas fa-file-excel',
    'application/vnd.ms-powerpoint': 'fas fa-file-powerpoint',
    'application/vnd.openxmlformats-officedocument.presentationml.presentation': 'fas fa-file-powerpoint',
    'text/csv': 'fas fa-file-csv'
};

function MediaPreview({ mimeType, source }) {
    if (mimeType.includes('image/')) {
        return (<img className="media-preview" src={source} />);
    } else if (mimeType in mimeTypeIcons) {
        return (
            <span className="media-preview">
                <i className={mimeTypeIcons[mimeType] + ' fa-2x'}></i>
            </span>
        );
    } else if (mimeType.length > 6 && mimeType.substring(0, 6) in mimeTypeIcons) {
        return (
            <span className="media-preview">
                <i className={mimeTypeIcons[mimeType.substring(0, 6)] + ' fa-2x'}></i>
            </span>
        );
    } else {
        return (
            <span className="media-preview">
                <i className="fas fa-file fa-2x"></i>
            </span>
        );
    }
}

export default MediaPreview;