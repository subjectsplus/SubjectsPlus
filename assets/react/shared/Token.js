import React, { Component } from 'react';
import Utility from '../../js/Utility/Utility.js';

export default class Token extends Component {

    constructor(props) {
        super(props);
    }

    render() {
        if (this.props.tokenType === 'record') {
            return (
                <div className="record-token" draggable="true"
                    data-record-id={this.props.recordId} data-record-title={this.props.recordTitle}
                    data-record-description={this.props.recordDescription} 
                    data-record-location={this.props.recordLocation}>
                        {Utility.htmlEntityDecode(this.props.recordTitle)}
                </div>
            );
        }
    }
}