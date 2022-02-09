import React, { Component } from 'react';
import Tabs from 'react-bootstrap/Tabs'
import Tab from 'react-bootstrap/Tab'
import Utility from '../../../js/Utility/Utility.js';
import SectionContainer from './SectionContainer.js';

export default class GuideTabContainer extends Component {
    apiLink = '/api/subjects/{subjectId}/tabs';

    constructor(props) {
        super(props);
        
        this.state = {
            tabs: null,
            isErrored: false
        };
    }

    componentDidMount() {
        this.getTabs();
    }

    getAPILink() {
        return this.apiLink.replace('{subjectId}', 
            this.props.guideId);
    }

    getTabs() {
        // formulate the results api link for guide
        var resLink = this.getAPILink();

        // fetch api results
        fetch(resLink).then(response => {
            if (response.ok) {
                return response.json();
            }

            this.setState({
                isErrored: true
            });
        })
        .then(results => {
            this.setState({
                tabs: results["hydra:member"],
                isErrored: false
            });
        }
        )
        .catch(err => {
            console.error(err);
            this.setState({
                isErrored: true
            });
        });
    }

    render() {
        if (this.state.tabs) {
            var guideTabs = this.state.tabs.map((results, index) => {
                return (
                    <Tab key={results.tabId} eventKey={results.tabIndex} 
                        title={Utility.htmlEntityDecode(results.label)}>
                        <SectionContainer tabId={results.tabId} />
                    </Tab>
                );
            });
            console.log(guideTabs);
            return (
                <Tabs defaultActiveKey="0" id="guide-tabs-container">
                    {guideTabs}
                </Tabs>
            );
        } else if (this.state.isErrored) {
            return (<p>Error: Failed to load tabs through API Endpoint!</p>);
        } else {
            return (<p>Loading Tabs...</p>);
        }
    }
}