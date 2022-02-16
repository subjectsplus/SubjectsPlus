import React, { Component } from 'react';
import Tabs from 'react-bootstrap/Tabs'
import Tab from 'react-bootstrap/Tab'
import Button from 'react-bootstrap/Button'
import Utility from '../../../js/Utility/Utility.js';
import SectionContainer from './SectionContainer.js';

export default class GuideTabContainer extends Component {
    apiLink = '/api/subjects/{subjectId}/tabs';
    postLink = '/api/tabs';

    constructor(props) {
        super(props);
        
        this.state = {
            tabs: null,
            lastTabIndex: 0,
            activeKey: 0,
            isErrored: false
        };

        this.onTabSelect = this.onTabSelect.bind(this);
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
                tabs: results['hydra:member'],
                lastTabIndex: results['hydra:member'].at(-1)['tabIndex'],
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

    onTabSelect(eventKey) {
        console.log(eventKey);
        if (eventKey === 'new-tab') {
            // Create new tab
            this.newTab();
        } else if (this.state.activeKey !== eventKey) {
            this.setState({
                activeKey: eventKey
            });
        }
    }

    newTab() {
        let initialTabData = {
            label: 'Untitled',
            tabIndex: this.state.lastTabIndex + 1,
            visibility: true,
            subject: '/api/subjects/' + this.props.guideId
        };

        fetch(this.postLink, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(initialTabData)
        })
        .then(response => response.json())
        .then(data => {
            this.setState({
                tabs: [...this.state.tabs, data],
                lastTabIndex: data.tabIndex,
                activeKey: data.tabIndex
            });
            console.log('Success:', data);
        })
        .catch((error) => {
            alert('Error: Failed to add new tab.');
            console.error('Error:', error);
        });
    }

    deleteTab(tabId) {

    }

    render() {
        if (this.state.tabs) {
            let guideTabs = this.state.tabs.map((results, index) => {
                return (
                    <Tab key={results.tabId} eventKey={results.tabIndex} 
                        title={
                            <>
                                {Utility.htmlEntityDecode(results.label)}{' '}
                                <Button key={'-tab-settings-' + results.tabId} className="tab-settings-button"
                                    variant="outline-primary" size="sm">
                                    <i className="fas fa-cog"></i>
                                </Button>
                            </>
                        }>
                        <SectionContainer tabId={results.tabId} />
                    </Tab>
                );
            });
            console.log(guideTabs);
            return (
                <div id="guide-tabs-container">
                    <Tabs activeKey={this.state.activeKey} id="guide-tabs" onSelect={this.onTabSelect}>
                        {guideTabs}
                        <Tab key="new-tab" eventKey="new-tab" 
                            title={<i className="fas fa-plus"></i>} 
                        />
                    </Tabs>
                </div>
            );
        } else if (this.state.isErrored) {
            return (<p>Error: Failed to load tabs through API Endpoint!</p>);
        } else {
            return (<p>Loading Tabs...</p>);
        }
    }
}