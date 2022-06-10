import React, { useEffect, useState, useRef, useMemo } from 'react';
import Token from './Token';
import SearchBar from './SearchBar';
import { useDebouncedCallback } from 'use-debounce';

function Search(props) {
    const [results, setResults] = useState([]);
    const [page, setPage] = useState(1);
    const [hasNextPage, setHasNextPage] = useState(false);
    const [previousInput, setPreviousInput] = useState(null);
    const [inputEmpty, setInputEmpty] = useState(true);
    const [isErrored, setIsErrored] = useState(false);
    const [loading, setLoading] = useState(false);

    const debouncedGetResults = useDebouncedCallback(
        (input, page=1, append=false) => getResults(input, page, append), 400);

    const listRef = useRef();

    useEffect(() => {
        if (props.refresh !== 0) refresh();
      }, [props.refresh]);

    const onSearchInput = (evt) => {
        const userInput = evt.target.value;
        if (typeof userInput === 'string' && userInput.length >= 3) {
            if (userInput !== previousInput) {
                setInputEmpty(false);
                setLoading(true);
                setResults([]);
                debouncedGetResults(userInput);
            }
        } else {
            setInputEmpty(true);
            setResults([]);
            setPreviousInput('');
            setPage(1);
        }
    }

    const getResults = (search_term, page=1, append=false) => {
        // formulate the results api link
        const resLink = props.apiLink(search_term, page);

        // only append results from subsequent pages
        if (page === 1) append = false;

        // fetch api results
        fetch(resLink).then(response => {
            if (response.ok) {
                return response.json();
            }

            setIsErrored(true);
            setLoading(false);
        })
        .then(latestResults => {
            setPreviousInput(search_term);
            setResults((append ? results.concat(latestResults['hydra:member']) : latestResults['hydra:member']));
            setPage(page);
            setHasNextPage((latestResults['hydra:view']['hydra:next'] != null));
            setIsErrored(false);
            setLoading(false);
        })
        .catch(err => {
            console.error(err);
            setIsErrored(true);
            setLoading(false);
        })
    }

    const refresh = () => {
        setLoading(true);
        setResults([]);
        debouncedGetResults(previousInput);
    }

    const loadNextPage = () => {
        setLoading(true);
        getResults(previousInput, page + 1, true);
    }

    const scrollToTop = () => {
        listRef.current.scrollTop = 0;
    }

    const pasteToCKEditor = evt => {
        if (CKEDITOR?.instances['pluslet_ckeditor']) {
            CKEDITOR.instances['pluslet_ckeditor'].insertHtml(evt.target.outerHTML);
        } else if (CKEDITOR?.instances['faq_answer']) {
            CKEDITOR.instances['faq_answer'].insertHtml(evt.target.outerHTML);
        }
    }

    const resultsMessage = useMemo(() => {
        if (isErrored) {
            return (
                <p className="fs-sm fst-italic">Error: Failed to reach API endpoint.</p>
            );
        } else if (inputEmpty) {
            return (
                <p className="fs-sm fst-italic">Please enter a search term (minimum 3 characters).</p>
            );
        } else if (results.length > 0) {
            const resultTokens = results.map(result => (
                <li key={result['@id']}>
                    <Token tokenType={props.tokenType} token={result} onClick={pasteToCKEditor} />
                </li>
            ));

            return (
                <>
                    <p className="fs-sm fst-italic"><b>Click</b> or <b>drag</b> into box.</p>
                    {resultTokens}
                </>
            );
        } else if (loading) {
            return null;
        } else {
            return (
                <p className="fs-sm fst-italic">No results found.</p>
            );
        }
    }, [isErrored, inputEmpty, loading, results]);

    const bottomElement = useMemo(() => {
        if (loading) {
            return (
                <p className="text-center fw-bold fst-italic">Loading...</p>
            );
        } else if (hasNextPage) {
            return (
                <div className={`text-center my-2 ${results.length > 0 ? "d-block" : "d-none"}`}>
                    <button className="btn btn-pill load-more-button" onClick={loadNextPage}>
                        Load More
                    </button>
                </div>);
        } else if (inputEmpty) {
            return null;
        } else {
            return (
                <p className="text-center mt-2 fw-bold fst-italic">End of results</p>
            );
        }
    }, [loading, hasNextPage, inputEmpty]);

    return (
        <div id={props.tokenType + '-search'}>
            {props.extras}
            <SearchBar id={props.tokenType + '-searchbar'} className="form-control"
                    placeholder={props.placeholder ?? ('Search ' + props.tokenType)} onChange={onSearchInput} />
            <ul id={props.tokenType + '-list'} ref={listRef} className={`list-unstyled ${results.length > 0 ? "sp-search-results-panel-list" : ""}`}>
                {resultsMessage}
                {bottomElement}
            </ul>
            <div className={`text-end ${results.length > 0 ? "d-block" : "d-none"}`}>
                <button className="btn btn-link scroll-to-top" onClick={scrollToTop}>
                    <i className="fas fa-arrow-alt-circle-up"></i> Scroll To Top
                </button>
            </div>
        </div>
    );
}

export default Search;