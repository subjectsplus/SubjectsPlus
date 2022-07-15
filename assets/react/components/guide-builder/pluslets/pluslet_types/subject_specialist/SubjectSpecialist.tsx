import { usePlusletWindow, PlusletWindowType } from '@context/PlusletWindowContext';
import { EditSubjectSpecialist } from './EditSubjectSpecialist';
import { ViewSubjectSpecialist } from './ViewSubjectSpecialist';

type SubjectSpecialistProps = {
    extra?: Record<string, any>|null,
}

export const SubjectSpecialist = ({ extra }: SubjectSpecialistProps) => {
    const { isEditMode } = usePlusletWindow() as PlusletWindowType;
    
    if (isEditMode) {
        return <EditSubjectSpecialist extra={extra} />
    } else {
        return <ViewSubjectSpecialist extra={extra} />
    }
}