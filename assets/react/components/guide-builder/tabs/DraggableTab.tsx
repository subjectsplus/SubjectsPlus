import { htmlEntityDecode } from '@utility/Utility';
import Nav from 'react-bootstrap/Nav';
import { Draggable } from 'react-beautiful-dnd';

type DraggableTabProps = {
    tabId: string,
    tabIndex: number,
    label: string,
    active: boolean,
    settingsButtonOnClick: React.MouseEventHandler<HTMLAnchorElement>
}
export const DraggableTab = ({ tabId, tabIndex, label, active, settingsButtonOnClick }: DraggableTabProps) => {
    return (
        <Draggable draggableId={'tab-' + tabIndex} index={tabIndex}>
            {(provided, snapshot) => {
                return (
                    <Nav.Link as="div" ref={provided.innerRef} {...provided.draggableProps} {...provided.dragHandleProps} className="fs-sm"
                        eventKey={tabIndex} tabIndex={tabIndex}
                        active={active}>
                            {htmlEntityDecode(label)}{' '}
                            {active && 
                                <a href={void(0)} onClick={settingsButtonOnClick} key={tabId} className="tab-settings-icon" title="Edit Tab">
                                    <i className="fas fa-cog"></i>
                                </a>}
                    </Nav.Link>
                )
            }}
        </Draggable>
    );
}

export default DraggableTab;