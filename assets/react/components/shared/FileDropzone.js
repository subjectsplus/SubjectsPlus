import { useDropzone } from 'react-dropzone';

function FileDropzone({ onDrop, multiple }) {

  const {getRootProps, getInputProps, isDragActive} = useDropzone({ onDrop: onDrop, multiple: multiple });

  return (
    <div {...getRootProps({className: 'sp-media-dropzone'})}>
      <input {...getInputProps()} />
      {
        isDragActive ?
          <p>Drop the files here</p> :
            <p>Drag & drop or click to select file</p>
      }
    </div>
  );
}

export default FileDropzone;