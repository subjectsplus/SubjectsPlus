import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import RecordResults from "./RecordResults";

console.log('alphabet list component container');

export default class AlphabetList extends Component {

    constructor(props) {
        super(props);

        this.onLetterClick = RecordResults.onLetterClick(evt);
    }

    setAlphabetLetters() {
        return ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K","L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];
    }

    getAlphabetList() {
        let alphabet = this.setAlphabetLetters();

        return alphabet.map((letter,index)=>{
            return <li className="list-group-item" key={index}><a onClick={this.onLetterClick} href="#" data-letter={letter}>{letter}</a></li>
        });
    }

    render() {
        let alphabetList = this.getAlphabetList();

        return (
            <div>
                <ul className="list-group list-group-horizontal">{alphabetList}</ul>
            </div>
        );
    }


}

ReactDOM.render(<AlphabetList />, document.getElementById('AlphabetList'));