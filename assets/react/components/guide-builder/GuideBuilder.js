import { useEffect, useState } from 'react';
import GuideTabContainer from './GuideTabContainer';
import { ToastContainer } from 'react-toastify';

function GuideBuilder(props) {
    return (
        <div id="guide-builder">
            <GuideTabContainer subjectId={props.subjectId} />
            <ToastContainer />
        </div>
    );
}

export default GuideBuilder;