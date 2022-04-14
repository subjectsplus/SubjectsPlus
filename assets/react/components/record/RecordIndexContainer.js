import React, { useState, useEffect, useRef } from 'react';
import AlphabetList from "#components/record/AlphabetList";

import Records from "#components/record/Records";

function RecordIndexContainer(props) {

    return (
        <>
            <div>
                <h1>Record Index Container</h1>
            </div>
            <div><AlphabetList /></div>
            <div>Filters</div>
            <div>Search Bar</div>
            <div><Records />></div>
        </>
    )
}

export default RecordIndexContainer;