import React, { Component } from 'react';
import ReactDOM from 'react-dom';

console.log('record component container');

export default class RecordIndexContainer extends Component {

    constructor(props) {
        super(props);

        this.state = {
            results : [],
            isErrored : false
        }
    }

    componentDidMount() {
        this.getResults();
    }

    getResults() {
        var apiLink = '/api/titles?pagination=true';

        // fetch api results
        fetch(apiLink).then(response => {
            if (response.ok) {
                return response.json();
            }

            this.setState({
                isErrored: true

            });
        })
        .then(results => {
            this.setState({
                results: results['hydra:member']
            })
        })
        .catch(err => {
            console.error(err);
            this.setState({
                isErrored: true
            });
        });
    }

    render() {
        // let resultItems = [];
        //
        // if(this.state.results) {
        //     resultItems = this.state.results.map( (result, index) => {
        //         return (
        //             <li key={result.titleId}>
        //                 {result.title}
        //             </li>
        //         );
        //     });
        //     return (
        //         <div>
        //             <ul>
        //                 {resultItems}
        //             </ul>
        //         </div>
        //     );
        // } else {
        //     return (<p>No results</p>);
        // }
    }
}
//ReactDOM.render(<RecordIndexContainer />, document.getElementById('SearchBar'));