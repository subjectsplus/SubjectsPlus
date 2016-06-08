<?
namespace SubjectsPlus\Control\Interfaces;
interface ISearcher
{
	/**
	 * Return Books from many ISBN
	 * @param  array(string)  $isbns [ISBN numbers to perform the search.]
	 * @return array(Book)  [An array containing the Book elements from the ISBNs passed.]
	 */
    public function getBooks($isbns);

    /**
     * Return one book per the ISBN parameter
     * @param  string $isbn [ISBN number to perform the search.]
     * @return bool [If the book exists or not]
     */
    public function existBook($isbn);
}