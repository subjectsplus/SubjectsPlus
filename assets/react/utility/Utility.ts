import ReactDOM from 'react-dom';

/**
 * Utility.js 
 * 
 * React Component helper functions.
 */

export const htmlEntityDecode = (str: string): string => {
    if (typeof str !== 'string') return '';

    const doc = new DOMParser().parseFromString(str, 'text/html');
    return doc.body.textContent || '';
}

export const objectIsEmpty = (obj: object): boolean => {
    return obj && Object.keys(obj).length === 0
        && Object.getPrototypeOf(obj) === Object.prototype
}

export const replaceNodeWithReactComponent = (element: HTMLElement, reactComponent: React.ReactNode) => {
    const parent = element.parentNode as HTMLElement;
    ReactDOM.render(ReactDOM.createPortal(reactComponent, parent),
        document.createElement('div'));
    
    element.remove();
}

export const removeFileExtension = (filename: string): string => {
    const lastDotPosition = filename.lastIndexOf('.');
    if (lastDotPosition === -1) return filename;
    
    return filename.substring(0, lastDotPosition);
}

export const hideAllOffcanvas = () => {
    if ((<any> window).recordTokenOffcanvas) {
        (<any> window).recordTokenOffcanvas.hide();
    }

    if ((<any> window).mediaTokenOffcanvas) {
        (<any> window).mediaTokenOffcanvas.hide();
    }
}