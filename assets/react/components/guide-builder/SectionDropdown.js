import React from 'react';

function SectionDropdown({ isConvertingLayout, sectionDelete, convertLayout }) {

    const spinner = (
        <div className="spinner-border spinner-border-sm float-end" role="status">
            <span className="visually-hidden">Loading...</span>
        </div>
    );

    const sectionDropdownContent = (
        <div className="dropdown basic-dropdown">
            <button className="btn btn-muted dropdown-toggle sp-section-menu-btn" id="sectionMenuOptions" data-bs-toggle="dropdown" aria-expanded="false" title="Section options">
                <i className="fas fa-ellipsis-h"></i>
            </button>
            <ul className="dropdown-menu dropdown-arrow dropdown-menu-end fs-xs" aria-labelledby="sectionMenuOptions">
                {/* Change Section Layout */}
                <li><span className="dropdown-item-text fw-bold fs-sm">Layout</span>
                    <ul className="sp-section-layout-list">
                        {/*
                            sp-col-1 = 12-0-0 or 0-12-0
                            sp-col-2 = 6-6-0
                            sp-col-2-left-sidebar = 4-8-0 or 9-3-0
                            sp-col-2-right-sidebar = 8-4-0
                            sp-col-3 = 4-4-4
                            sp-col-3-sidebars = 3-6-3
                        */}
                        <li><a className="dropdown-item" onClick={() => convertLayout('0-12-0')}><span className="sp-col-1"></span></a></li>
                        <li><a className="dropdown-item" onClick={() => convertLayout('6-6-0')}><span className="sp-col-2"></span></a></li>
                        <li><a className="dropdown-item" onClick={() => convertLayout('4-8-0')}><span className="sp-col-2-left-sidebar"></span></a></li>
                        <li><a className="dropdown-item" onClick={() => convertLayout('8-4-0')}><span className="sp-col-2-right-sidebar"></span></a></li>
                        <li><a className="dropdown-item" onClick={() => convertLayout('4-4-4')}><span className="sp-col-3"></span></a></li>
                        <li><a className="dropdown-item" onClick={() => convertLayout('3-6-3')}><span className="sp-col-3-sidebars"></span></a></li>
                    </ul>
                </li>
                
                <li><hr className="dropdown-divider" /></li>

                {/* Delete Section */}
                <li><a className="dropdown-item delete-section" onClick={sectionDelete}><i
                    className="fas fa-trash"></i> Delete Section</a></li>
            </ul>
        </div>
    );
    
    if (isConvertingLayout) {
        return spinner;
    } else {
        return sectionDropdownContent;
    }
}

export default SectionDropdown;