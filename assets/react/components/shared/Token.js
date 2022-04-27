import React from 'react';
import { htmlEntityDecode } from '#utility/Utility';

function Token(props) {
    if (props.tokenType === 'record') {
        return (
            <div className="record-token" draggable="true" onClick={props.onClick}
                data-record-id={props.token.titleId} data-record-title={props.token.title}
                data-record-description={props.token.description} 
                data-record-location={props.token.location[0].location}>
                    {htmlEntityDecode(props.token.title)}
            </div>
        );
    }
}

export default Token;