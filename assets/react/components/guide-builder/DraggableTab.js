import { htmlEntityDecode } from '#utility/Utility';
import Nav from 'react-bootstrap/Nav';
import { Draggable } from 'react-beautiful-dnd';

function DraggableTab({ tabId, tabIndex, label, active, onClick }) {
    return (
        <Draggable type="tab" draggableId={'tab-' + tabIndex} index={tabIndex}>
            {(provided, snapshot) => {
                return (
                    <Nav.Link as="div" ref={provided.innerRef} {...provided.draggableProps} {...provided.dragHandleProps} className="fs-sm"
                        eventKey={tabIndex} tabIndex={tabIndex}
                        active={active}>
                            {htmlEntityDecode(label)}{' '}
                            {active && 
                                <a href={void(0)} onClick={onClick} key={tabId} className="tab-settings-icon" title="Edit Tab">
                                    <i className="fas fa-cog"></i>
                                </a>}
                    </Nav.Link>
                )
            }}
        </Draggable>
    );
}

export default DraggableTab;