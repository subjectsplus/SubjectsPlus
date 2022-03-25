import React from 'react';

function Error({ errorCode, errorMessage}) {
    return (
        <div>
            <p>Error has occurred!</p>
            <p>{'Error: ' + errorCode + ' - ' + errorMessage}</p>
        </div>
    )
}

export default Error;