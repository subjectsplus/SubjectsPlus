import { StaffType } from '@shared/types/staff_types';
import { htmlEntityDecode } from '@utility/Utility';

type SpecialistProfileProps = {
    staff: StaffType,
    flags: Record<string, boolean>
;}

export const SpecialistProfile = ({staff, flags}: SpecialistProfileProps) => {
    
    const SpecialistName = () => (
        <h4>{staff.fname + ' ' + staff.lname}</h4>
    );

    const SpecialistPhoto = () => {
        let sourceUrl = ''; // Change to a default image

        if (staff.staffPhoto) {
            const smallFileName = staff.staffPhoto.media.smallFileName;
            if (smallFileName) {
                sourceUrl = staff.staffPhoto.media.directory + '/' + smallFileName;
            } else {
                sourceUrl = staff.staffPhoto.media.directory + '/' + staff.staffPhoto.media.fileName;
            }
        }

        return (
            <img className="specialist-photo" src={sourceUrl} />
        );
    };

    return (
        <div className="specialists-info">
            {flags['showName'] && SpecialistName()}
            {flags['showPhoto'] && SpecialistPhoto()}
            <ul className="staff-details">
                {(flags['showTitle'] && staff.title) && <li>{ htmlEntityDecode(staff.title) }</li>}
                {(flags['showEmail'] && staff.email) && <li>{ staff.email }</li>}
                {(flags['showPhone'] && staff.tel) && <li>{ staff.tel }</li>}
            </ul>
        </div>
    )
}