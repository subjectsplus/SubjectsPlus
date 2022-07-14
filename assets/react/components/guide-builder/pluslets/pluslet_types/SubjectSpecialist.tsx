import { useGuideTabContainer, GuideTabContainerType } from '@context/GuideTabContainerContext';
import { useFetchSubjectSpecialists } from '@hooks/useFetchSubjectSpecialists';

type BasicPlusletProps = {
    extra?: string|null,
}

export const SubjectSpecialist = ({ extra }: BasicPlusletProps) => {
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
                {data.map(staff => (
                    <div key={'subject-specialist-' + staff.staffId} className="subject-specialist">
                        <p>Name: {staff.fname + ' ' + staff.lname}</p>
                        <p>Title: {staff.title}</p>
                        <p>Email: {staff.email}</p>
                    </div>
                ))}
            </div>
        );
    } else {
        return (<p>Error: No tabs exist for this guide!</p>);
    }
}