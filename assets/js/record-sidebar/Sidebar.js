import React, { Component } from 'react';
import ReactDOM from 'react-dom'
import Token from './Token.js';
import SearchBar from './SearchBar.js';

export default class Sidebar extends Component {
    apiLink = '/api/titles?search={search_term}'

    constructor(props) {
        super(props);

        this.state = {
            records: [],
            previousInput: null,
            inputEmpty: true,
            azlist: true
        };

        this.onSearchInput = this.onSearchInput.bind(this);
        this.onAZListCheckBoxInput = this.onAZListCheckBoxInput.bind(this);
    }

    onSearchInput(evt) {
        var userInput = evt.target.value;
        if (typeof userInput === 'string' && userInput.length >= 3) {
            this.getRecords(userInput, this.state.azlist);
        } else {
            this.setState({inputEmpty: true});
        }
    }

    onAZListCheckBoxInput(evt) {
        var checked = evt.target.checked;
        this.setState({azlist: checked});
        
        if (!this.state.inputEmpty && this.state.previousInput) {
            this.getRecords(this.state.previousInput, checked);
        }
    }

    getRecords(search_term, use_az_list=true) {
        var resLink = this.apiLink.replace('{search_term}', search_term);
        if (use_az_list) {
            resLink += '&location.eresDisplay=Y';
        }

        $.getJSON(resLink).then((results) => {
            this.setState({
                inputEmpty: false, 
                previousInput: search_term, 
                records: results
            });
        });
    }

    render() {
        var resultsMessage = 'Please enter a search term. (Minimum 3 characters)';
        var recordTokens = [];

        if (!this.state.inputEmpty) {
            recordTokens = this.state.records.map( (record, index) => (
                <li key={record.titleId}>
                    <Token tokenClassName="record-token" recordId={record.titleId} recordTitle={record.title}
                        recordDescription={record.description} recordLocation={record.location[0].location} />
                </li>
            ));
            if (recordTokens.length === 0) resultsMessage = "No results found.";
        }

        return (
            <div id="records-sidebar">
                <h1>Get Records</h1>
                <SearchBar id="record-searchbar" className="sidebar-searchbar" 
                    placeholder="Search Records" onChange={this.onSearchInput} />
                <label>
                    <input id="azlist" name="azlist" type="checkbox" checked={this.state.azlist} 
                        onChange={this.onAZListCheckBoxInput} />
                     Limit to A-Z List
                </label>
                <ul id="records-list">
                    {recordTokens.length > 0 ? recordTokens : resultsMessage}
                </ul>
            </div>
        );
    }
}

ReactDOM.render(<Sidebar />, 
    document.getElementById('record-sidebar-container'));