import React from 'react';
import { htmlEntityDecode } from '#utility/Utility';
import Nav from 'react-bootstrap/Nav';
import { Draggable } from 'react-beautiful-dnd';

function DraggableTab({ tab, active, onClick }) {
    return (
        <Draggable type="tab" draggableId={'tab-' + tab.tabIndex} index={tab.tabIndex}>
            {(provided, snapshot) => {
                return (
                    <Nav.Link as="div" ref={provided.innerRef} {...provided.draggableProps} {...provided.dragHandleProps} className="fs-sm"
                        eventKey={tab.tabIndex} tabIndex={tab.tabIndex}
                        active={active}>
                            {htmlEntityDecode(tab.label)}{' '}
                            {active && 
                                <a href={void(0)} onClick={onClick} key={tab.tabId} className="tab-settings-icon" title="Edit Tab">
                                    <i className="fas fa-cog"></i>
                                </a>}
                    </Nav.Link>
                )
            }}
        </Draggable>
    );
}

export default DraggableTab;