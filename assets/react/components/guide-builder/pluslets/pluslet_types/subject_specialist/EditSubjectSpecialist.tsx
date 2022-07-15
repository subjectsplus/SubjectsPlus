import { useEffect, useRef } from 'react';
import { useGuideTabContainer, GuideTabContainerType } from '@context/GuideTabContainerContext';
import { usePlusletWindow, PlusletWindowType } from '@context/PlusletWindowContext';
import { useFetchSubjectSpecialists } from '@hooks/useFetchSubjectSpecialists';
import { SubjectSpecialistForm } from './SubjectSpecialistForm';

type EditSubjectSpecialistProps = {
    extra?: Record<string, any>|null,
}

export const EditSubjectSpecialist = ({ extra }: EditSubjectSpecialistProps) => {
    const { subjectId } = useGuideTabContainer() as GuideTabContainerType;
    const { savePlusletCallback, isSaveRequested } = usePlusletWindow() as PlusletWindowType;
    const { isLoading, isError, data, error } = useFetchSubjectSpecialists(subjectId);

    const formRef = useRef<HTMLFormElement>(null);

    useEffect(() => {
        if (isSaveRequested) {
            console.log('formRef.current:', formRef.current);
            formRef.current?.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
        }
    }, [isSaveRequested]);

    const handleUpdate = (data: Record<string, Record<string, boolean>>) => {
        console.log('data:', data);
        // formulate the new extra field
        const newExtra = {};
    }

    if (isLoading) {
        return (<p>Loading Subject Specialists...</p>);
    } else if (isError) {
        console.error(error);
        return (<p>Error: Failed to load Subject Specialists through API Endpoint!</p>);
    } else if (data) {
        return (
            <SubjectSpecialistForm specialists={data} extra={extra} formRef={formRef} onSubmit={handleUpdate}/>
        );
    } else {
        return (<p>Error: No Subject Specialists exist for this guide!</p>);
    }
}