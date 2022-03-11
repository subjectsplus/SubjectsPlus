import React from 'react';
import ReactDOM from 'react-dom';
import GuideBuilder from '../../../react/components/guide-builder/GuideBuilder.js';

// TODO: Revisit this method of gathering the guide id
// possibly without relying on the client window location
let path = window.location.pathname;
let guideId = path.split("/").pop();

ReactDOM.render(<GuideBuilder guideId={guideId} />, 
    document.getElementById('guide-builder-container'));