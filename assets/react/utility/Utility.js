import ReactDOM from 'react-dom';

/**
 * Utility.js 
 * 
 * Commonly used functions within JS.
 */

export function htmlEntityDecode(str) {
    if (typeof str !== 'string') return '';

    var doc = new DOMParser().parseFromString(str, 'text/html');
    return doc.body.textContent || '';
}

export function objectIsEmpty(obj) {
    return obj && Object.keys(obj).length === 0
        && Object.getPrototypeOf(obj) === Object.prototype
}

export function replaceNodeWithReactComponent(element, reactComponent) {
    const parent = element.parentNode;
    ReactDOM.render(ReactDOM.createPortal(reactComponent, parent),
        document.createElement('div'));
    
    element.remove();
}

export function removeFileExtension(filename) {
    const lastDotPosition = filename.lastIndexOf('.');
    if (lastDotPosition === -1) return filename;
    
    return filename.substring(0, lastDotPosition);
}

export function hideAllOffcanvas() {
    if (window.recordTokenOffcanvas) {
        window.recordTokenOffcanvas.hide();
    }

    if (window.mediaTokenOffcanvas) {
        window.mediaTokenOffcanvas.hide();
    }
}