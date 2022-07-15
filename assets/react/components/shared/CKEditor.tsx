import { CKEditor as Editor, CKEditorProps, CKEditorEventHandlerProp, CKEditorEventHandler } from 'ckeditor4-react';

const CONFIG = {
    customConfig: '/build/ckeditor/sp_config.js'
};

const editorUrl = '/build/ckeditor/ckeditor.js';

type EditorProps = {
    name: string,
    onKey: CKEditorEventHandler<'key'>,
    onInstanceReady: CKEditorEventHandler<'instanceReady'>,
    onChange: CKEditorEventHandler<'change'>
}
export const CKEditor = ({name, onKey, onInstanceReady, onChange}: EditorProps) => {
    return <Editor name={name} onKey={onKey} onInstanceReady={onInstanceReady} onChange={onChange}
        editorUrl={editorUrl} config={CONFIG} />
}