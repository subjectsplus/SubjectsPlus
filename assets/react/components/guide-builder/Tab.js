import React, { Component } from 'react';
import Tabs from 'react-bootstrap/Tabs'

export class Tab extends Component {

    constructor(props) {
        super(props);
        
        this.state = {
            tabs: props.tabs,
        };
    }


    render() {

        var tabs = this.state.tabs.map((results, index) => {
            <Tab eventKey={results.tabId} title={results.label} />
        });

        <Tabs className="guide-tabs-container">
            {tabs}
        </Tabs>
    }
}