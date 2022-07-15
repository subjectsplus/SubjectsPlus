import { useState } from 'react';
import { useFetchGuide } from '@hooks/useFetchGuide';
import { useUpdateGuide } from '@hooks/useUpdateGuide';
import { GuideType } from '@shared/types/guide_types';
import { GuideMetadataForm } from './GuideMetadataForm';

type GuideMetadataProps = {
    subjectId: number
};

export const GuideMetadata = ({ subjectId }: GuideMetadataProps) => {
    const {isLoading, isError, data, error} = useFetchGuide(subjectId);
    
    const [isUpdating, setIsUpdating] = useState(false);

    const updateGuideMutation = useUpdateGuide(subjectId);

    const onMetadataSubmit = (evt: React.FormEvent<HTMLFormElement>) => {
        evt.preventDefault();

        setIsUpdating(true);
        
        const form = new FormData(evt.currentTarget);
        
        updateGuideMutation.mutate({
            subjectId: subjectId,
            data: {
                shortform: form.get('shortform') as string,
                subject: form.get('subject') as string,
                type: form.get('type') as string,
                active: Number(form.get('active') as string),
                description: form.get('description') as string
            }
        }, {
            onSuccess: (updatedData: GuideType) => {
                // Replace the static text labels on page with updated values
                //const shortformLabel = document.getElementById('shortform-label');
                const shortformLink = document.getElementById('shortform-link');
                const subjectHeading = document.getElementById('subject-heading');

                //shortformLabel.innerText = updatedData.shortform;
                if (shortformLink && updatedData.shortform) {
                    shortformLink.setAttribute('href', '/' + updatedData.shortform);
                }

                if (subjectHeading && updatedData.subject) {
                    subjectHeading.innerText = updatedData.subject;
                }
            },
            onSettled: () => setIsUpdating(false)
        });
    }
    
    if (isLoading) {
        return (<p>Loading Guide Details...</p>);
    } else if (isError) {
        console.error(error);
        return (<p>Error: Failed to load guide through API Endpoint!</p>);
    } else {
        if (data) {
            return (<GuideMetadataForm guide={data} disabled={isUpdating} onSubmit={onMetadataSubmit} />);
        } else {
            return (<p>Error: Guide does not exist.</p>);
        }
    }
}