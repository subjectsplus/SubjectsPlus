import { BasicPluslet } from '../pluslet_types/BasicPluslet';
import { PlusletType } from '@shared/types/guide_types';

type PlusletBodyProps = {
    pluslet: PlusletType,
    isDragging: boolean
} 

export const PlusletBody = ({ pluslet, isDragging }: PlusletBodyProps) => {
    if (isDragging) {
        return <div className="visually-hidden" />
    } else {
        return <BasicPluslet plusletBody={pluslet.body} />
    }
}