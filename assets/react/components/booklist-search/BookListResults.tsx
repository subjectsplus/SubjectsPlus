import { BookType } from '@shared/types/book_types';
import { BookToken } from '@components/shared/token/BookToken'

type BookListResults = {
    results: BookType[]|null,
    isLoading: boolean
}

export const BookListResults = ({ results, isLoading }: BookListResults) => {
    if (isLoading) {
        return (<p>Loading...</p>);
    } else if (results) {
        return (
            <ul id="booklist-results">
                {results.map(result => {

                })}
            </ul>
        );
    }
}