import React, { Component } from 'react';
import { htmlEntityDecode } from '#utility/Utility';
import Nav from 'react-bootstrap/Nav';
import { Draggable } from 'react-beautiful-dnd';

export default class DraggableTab extends Component {
    
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <Draggable type="tab" draggableId={'tab-' + this.props.tab.tabIndex} index={this.props.tab.tabIndex}>
                {(provided, snapshot) => {
                    return (
                        <Nav.Link as="div" ref={provided.innerRef} {...provided.draggableProps} {...provided.dragHandleProps}
                            eventKey={this.props.tab.tabIndex} tabIndex={this.props.tab.tabIndex}
                            active={this.props.active}>
                                {htmlEntityDecode(this.props.tab.label)}{' '}
                                {this.props.active && 
                                    <a href={void(0)} onClick={this.props.onClick} key={this.props.tab.tabId} className="tab-settings-icon">
                                        <i className="fas fa-cog"></i>
                                    </a>}
                        </Nav.Link>
                    )
                }}
            </Draggable>
        );
    }
}