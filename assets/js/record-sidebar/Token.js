import React, { Component } from 'react';

export default class Token extends Component {

    constructor(props) {
        super(props);
    }

    htmlEntityDecode(str) {
        if (typeof str !== 'string') return '';

        var doc = new DOMParser().parseFromString(str, 'text/html');
        return doc.body.textContent || '';
    }

    render() {
        return (
            <div className={this.props.tokenClassName} draggable="true"
                data-record-id={this.props.recordId} data-record-title={this.props.recordTitle}
                data-record-description={this.props.recordDescription} 
                data-record-location={this.props.recordLocation}>
                    {this.htmlEntityDecode(this.props.recordTitle)}
            </div>
        );
    }
}