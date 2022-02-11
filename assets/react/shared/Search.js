import React, { Component } from 'react';
import Token from './Token.js';
import SearchBar from './SearchBar.js';

export default class Search extends Component {
    constructor(props) {
        super(props);

        this.state = {
            results: [],
            page: 1,
            hasNextPage: false,
            previousInput: null,
            inputEmpty: true,
            isErrored: false,
            loading: false
        };

        this.listRef = React.createRef();

        this.onSearchInput = this.onSearchInput.bind(this);
        this.loadNextPage = this.loadNextPage.bind(this);
        this.scrollToTop = this.scrollToTop.bind(this);
    }

    onSearchInput(evt) {
        var userInput = evt.target.value;
        if (typeof userInput === 'string' && userInput.length >= 3) {
            if (userInput !== this.state.previousInput) {
                this.getResults(userInput, 1);
            }
        } else {
            this.setState({inputEmpty: true, page: 1});
        }
    }

    getResults(search_term, page=1, append=false) {
        // formulate the results api link
        var resLink = this.props.apiLink(search_term, page);

        // only append results from subsequent pages
        if (page === 1) append = false;

        // fetch api results
        fetch(resLink).then(response => {
            if (response.ok) {
                return response.json();
            }

            this.setState({
                isErrored: true,
                loading: false
            });
        })
        .then(results => {
            this.setState({
                inputEmpty: false, 
                previousInput: search_term, 
                results: (append ? this.state.results.concat(results['hydra:member']) : results['hydra:member']),
                page: page,
                hasNextPage: (results['hydra:view']['hydra:next'] != null),
                isErrored: false,
                loading: false
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

    refresh() {
        this.getResults(this.state.previousInput);
    }

    loadNextPage() {
        this.setState({loading: true},
            this.getResults(this.state.previousInput, this.state.page + 1, true));
    }

    scrollToTop(evt) {
        this.listRef.current.scrollTop = 0;
    }

    render() {
        var resultsMessage = 'Please enter a search term. (Minimum 3 characters)';
        var resultTokens = [];
        var bottomElement = (
            <p style={{ textAlign: 'center' }}>
                <b>End of results.</b>
            </p>);

        if (this.state.isErrored) resultsMessage = "Error: Failed to reach API endpoint.";
        
        // Turn the current results into token components
        if (!this.state.inputEmpty && !this.state.isErrored) {
            resultTokens = this.state.results.map( (result, index) => {
                if (this.props.tokenType === 'record') {
                    return (
                        <li key={result.titleId}>
                            <Token tokenType="record" recordId={result.titleId} recordTitle={result.title}
                                recordDescription={result.description} recordLocation={result.location[0].location} />
                        </li>
                    )}
            });
            if (resultTokens.length === 0) resultsMessage = "No results found.";
        }

        // Determine bottom element in results view
        if (this.state.loading) {
            bottomElement = (
            <p style={{ textAlign: 'center' }}>
                <b>Loading...</b>
            </p>);
        } else if (this.state.hasNextPage) {
            bottomElement = (
                <button className="load-more-button" onClick={this.loadNextPage}>
                    Load More
                </button>);
        }

        return (
            <div id={this.props.tokenType + '-search'}>
                {this.props.extras}
                <SearchBar id={this.props.tokenType + '-searchbar'} className="form-control"
                        placeholder={'Search ' + this.props.tokenType} onChange={this.onSearchInput} />
                <ul id={this.props.tokenType + '-list'} ref={this.listRef} className="list-unstyled sp-search-results-panel-list">
                    {resultTokens.length > 0 ? resultTokens : resultsMessage}
                    {bottomElement}
                </ul>
                <button className="scroll-to-top" onClick={this.scrollToTop}>
                    Scroll To Top
                </button>
            </div>
        );
    }
}