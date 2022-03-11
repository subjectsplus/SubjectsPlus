import React from 'react';
import Utility from '../../backend/javascript/Utility/Utility.js';

function Token(props) {
    if (props.tokenType === 'record') {
        return (
            <div className="record-token" draggable="true"
                data-record-id={props.token.titleId} data-record-title={props.token.title}
                data-record-description={props.token.description} 
                data-record-location={props.token.location[0].location}>
                    {Utility.htmlEntityDecode(props.token.title)}
            </div>
        );
    }
}

export default Token;