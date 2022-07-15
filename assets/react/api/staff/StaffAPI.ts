import { StaffType } from '@shared/types/staff_types';
import { fetchGuide } from '@api/guide/GuideAPI';

export const fetchStaff = async (filters:Record<string, string>|URLSearchParams|null = null): Promise<StaffType[]> => {
    const data = await fetch(`/api/staff/`
        + (filters ? '?' + new URLSearchParams(filters) : ''), {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
    
    if (!data.ok) {
        throw new Error(data.status + ' ' + data.statusText);
    }

    return data.json();
}

export const fetchStaffMember = async (staffId: number): Promise<StaffType> => {
    const data = await fetch(`/api/staff/${staffId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    });

    if (!data.ok) {
        throw new Error(data.status + ' ' + data.statusText);
    }

    return data.json();
}

export const fetchSubjectSpecialists = async (subjectId: number): Promise<StaffType[]> => {
    const guideData = await fetchGuide(subjectId);
    const staffAPILinks = guideData.staff;

    if (staffAPILinks) {
        const staffIds = staffAPILinks.map(link => link.split('/').pop());
        
        const searchParams = new URLSearchParams();
        staffIds.forEach(id => typeof id !== 'undefined' && searchParams.append('staffId[]', id));
        
        const data = await fetch(`/api/staff/?` + searchParams.toString(), {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
    
        if (!data.ok) {
            throw new Error(data.status + ' ' + data.statusText);
        }

        return data.json();
    }

    return Promise.all([] as StaffType[]);
}