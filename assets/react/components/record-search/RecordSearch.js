import React, { Component } from 'react';
import ReactDOM from 'react-dom'
import Search from '../../shared/Search.js';

export default class RecordSearch extends Component {
    apiLink = '/api/titles';

    constructor(props) {
        super(props);
        
        this.state = {
            azlist: true,
        };

        this.searchElement = React.createRef();

        this.onAZListCheckBoxInput = this.onAZListCheckBoxInput.bind(this);
        this.getApiLink = this.getApiLink.bind(this);
    }

    onAZListCheckBoxInput(evt) {
        this.setState({azlist: evt.target.checked},
            () => {
                this.searchElement.current.refresh();
            });
        console.log("checked: " + evt.target.checked);
        console.log("state: " + this.state.azlist);
    }

    getApiLink(search_term, page=1) {
        return this.apiLink + '?' + new URLSearchParams({
            search: search_term,
            'location.eresDisplay': (this.state.azlist ? 'Y' : 'N'),
            page: page
        });
    }

    render() {
        return (
            <Search tokenType="record" title="Get Records" apiLink={this.getApiLink}
                ref={this.searchElement}
                extras={
                    <label>
                        <input id="azlist" name="azlist" type="checkbox" checked={this.state.azlist} 
                            onChange={this.onAZListCheckBoxInput} />
                        Limit to A-Z List
                    </label>} />
        );
    }
}

ReactDOM.render(<RecordSearch />,
    document.getElementById('record-search-container'));