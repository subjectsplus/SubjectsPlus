import { BasicPluslet } from '../pluslet_types/BasicPluslet';
import { PlusletType } from '@shared/types/guide_types';

type PlusletBodyProps = {
    pluslet: PlusletType,
    isDragging: boolean,
    savePlusletCallback: (data: object, toggleEditMode?: boolean) => void
} 

export const PlusletBody = ({ pluslet, isDragging, savePlusletCallback }: PlusletBodyProps) => {
    if (isDragging) {
        return <div className="visually-hidden" />
    } else {
        return <BasicPluslet plusletBody={pluslet.body} savePlusletCallback={savePlusletCallback} />
    }
}