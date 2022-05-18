import React from 'react';
import { useDropzone } from 'react-dropzone';

function FileDropzone({ onDrop, multiple }) {

  const {getRootProps, getInputProps, isDragActive} = useDropzone({ onDrop: onDrop, multiple: multiple });

  return (
    <div {...getRootProps({className: 'sp-media-dropzone'})}>
      <input {...getInputProps()} />
      {
        isDragActive ?
          <p>Drop the files here</p> :
            <p>Drag 'n' drop a file or click to select one</p>
      }
    </div>
  );
}

export default FileDropzone;