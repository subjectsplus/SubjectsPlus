import { useState } from 'react';
import { CKEditorEventPayload } from 'ckeditor4-react';
import DOMPurify from 'dompurify';
import { useDebouncedCallback } from 'use-debounce';
import { CKEditor } from '@components/shared/CKEditor';
import { usePlusletWindow, PlusletWindowType } from '@context/PlusletWindowContext';
import { hideAllOffcanvas } from '@utility/Utility';

type BasicPlusletProps = {
    plusletBody: string,
    savePlusletCallback: (data: object, toggleEditMode?: boolean) => void
}

export const BasicPluslet = ({ plusletBody, savePlusletCallback }: BasicPlusletProps) => {
    const { isEditMode } = usePlusletWindow() as PlusletWindowType;
    const [body, setBody] = useState<string>(plusletBody);

    const savePluslet = (newBody: string, toggleEditMode: boolean = false) => {
        savePlusletCallback({
            body: DOMPurify.sanitize(newBody, { ADD_TAGS: ["iframe"] })
        }, toggleEditMode);
    }

    const debouncedSavePluslet = useDebouncedCallback(savePluslet, 400);

    const handleCKEditorInstanceReady = (evt: CKEditorEventPayload<'instanceReady'>) => {
        if (evt.editor) {
            evt.editor.setData(plusletBody);
        }
    }
    
    const onCKEditorChanged = (evt: CKEditorEventPayload<'change'>) => {
        if (evt.editor) {
            const newBody = evt.editor.getData();
            setBody(newBody);
            if (newBody !== body) {
                debouncedSavePluslet(newBody);
            }
        }
    }

    const handleCKEditorSaveKey = (evt: CKEditorEventPayload<'key'>) => {
        if (evt.data && evt.data.hasOwnProperty('domEvent')) {
            const data: Record<string, any> = evt.data;
            const domEvent = data['domEvent'].$;

            if (domEvent) {
                if ((domEvent.ctrlKey || domEvent.metaKey) && domEvent.key === 's') {
                    domEvent.preventDefault();
                    if (evt.editor) {
                        savePluslet(evt.editor.getData(), true);
                        hideAllOffcanvas();
                    }
                }
            }
        }
    }

    if (isEditMode) {
        return (<CKEditor name="pluslet_ckeditor" onKey={handleCKEditorSaveKey} 
                    onInstanceReady={handleCKEditorInstanceReady} onChange={onCKEditorChanged} />);
    } else {
        return (<div className="sp-pluslet-body" dangerouslySetInnerHTML={{__html: DOMPurify.sanitize(plusletBody, { ADD_TAGS: ["iframe"] })}} />);
    }
}