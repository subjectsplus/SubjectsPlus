import React, { Component } from 'react';
import { DragDropContext } from 'react-beautiful-dnd';
import Section from './Section.js';

export default class SectionContainer extends Component {
    apiLink = '/api/tabs/{tabId}/sections';

    constructor(props) {
        super(props);

        this.state = {
            sections: null,
            isErrored: false
        };
    }

    getApiLink() {
        return this.apiLink.replace('{tabId}', 
            this.props.tabId);
    }

    getSections() {
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
                sections: results["hydra:member"],
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
        if (this.state.sections) {
            var guideSections = this.state.sections.map((result, index) => 
                <Section sectionId={result.sectionId} />)
            return (
                <div className="section-container">
                    {guideSections}
                </div> 
            );
        } else if (this.state.isErrored) {
            return (<p>Error: Failed to load sections through API Endpoint!</p>);
        } else {
            return (<p>Loading Sections...</p>);
        }
    }
}