import React, { Component } from 'react';

export default class Section extends Component {
    apiLink = '/api/sections/{sectionId}/pluslets';

    constructor(props) {
        super(props);

        this.state = {
            pluslets: null,
            isErrored: false
        };
    }

    getApiLink() {
        return this.apiLink.replace('{sectionId}', 
            this.props.sectionId);
    }

    getPluslets() {
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
                pluslets: results["hydra:member"],
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
        // if (this.state.sections) {
        return (
            <div className="guide-section">
                Section { this.props.sectionId}
            </div>
        );
        // } else if (this.state.isErrored) {
        //     return (<p>Error: Failed to load sections through API Endpoint!</p>);
        // } else {
        //     return (<p>Loading Sections...</p>);
        // }
    }
}