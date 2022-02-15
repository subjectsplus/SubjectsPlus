import React, { Component } from 'react';
import ReactDOM from 'react-dom';


console.log('alphabet list component container');

export default class AlphabetList extends Component {

    getAlphabetList() {

    }

    render() {

        let items = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K","L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];

        let itemList=items.map((item,index)=>{
            return <li key={index}>{item}</li>
        });

        return (
            <ul>{itemList}</ul>
        );
    }


}

ReactDOM.render(<AlphabetList />, document.getElementById('AlphabetList'));