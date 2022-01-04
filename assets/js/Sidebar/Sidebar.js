import React, { Component } from 'react';
import ReactDOM from 'react-dom'
import Token from './Token.js';
import SearchBar from './SearchBar.js';

export default class Sidebar extends Component {

    constructor(props) {
        super(props);

        this.state = {
            records: [],
            inputEmpty: true
        };

        this.onChange = this.onChange.bind(this);
    }

    onChange(evt) {
        var userInput = evt.target.value;
        if (typeof userInput === 'string' && userInput.length >= 3) {
            this.setState({inputEmpty: false});
            this.getRecords(userInput);
        } else {
            this.setState({inputEmpty: true});
        }
    }

    getRecords(search_term) {
        $.getJSON('/api/titles?search=' + search_term)
        .then((results) => {
            console.log(results);
            this.setState({records: results})
        });
    }

    render() {
        var resultsMessage = "Please enter a search term. (Minimum 3 characters)";
        var recordTokens = [];

        if (this.state.inputEmpty === false) {
            recordTokens = this.state.records.map( (record, index) => (
                <li key={record.titleId}>
                    <Token tokenType="record" tokenClassName="record-token" recordId={record.titleId} recordTitle={record.title}
                        recordDescription={record.description} recordLocation={record.location[0].location} />
                </li>
            ));
            if (recordTokens.length == 0) resultsMessage = "No results found.";
        }

        return (<div id="records-sidebar">
            <h1>Get Records</h1>
            <SearchBar id="record-searchbar" className="sidebar-searchbar" 
                placeholder="Search Records" onChange={this.onChange} />
            <ul id="records-list">
                {recordTokens.length > 0 ? recordTokens : resultsMessage}
            </ul>
        </div>);
    }
}

ReactDOM.render(<Sidebar />, 
    document.getElementById('record-sidebar-container'));