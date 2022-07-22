import { useState, useRef } from 'react';
import ReactDOM from 'react-dom'
import { SearchBar } from '@components/shared/SearchBar';
import { validateISBN } from '@utility/helpers/validateISBN';
import { getPrimoBookByISBN } from '@api/books/PrimoAPI';
import { BookType } from '@shared/types/book_types';

export const BookListSearch = () => {
    const [results, setResults] = useState<BookType[]|null>(null);
    const [previousInput, setPreviousInput] = useState<string|null>(null);
    const [inputEmpty, setInputEmpty] = useState<boolean>(true);
    const [isLoading, setIsLoading] = useState<boolean>(false);

    const onSearchInput = (evt: React.ChangeEvent<HTMLInputElement>) => {
        const userInput = evt.target.value;
        if (typeof userInput === 'string' && userInput.length >= 3) {
            if (userInput !== previousInput) {
                if (validateISBN(userInput)) {
                    setInputEmpty(false);
                    setIsLoading(true);
                    setResults(null);
                    getPrimoBookByISBN(userInput).then(data => {
                        const books: BookType[] = [];

                        if (data['docs']) {
                            data['docs'].forEach((entry: Record<string, any>) => {
                                const coverSource = entry['delivery']['link'][0]['linkURL'];
                                const title = entry['pnx']['display']['title'][0];
                                const author = entry['pnx']['sort']['author'][0];
                                const creationDate =  entry['pnx']['sort']['creationdate'][0];
                                const recordId = entry['pnx']['search']['recordid'][0];
                                const isbn = userInput.trim();

                                books.push({
                                    coverSource: coverSource,
                                    title: title,
                                    author: author,
                                    creationDate: creationDate,
                                    recordId: recordId,
                                    isbn: isbn
                                });
                            });
                        }

                        setResults(books);
                        setIsLoading(false);
                    })
                }
            }
        } else {
            setInputEmpty(true);
            setResults(null);
            setPreviousInput('');
        }
    }

    return (
        <div id="booklist-search">
            <SearchBar id="booklist-search-bar" className="form-control"
                    placeholder="Search Books By ISBN" onChange={onSearchInput} />
            <ul>

            </ul>
        </div>
    );
}

ReactDOM.render(<BookListSearch />,
    document.getElementById('guide-booklist-search-container'));