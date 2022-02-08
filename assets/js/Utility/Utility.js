import React from 'react';
import ReactDOM from 'react-dom';

/**
 * Utility.js 
 * 
 * Class of commonly used functions within JS.
 */

export default class Utility {

    static htmlEntityDecode(str) {
        if (typeof str !== 'string') return '';

        var doc = new DOMParser().parseFromString(str, 'text/html');
        return doc.body.textContent || '';
    }

    static replaceNodeWithReactComponent(element, reactComponent) {
        const parent = element.parentNode;
        ReactDOM.render(ReactDOM.createPortal(reactComponent, parent),
            document.createElement('div'));
        
        element.remove();
    }
}