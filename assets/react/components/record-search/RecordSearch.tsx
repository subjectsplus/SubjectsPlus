import { useState, useRef } from 'react';
import ReactDOM from 'react-dom'
import { Search } from '@components/shared/Search';

export const RecordSearch = () => {
    const apiLink = '/api/titles';

    const [azlist, setAZList] = useState(true);
    const [refresh, performRefresh] = useState(0);

    const onAZListCheckBoxInput = (evt: React.ChangeEvent<HTMLInputElement>) => {
        setAZList(evt.currentTarget.checked);
        performRefresh(prev => prev + 1);
    }

    const getApiLink = (search_term: string, page: number = 1) => {
        return apiLink + '?' + new URLSearchParams({
            search: search_term,
            ...(azlist ? {'location.eresDisplay': 'Y'} : {}),
            page: String(page)
        });
    }

    // TODO: Translations for title, label below
    return (
        <Search tokenType="record" placeholder="Search Resources" apiLink={getApiLink}
            refresh={refresh}
            extras={
                <div className="form-check form-switch">
                    <input id="azlist" name="azlist" type="checkbox" className="form-check-input" checked={azlist} onChange={onAZListCheckBoxInput} />
                        <label className="form-check-label" htmlFor="azlist">Limit to A-Z List</label>
                </div>} />
    );
}

ReactDOM.render(<RecordSearch />,
    document.getElementById('record-search-container'));