import React, { Component } from 'react';
import ReactDOM from 'react-dom'
import InfiniteScroll from 'react-infinite-scroll-component';
import Token from '../../shared/Token.js';
import SearchBar from '../../shared/SearchBar.js';

export default class Sidebar extends Component {
    apiLink = '/api/titles';

    constructor(props) {
        super(props);

        this.state = {
            records: [],
            page: 1,
            hasNextPage: false,
            previousInput: null,
            inputEmpty: true,
            azlist: true,
            isErrored: false
        };

        this.onSearchInput = this.onSearchInput.bind(this);
        this.onAZListCheckBoxInput = this.onAZListCheckBoxInput.bind(this);
        this.loadNextPage = this.loadNextPage.bind(this);
    }

    onSearchInput(evt) {
        var userInput = evt.target.value;
        if (typeof userInput === 'string' && userInput.length >= 3) {
            if (userInput === this.state.previousInput) {
                this.getRecords(userInput, this.state.page, this.state.azlist);
            } else {
                this.getRecords(userInput, 1, this.state.azlist);
            }
        } else {
            this.setState({inputEmpty: true, page: 1});
        }
    }

    onAZListCheckBoxInput(evt) {
        var checked = evt.target.checked;
        this.setState({azlist: checked});
        
        if (!this.state.inputEmpty && this.state.previousInput) {
            this.getRecords(this.state.previousInput, 1, checked);
        }
    }

    getRecords(search_term, page=1, use_az_list=true, append=false) {
        // formulate the results api link
        var resLink = this.apiLink + '?' + new URLSearchParams({
            search: search_term,
            'location.eresDisplay': (use_az_list ? 'Y' : 'N'),
            page: page
        });

        // only append results from subsequent pages
        if (page === 1) append = false;

        fetch(resLink).then(response => {
            if (response.ok) {
                return response.json();
            }

            this.setState({
                isErrored: true
            });
        })
        .then(results => {
            console.log(results['hydra:member']);
            console.log(results['hydra:view']);
            console.log(results['hydra:view']['hydra:next']);
            console.log((results['hydra:view']['hydra:next'] != null));

            this.setState({
                inputEmpty: false, 
                previousInput: search_term, 
                records: (append ? this.state.records.concat(results['hydra:member']) : results['hydra:member']),
                page: page,
                hasNextPage: (results['hydra:view']['hydra:next'] != null),
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

    loadNextPage() {
        this.getRecords(this.state.previousInput, this.state.page + 1, this.state.azlist, true);
    }

    render() {
        var resultsMessage = 'Please enter a search term. (Minimum 3 characters)';
        var recordTokens = [];

        if (this.state.isErrored) resultsMessage = "Error: Failed to reach API endpoint.";

        if (!this.state.inputEmpty && !this.state.isErrored) {
            recordTokens = this.state.records.map( (record, index) => (
                <li key={record.titleId}>
                    <Token tokenClassName="record-token" recordId={record.titleId} recordTitle={record.title}
                        recordDescription={record.description} recordLocation={record.location[0].location} />
                </li>
            ));
            if (recordTokens.length === 0) resultsMessage = "No results found.";
        }

        return (
            <div id="record-search">
                <h1>Get Records</h1>
                <SearchBar id="record-searchbar" className="sidebar-searchbar" 
                    placeholder="Search Records" onChange={this.onSearchInput} />
                <label>
                    <input id="azlist" name="azlist" type="checkbox" checked={this.state.azlist} 
                        onChange={this.onAZListCheckBoxInput} />
                     Limit to A-Z List
                </label>
                <ul id="records-list">
                    <InfiniteScroll
                        dataLength={this.state.records.length} //This is important field to render the next data
                        next={this.loadNextPage}
                        hasMore={this.state.hasNextPage}
                        loader={<h4>Loading...</h4>}
                        endMessage={
                            <p style={{ textAlign: 'center' }}>
                            <b>End of results.</b>
                            </p>
                        }
                        scrollableTarget="records-list">
                        {recordTokens.length > 0 ? recordTokens : resultsMessage}
                    </InfiniteScroll>
                </ul>
            </div>
        );
    }
}

ReactDOM.render(<Sidebar />,
    document.getElementById('record-search-container'));