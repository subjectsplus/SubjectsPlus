import React, { useEffect, useState } from 'react';
import GuideTabContainer from './GuideTabContainer.js';
import Utility from '../../../backend/javascript/Utility/Utility.js';
import { DragDropContext, Droppable } from 'react-beautiful-dnd';

function GuideBuilder(props) {
    const apiLink = '/api/subjects/';

    const [guide, setGuide] = useState(null);
    const [loading, setLoading] = useState(true);
    const [isErrored, setIsErrored] = useState(false);

    useEffect(() => getGuide(), [props.guideId]);

    const getAPILink = () => {
        return apiLink + props.guideId;
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
                    <h3>{Utility.htmlEntityDecode(guide.subject)}</h3>
                    <GuideTabContainer guideId={props.guideId} />
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