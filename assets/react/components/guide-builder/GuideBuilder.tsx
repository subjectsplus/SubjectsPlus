import { useEffect, useState } from 'react';
import { GuideTabContainer } from './GuideTabContainer';
import { ToastContainer } from 'react-toastify';

type GuideBuilderProps = {
    subjectId: number
}

export const GuideBuilder = ({ subjectId }: GuideBuilderProps) => {
    return (
        <div id="guide-builder">
            <GuideTabContainer subjectId={subjectId} />
            <ToastContainer />
        </div>
    );
}