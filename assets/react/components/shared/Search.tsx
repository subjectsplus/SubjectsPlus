import { useEffect, useState, useRef, useMemo } from 'react';
import { Token } from './token/Token';
import { SearchBar } from './SearchBar';
import { useDebouncedCallback } from 'use-debounce';

type SearchProps = {
    tokenType: string,
    refresh: number,
    apiLink: (arg0: string, arg1: number) => string,
    placeholder?: string,
    extras?: JSX.Element
}

function Search({tokenType, refresh, apiLink, placeholder, extras}: SearchProps) {
    const [results, setResults] = useState<Array<Record<string, any>>>([]);
    const [page, setPage] = useState<number>(1);
    const [hasNextPage, setHasNextPage] = useState<boolean>(false);
    const [previousInput, setPreviousInput] = useState<string|null>(null);
    const [inputEmpty, setInputEmpty] = useState<boolean>(true);
    const [isErrored, setIsErrored] = useState<boolean>(false);
    const [loading, setLoading] = useState<boolean>(false);

    const debouncedGetResults = useDebouncedCallback(
        (input, page=1, append=false) => getResults(input, page, append), 400);

    const listRef = useRef<HTMLUListElement>(null);

    useEffect(() => {
        if (refresh !== 0) performRefresh();
      }, [refresh]);

    const onSearchInput = (evt: React.ChangeEvent<HTMLInputElement>) => {
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

    const getResults = (search_term: string, page:number = 1, append:boolean = false) => {
        // formulate the results api link
        const resLink = apiLink(search_term, page);

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

    const performRefresh = () => {
        setLoading(true);
        setResults([]);
        debouncedGetResults(previousInput);
    }

    const loadNextPage = () => {
        if (previousInput) {
            setLoading(true);
            getResults(previousInput, page + 1, true);
        }
    }

    const scrollToTop = () => {
        if (listRef.current) {
            listRef.current.scrollTop = 0;
        }
    }

    const pasteToCKEditor = (evt: React.MouseEvent<Element>) => {
        if (CKEDITOR?.instances['pluslet_ckeditor']) {
            CKEDITOR.instances['pluslet_ckeditor'].insertHtml(evt.currentTarget.outerHTML);
        } else if (CKEDITOR?.instances['faq_answer']) {
            CKEDITOR.instances['faq_answer'].insertHtml(evt.currentTarget.outerHTML);
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
                    <Token tokenType={tokenType} token={result} onClick={pasteToCKEditor} />
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
        <div id={tokenType + '-search'}>
            {extras}
            <SearchBar id={tokenType + '-searchbar'} className="form-control"
                    placeholder={placeholder ?? ('Search ' + tokenType)} onChange={onSearchInput} />
            <ul id={tokenType + '-list'} ref={listRef} className={`list-unstyled ${results.length > 0 ? "sp-search-results-panel-list" : ""}`}>
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