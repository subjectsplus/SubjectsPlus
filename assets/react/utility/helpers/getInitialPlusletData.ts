import { v4 as uuidv4 } from 'uuid';
import { PlusletType } from '@shared/types/guide_types';

export const getInitialPlusletData = (column: number, row: number, plusletType: string, subjectId: number, sectionUUID: string): PlusletType => {
    let initialPlusletData: PlusletType = {
        id: uuidv4(),
        title: '',
        type: plusletType,
        body: '',
        pcolumn: column,
        prow: row,
        section: '/api/sections/' + sectionUUID
    }

    if (plusletType === 'SubjectSpecialist') {
        initialPlusletData['title'] = 'Subject Specialist';
    }

    return initialPlusletData;
}