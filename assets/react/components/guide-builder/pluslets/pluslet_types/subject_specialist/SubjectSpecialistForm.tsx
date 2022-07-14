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
                <Form.Group key={'specialist-info-edit-' + specialist.staffId} className="mb-3" controlId={'form-specialist-' + specialist.staffId}>
                    <h4>{specialist.fname + ' ' + specialist.lname}</h4>
                    {flags.map(flag => {
                        let flagValue = false;
                        if (extra && extra[specialist.staffId.toString()] && extra[specialist.staffId.toString()][flag]) {
                            flagValue = extra[specialist.staffId.toString()][flag];
                        }

                        return (
                            <Form.Check type="switch"
                                key={'specialist-' + specialist.staffId + '-' + flag + '-switch'}
                                id={'specialist-' + specialist.staffId + '-' + flag + '-switch'}
                                checked={flagValue} label={flag} />
                        );
                    })}
                </Form.Group>
           ))}
        </Form>
    );
}