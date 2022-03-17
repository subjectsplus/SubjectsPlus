import React, { useMemo } from 'react';
import { useFetchSections } from '#api/guide/SectionAPI';
import Section from './Section';

function SectionContainer({ tabId }) {
    const {isLoading, isError, data, error} = useFetchSections(tabId);

    const containerContent = useMemo(() => {
        if (isError) {
            console.error(error);
            return (<p>Error: Failed to load sections through API Endpoint!</p>);
        } else if (isLoading) {
            return (<p>Loading Sections...</p>);
        } else {
            const guideSections = data.map(section => 
                <Section key={section.sectionId} sectionId={section.sectionId} layout={section.layout} />);
            return (
                <div className="section-container">
                    {guideSections}
                </div> 
            );
        }
    }, [data]);

    return containerContent;
}

export default SectionContainer;