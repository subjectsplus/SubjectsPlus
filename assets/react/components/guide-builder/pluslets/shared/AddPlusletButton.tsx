import { useState, useRef } from 'react';

type AddPlusletButtonProps = {
    addPlusletCallback: (plusletType: string) => void
};

export const AddPlusletButton = ({ addPlusletCallback }: AddPlusletButtonProps) => {
    const [addPlusletHovered, setAddPlusletHovered] = useState<boolean>(false);

    const dropdownRef = useRef<HTMLUListElement>(null);

    const isActiveDropdown = dropdownRef?.current?.classList ? dropdownRef.current.classList.contains('show') : false;

    return (
        <div className="text-center mt-2">
            <div className="dropdown basic-dropdown">
                <button
                    id="addPlusletOptions"
                    className="btn btn-muted p-1 dropdown-toggle"
                    data-bs-toggle="dropdown" 
                    aria-expanded="false"
                    title="Add Box"
                    onMouseEnter={e => {
                        setAddPlusletHovered(true);
                    }}
                    onMouseLeave={e => {
                        setAddPlusletHovered(false);
                    }}
                >
                    <i className="fas fa-plus-circle d-block"></i>
                    <span className={'fs-xs' + ((addPlusletHovered || isActiveDropdown) ? '' : ' invisible')}>
                            Add Box
                    </span>
                </button>
                <ul ref={dropdownRef} className="dropdown-menu dropdown-arrow fs-xs text-center" aria-labelledby="addPlusletOptions">
                    <li><a className="dropdown-item" onClick={() => addPlusletCallback('Basic')}>Basic</a></li>
                    <li><a className="dropdown-item" onClick={() => addPlusletCallback('SubjectSpecialist')}>Subject Specialist</a></li>
                </ul>
            </div>
        </div>
    );
}