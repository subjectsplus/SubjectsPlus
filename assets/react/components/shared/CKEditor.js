import React from 'react';
import { CKEditor as Editor } from 'ckeditor4-react';

const CONFIG = {
    customConfig: '/build/ckeditor/sp_config.js'
};

const editorUrl = '/build/ckeditor/ckeditor.js';

function CKEditor({ initData }) {
    return <Editor editorUrl={editorUrl} config={CONFIG} initData={initData} />
}

export default CKEditor;