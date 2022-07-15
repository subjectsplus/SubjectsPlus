import { useQuery } from 'react-query';
import { fetchSubjectSpecialists } from '@api/staff/StaffAPI';
import { StaffType } from '@shared/types/staff_types';

export const useFetchSubjectSpecialists = (subjectId: number) => {
    return useQuery<StaffType[], Error>(['subject_specialists', subjectId], 
        () => fetchSubjectSpecialists(subjectId), {
            staleTime: 5000
        }
    );
}