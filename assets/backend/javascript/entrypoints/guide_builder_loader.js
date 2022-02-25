import React from 'react';
import ReactDOM from 'react-dom';
import Guide from '../../../react/components/guide-builder/Guide.js';

// TODO: Revisit this method of gathering the guide id
// possibly without relying on the client window location
let path = window.location.pathname;
let guideId = path.split("/").pop();

ReactDOM.render(<Guide guideId={guideId} />, 
    document.getElementById('guide-builder-container'));