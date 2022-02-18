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
    }

    getApiLink(search_term, page=1) {
        return this.apiLink + '?' + new URLSearchParams({
            search: search_term,
            'location.eresDisplay': (this.state.azlist ? 'Y' : 'N'),
            page: page
        });
    }

    render() {
        // TODO: Translations for title, label below
        return (
            <Search tokenType="record" title="Get Records" apiLink={this.getApiLink}
                ref={this.searchElement}
                extras={
                    <div className="form-check form-switch">
                        <input id="azlist" name="azlist" type="checkbox" className="form-check-input" checked={this.state.azlist} onChange={this.onAZListCheckBoxInput} />
                            <label className="form-check-label" for="azlist">Limit to A-Z List</label>
                    </div>} />
        );
    }
}

ReactDOM.render(<RecordSearch />,
    document.getElementById('record-search-container'));