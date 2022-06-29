import { CKEditor as Editor } from 'ckeditor4-react';

const CONFIG = {
    customConfig: '/build/ckeditor/sp_config.js'
};

const editorUrl = '/build/ckeditor/ckeditor.js';

function CKEditor(props) {
    return <Editor {...props} editorUrl={editorUrl} config={CONFIG} />
}

export default CKEditor;