import { useState } from 'react';
import { useFetchGuide, useUpdateGuide } from '#api/guide/GuideAPI';
import GuideMetadataForm from './GuideMetadataForm';

function GuideMetadata({ subjectId }) {
    const {isLoading, isError, data, error} = useFetchGuide(subjectId);
    
    const [isUpdating, setIsUpdating] = useState(false);

    const updateGuideMutation = useUpdateGuide(subjectId);

    const onMetadataSubmit = evt => {
        evt.preventDefault();

        setIsUpdating(true);

        updateGuideMutation.mutate({
            subjectId: subjectId,
            data: {
                shortform: evt.target.shortform.value,
                subject: evt.target.subject.value,
                type: evt.target.type.value,
                active: Number(evt.target.active.value),
                description: evt.target.description.value
            }
        }, {
            onSuccess: (updatedData) => {
                // Replace the static text labels on page with updated values
                //const shortformLabel = document.getElementById('shortform-label');
                const shortformLink = document.getElementById('shortform-link');
                const subjectHeading = document.getElementById('subject-heading');

                //shortformLabel.innerText = updatedData.shortform;
                shortformLink.setAttribute('href', '/' + updatedData.shortform);
                subjectHeading.innerText = updatedData.subject;
            },
            onSettled: () => setIsUpdating(false)
        });
    }
    
    const metadataContent = () => {
        if (isLoading) {
            return (<p>Loading Guide Details...</p>);
        } else if (isError) {
            console.error(error);
            return (<p>Error: Failed to load guide through API Endpoint!</p>);
        } else {
            return (<GuideMetadataForm guide={data} disabled={isUpdating} onSubmit={onMetadataSubmit} />);
        }
    }

    return metadataContent();
}

export default GuideMetadata;