import React from "react";
import ReactDOM from 'react-dom';




function AlphabetList() {

    const alphabetLetters =
        ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K","L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];


    const renderAlphabetList = () => {
        let alphabet = alphabetLetters;

        return alphabet.map((letter,index)=>{
            return <li
                className="list-group-item"
                key={index}>
                <a
                   href="#"
                   data-letter={letter}>{letter}</a></li>
        });
    }

    return (
        <>
            <div>
                <ul className="list-group list-group-horizontal">{renderAlphabetList()}</ul>
            </div>

        </>
    )
}

export default AlphabetList