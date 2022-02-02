import React, { Component } from 'react';

export class Guide extends Component {
    apiLink = 'api/subjects/';

    constructor(props) {
        this.state = {
            guide: null,
            isErrored: false
        }
    }

    getAPILink() {
        return this.apiLink + this.props.guideId;
    }

    getGuide() {
        // formulate the results api link for guide
        var resLink = getAPILink();

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
                isErrored: false
            });
        }
        )
        .catch(err => {
            console.error(err);
            this.setState({
                isErrored: true,
            });
        });
    }

    render() {
        if (this.state.guide) {
            <p>{'Subject:' + this.state.guide.subject}</p>
        }
    }
}