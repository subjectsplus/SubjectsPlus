import React, { Component } from 'react';
import { Draggable } from 'react-beautiful-dnd';

export default class Pluslet extends Component {

    constructor(props) {
        super(props);
    }

    render() {
        return (
            <Draggable type="pluslet" key={this.props.plusletId.toString()} draggableId={this.props.plusletId.toString()} index={this.props.plusletRow}>
                {(provided, snapshot) => {
                    return (
                        <div className="pluslet" key={this.props.plusletId} ref={provided.innerRef} {...provided.draggableProps} 
                            {...provided.dragHandleProps}
                            style={{
                                ...provided.draggableProps.style,
                                border: '2px dashed blue',
                                height: '200px',
                                width: '150px',
                            }}>
                                {'Pluslet ' + this.props.plusletId}
                        </div>
                    );
                }}
            </Draggable>
        );
    }
}