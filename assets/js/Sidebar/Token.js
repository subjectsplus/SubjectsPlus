import React, { Component } from 'react';

export default class Token extends Component {

    render() {
        if (this.props.tokenType === 'record') {
            return (
            <div className={this.props.tokenClassName} style={{overflow: 'hidden', whiteSpace: 'nowrap', textOverflow: 'ellipsis' }} draggable="true"
                data-record-id={this.props.recordId} data-record-title={this.props.recordTitle}
                data-record-description={this.props.recordDescription} 
                data-record-location={this.props.recordLocation}>
                    {this.props.recordTitle}
            </div>);
        }
    }
}