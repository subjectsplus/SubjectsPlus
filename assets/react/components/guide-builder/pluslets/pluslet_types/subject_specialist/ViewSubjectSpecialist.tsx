import { useGuideTabContainer, GuideTabContainerType } from '@context/GuideTabContainerContext';
import { useFetchSubjectSpecialists } from '@hooks/useFetchSubjectSpecialists';
import { SpecialistProfile } from './SpecialistProfile';

type ViewSubjectSpecialistProps = {
    extra?: Record<string, any>|null,
}

export const ViewSubjectSpecialist = ({ extra }: ViewSubjectSpecialistProps) => {
    const { subjectId } = useGuideTabContainer() as GuideTabContainerType;
    const { isLoading, isError, data, error } = useFetchSubjectSpecialists(subjectId);

    if (isLoading) {
        return (<p>Loading Subject Specialists...</p>);
    } else if (isError) {
        console.error(error);
        return (<p>Error: Failed to load Subject Specialists through API Endpoint!</p>);
    } else if (data) {
        return (
            <div className="sp-pluslet-body">
                {extra && data.map(staff => {
                    if (extra[staff.staffId.toString()]) {
                        const flags = extra[staff.staffId.toString()];
                        return <SpecialistProfile key={'specialist-profile-' + staff.staffId} staff={staff} flags={flags} />
                    }
                })}
            </div>
        );
    } else {
        return (<p>Error: No subject specialists exist for this guide!</p>);
    }
}