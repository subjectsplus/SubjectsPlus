import { htmlEntityDecode } from '@utility/Utility';
import { BookType } from '@shared/types/book_types';

type BookTokenProps = {
    book: BookType,
}

export const BookToken = ({ book }: BookTokenProps) => {
    return (
        <div className="book-token" data-primo-record-id={book.recordId} data-book-title={book.title}
            data-book-author={book.author} data-book-location={record.location ? record.location[0].location : null}>
                {htmlEntityDecode(record.title)}
        </div>
    );
};

