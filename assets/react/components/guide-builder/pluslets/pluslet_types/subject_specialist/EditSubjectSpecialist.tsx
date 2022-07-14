import { useGuideTabContainer, GuideTabContainerType } from '@context/GuideTabContainerContext';
import { useFetchSubjectSpecialists } from '@hooks/useFetchSubjectSpecialists';
import { SubjectSpecialistForm } from './SubjectSpecialistForm';

type EditSubjectSpecialistProps = {
    extra?: Record<string, any>|null,
}

export const EditSubjectSpecialist = ({ extra }: EditSubjectSpecialistProps) => {
    const { subjectId } = useGuideTabContainer() as GuideTabContainerType;
    const { isLoading, isError, data, error } = useFetchSubjectSpecialists(subjectId);

    if (isLoading) {
        return (<p>Loading Subject Specialists...</p>);
    } else if (isError) {
        console.error(error);
        return (<p>Error: Failed to load Subject Specialists through API Endpoint!</p>);
    } else if (data) {
        return (
            <SubjectSpecialistForm specialists={data} extra={extra} />
        );
    } else {
        return (<p>Error: No tabs exist for this guide!</p>);
    }
}