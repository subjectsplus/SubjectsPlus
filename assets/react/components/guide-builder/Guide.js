import React, { Component } from 'react';
import GuideTabContainer from './GuideTabContainer.js';
import Utility from '../../../backend/javascript/Utility/Utility.js';

export default class Guide extends Component {
    apiLink = '/api/subjects/';

    constructor(props) {
        super(props);

        this.state = {
            guide: null,
            loading: true,
            isErrored: false
        }
    }

    componentDidMount() {
        this.getGuide();
    }

    getAPILink() {
        return this.apiLink + this.props.guideId;
    }

    getGuide() {
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
                guide: results,
                loading: false,
                isErrored: false
            });
        }
        )
        .catch(err => {
            console.error(err);
            this.setState({
                isErrored: true,
                loading: false
            });
        });
    }

    render() {
        if (this.state.loading) {
            return (<p>Loading Guide...</p>);
        } else if (this.state.guide) {
            return (
                <div id="guide-builder">
                    <h3>{Utility.htmlEntityDecode(this.state.guide.subject)}</h3>
                    <GuideTabContainer guideId={this.props.guideId}/>
                </div>
            );
        } else {
            return (<p>Guide not found!</p>);
        }
    }
}