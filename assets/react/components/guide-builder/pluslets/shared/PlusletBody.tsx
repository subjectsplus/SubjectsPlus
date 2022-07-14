import { BasicPluslet } from '../pluslet_types/BasicPluslet';
import { SubjectSpecialist } from '../pluslet_types/SubjectSpecialist';
import { PlusletType } from '@shared/types/guide_types';

type PlusletBodyProps = {
    pluslet: PlusletType,
    isDragging: boolean
} 

export const PlusletBody = ({ pluslet, isDragging }: PlusletBodyProps) => {
    if (isDragging) {
        return <div className="visually-hidden" />
    } else if (pluslet.type === 'SubjectSpecialist') { 
        return <SubjectSpecialist extra={pluslet.extra} />
    } else {
        return <BasicPluslet plusletBody={pluslet.body} />
    }
}