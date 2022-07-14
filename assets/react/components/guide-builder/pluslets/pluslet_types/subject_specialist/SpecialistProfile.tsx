import { StaffType } from '@shared/types/staff_types';

type SpecialistProfileProps = {
    staff: StaffType,
    flags: Record<string, boolean>
;}

export const SpecialistProfile = ({staff, flags}: SpecialistProfileProps) => {
    return (
        <div className="specialists-info">
            {flags['showName'] && <h4>{staff.fname + ' ' + staff.lname}</h4>}
            <ul className="staff-details">
                {(flags['showTitle'] && staff.title) && <li>{ staff.title }</li>}
                {(flags['showEmail'] && staff.email) && <li>{ staff.email }</li>}
                {(flags['showPhone'] && staff.tel) && <li>{ staff.tel }</li>}
            </ul>
        </div>
    )
}