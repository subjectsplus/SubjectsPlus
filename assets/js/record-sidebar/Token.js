import React, { Component } from 'react';

export default class Token extends Component {

    constructor(props) {
        super(props);
    }

    render() {
        return (
            <div className={this.props.tokenClassName} draggable="true"
                data-record-id={this.props.recordId} data-record-title={this.props.recordTitle}
                data-record-description={this.props.recordDescription} 
                data-record-location={this.props.recordLocation}>
                    {this.props.recordTitle}
            </div>
        );
    }
}