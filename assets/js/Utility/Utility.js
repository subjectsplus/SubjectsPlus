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
}