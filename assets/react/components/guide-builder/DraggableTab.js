import React, { Component } from 'react';
import Utility from '../../../backend/javascript/Utility/Utility.js';
import Nav from 'react-bootstrap/Nav';
import { Draggable } from 'react-beautiful-dnd';

export default class DraggableTab extends Component {
    
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <Draggable type="tab" draggableId={this.props.tab.tabId} index={this.props.tab.tabIndex}>
                {(provided, snapshot) => {
                    return (
                        <Nav.Link as="div" ref={provided.innerRef} {...provided.draggableProps} {...provided.dragHandleProps}
                            key={this.props.tab.tabId} eventKey={this.props.tab.tabIndex} tabIndex={this.props.tab.tabIndex}
                            active={this.props.active}>
                                {Utility.htmlEntityDecode(this.props.tab.label)}{' '}
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