import Form from 'react-bootstrap/Form';
import { StaffType } from '@shared/types/staff_types';

type SubjectSpecialistFormProps = {
    specialists: StaffType[],
    extra?: Record<string, any>|null,
};

const flags = ['showName', 'showTitle', 'showEmail', 'showPhone'];

export const SubjectSpecialistForm = ({ specialists, extra }: SubjectSpecialistFormProps) => {
    return (
        <Form noValidate={true} id="specialist-form">
           {specialists.map(specialist => (
                <div key={'specialist-info-edit-' + specialist.staffId} className="specialist-info-edit">
                    <h4>{specialist.fname + ' ' + specialist.lname}</h4>
                    {flags.map(flag => {
                        return (
                            <Form.Check type="switch"
                                key={'specialist-' + specialist.staffId + '-' + flag + '-switch'}
                                id={'specialist-' + specialist.staffId + '-' + flag + '-switch'}
                                label={flag} />
                        );
                    })}
                </div>
           ))}
        </Form>
    );
}