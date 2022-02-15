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
        // TODO: Translations for text below
        var resultsMessage = (
            <p className="fs-sm fst-italic">Please enter a search term (minimum 3 characters).</p>
        );
        var resultTokens = [];
        var  bottomElement = null;

        if (this.state.isErrored) resultsMessage = (
            <p className="fs-sm fst-italic">Error: Failed to reach API endpoint.</p>
        );
        
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
            if (resultTokens.length === 0) resultsMessage = (
                <p className="fs-sm fst-italic">No results found.</p>
            );
        }

        // Determine bottom element in results view
        if (this.state.loading) {
            bottomElement = (
            <p className="text-center fw-bold fst-italic">Loading...</p>
            );
        } else if (this.state.hasNextPage) {
            bottomElement = (
                <div className={`text-center my-2 ${resultTokens.length > 0 ? "d-block" : "d-none"}`}>
                    <button className="btn btn-pill load-more-button" onClick={this.loadNextPage}>
                        Load More
                    </button>
                </div>);
        } else if (this.state.inputEmpty) {
            bottomElement = null;
        } else {
            bottomElement = (
                <p className="text-center mt-2 fw-bold fst-italic">End of results</p>
            );
        }

        return (
            <div id={this.props.tokenType + '-search'}>
                {this.props.extras}
                <SearchBar id={this.props.tokenType + '-searchbar'} className="form-control"
                        placeholder={'Search ' + this.props.tokenType} onChange={this.onSearchInput} />
                <ul id={this.props.tokenType + '-list'} ref={this.listRef} className={`list-unstyled ${resultTokens.length > 0 ? "sp-search-results-panel-list" : ""}`}>
                    {resultTokens.length > 0 ? resultTokens : resultsMessage}
                    {bottomElement}
                </ul>
                <div className={`text-end ${resultTokens.length > 0 ? "d-block" : "d-none"}`}>
                    <button className="btn btn-link scroll-to-top" onClick={this.scrollToTop}>
                        <i className="fas fa-arrow-alt-circle-up"></i> Scroll To Top
                    </button>
                </div>
            </div>
        );
    }
}