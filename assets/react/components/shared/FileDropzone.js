import React from 'react';
import { useDropzone } from 'react-dropzone';

function FileDropzone({ onDrop, multiple }) {

  const {getRootProps, getInputProps, isDragActive} = useDropzone({ onDrop: onDrop, multiple: multiple });

  return (
    <div {...getRootProps()}>
      <input {...getInputProps()} />
      {
        isDragActive ?
          <p>Drop the files here ...</p> :
          <p>Drag 'n' drop some files here, or click to select files</p>
      }
    </div>
  );
}

export default FileDropzone;