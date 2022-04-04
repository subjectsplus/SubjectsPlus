import React, { useEffect, useState } from 'react';
import GuideTabContainer from './GuideTabContainer';
import { htmlEntityDecode } from '#utility/Utility';
import { DragDropContext, Droppable } from 'react-beautiful-dnd';

function GuideBuilder(props) {
    const apiLink = '/api/subjects/';

    const [guide, setGuide] = useState(null);
    const [loading, setLoading] = useState(true);
    const [isErrored, setIsErrored] = useState(false);

    useEffect(() => getGuide(), [props.subjectId]);

    const getAPILink = () => {
        return apiLink + props.subjectId;
    }

    const getGuide = () => {
        // formulate the results api link for guide
        const resLink = getAPILink();

        // fetch api results
        fetch(resLink).then(response => {
            if (response.ok) {
                return response.json();
            }

            setIsErrored(true);
        })
        .then(results => {
            setGuide(results);
            setLoading(false);
            setIsErrored(false);
        })
        .catch(err => {
            console.error(err);
            setLoading(false);
            setIsErrored(true);
        });
    }

    const builderContent = () => {
        if (guide) {
            return (
                <>
                    <GuideTabContainer subjectId={props.subjectId} />
                </>
            );
        } else if (loading) {
            return (
                <p>Loading Guide...</p>
            );
        } else if (isErrored) {
            return (
                <p>Error: Failed to load guide through API Endpoint!</p>
            );
        } else {
            return (
                <p>Guide not found!</p>
            );
        }
    }

    return (
        <div id="guide-builder">
            {builderContent()}
        </div>
    );
}

export default GuideBuilder;