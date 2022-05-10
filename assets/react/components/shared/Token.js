import React from 'react';
import { htmlEntityDecode } from '#utility/Utility';

function Token({ token, tokenType, onClick }) {
    if (tokenType === 'record') {
        return (
            <div className="record-token" draggable="true" onClick={onClick}
                data-record-id={token.titleId} data-record-title={token.title}
                data-record-description={token.description} 
                data-record-location={token.location[0].location}>
                    {htmlEntityDecode(token.title)}
            </div>
        );
    }
}

export default Token;