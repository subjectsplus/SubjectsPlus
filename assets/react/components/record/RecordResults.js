import { Component } from 'react';
import ReactDOM from 'react-dom';



export default class RecordResults extends Component {

    apiLink = '/api/titles'

    constructor(props) {
        super(props);

        this.state = {
            letter: 'a',
            results: null,
            azlist: true,
            isErrored: false,
            loading: false,
            page: 1,
            hasNextPage: false,
            data: []
        }

        this.onLetterClick = this.onLetterClick.bind(this);
    }

    componentDidMount() {
        this.getResults(this.state.letter);
    }

    getApiLink(letter, page=1) {
        return this.apiLink + '?' + new URLSearchParams({
            letter: letter,
            'location.eresDisplay': (this.state.azlist ? 'Y' : 'N'),
            page: page
        });
    }

    setAlphabetLetters() {
        return ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K","L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];
    }

    getAlphabetList() {
        let alphabet = this.setAlphabetLetters();

        return alphabet.map((letter,index)=>{
            return <li
                    className="list-group-item"
                    key={index}>
                        <a onClick={this.onLetterClick}
                           href="#"
                           data-letter={letter}>{letter}</a></li>
        });
    }

    onLetterClick(evt) {
        //console.log(evt);
        //console.log(evt.currentTarget.dataset.letter);

        this.setState({
            letter: evt.currentTarget.dataset.letter
        });

        this.getResults(evt.currentTarget.dataset.letter, this.state.page);
    }

    getResults(letter, page=1) {
        // formulate the results api link
        let resLink = this.getApiLink(letter);
        //console.log(resLink);
        // only append results from subsequent pages
        //if (page === 1) append = false;

        // fetch api results
        fetch(resLink).then(response => {
            if (response.ok) {
                return response.json();
            }

            this.setState({
                isErrored: true,
                loading: false,
                letter: letter
            });
        })
        .then(results => {

            // // create a lookup table for different formats of location
            // results['hydra:member'].map((result, index) => {
            //     let locationsTable = {};
            //     result.location.map(location => {
            //         if (location.format) {
            //             locationsTable[location.format.format] = location;
            //         } else {
            //             locationsTable['Web'] = location;
            //         }
            //     });

            //     results['hydra:member'][index]['location'] = locationsTable;
            // });

            console.log(results['hydra:member']);
            console.log(results['hydra:member'].location);

            this.setState({
                results: results['hydra:member'],
                page: page,
                hasNextPage: (results['hydra:view']['hydra:next'] != null),
                isErrored: false,
                loading: false
            });
        })
        .catch(err => {
            console.error(err);
            this.setState({
                isErrored: true,
                loading: false
            });
        });
    }

    displayResults() {
        let resultItems = [];

        if(this.state.results) {
            //console.log(this.state.results);
            resultItems = this.state.results.map( (result, index) => {
                console.log(result);
                return (
                    <li key={result.titleId}>
                        <a href={result.location[0].location}>{result.title}</a>
                    </li>
                );
            });

        } else {
            resultItems = (<p>no results found</p>);
        }

        return resultItems;
    }

    getRecordCount() {
        let recordCount = 0;

        if(this.state.results) {
            recordCount = this.state.results.length;

            return (
                <p>{recordCount} records starting with {this.state.letter}</p>
            )

        } else {
            return (
                <p>No records starting with {this.state.letter}</p>
            )
        }
    }

    render() {

        let alphabetList = this.getAlphabetList();
        let displayResults = this.displayResults();
        let recordCount = this.getRecordCount();

        return (

            <div>
                <ul className="list-group list-group-horizontal">{alphabetList}</ul>
                <div>{recordCount}</div>
                <ul>{displayResults}</ul>
            </div>

        );
    }

}
ReactDOM.render(<RecordResults />, document.getElementById('RecordResults'));