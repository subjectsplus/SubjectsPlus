<?php
/**
 * Created by PhpStorm.
 * User: acarrasco
 * Date: 8/23/2016
 * Time: 2:52 PM
 */?>


<div class="booklist-container">
    <?php
    if ($this->_extra != null)
    {
        if (!empty($this->_extra['isbn'])) {

            $isbns = explode(",", $this->_extra['isbn']);
            foreach ($isbns as $number) {
                if (!empty($number)){
                    $this->setBookInfo($number);
                    if ($this->_validBook) {?>
                    <div data-book-id="<?php echo $this->_bookId ?>">
                        <div data-show-image="<?php echo $this->_bookCover ?>">
                            <img src="<?php echo $this->_bookCover;?>">
                        </div>
                        <div>
                            <h2 data-book-title="<?php echo $this->_bookTitle ?>"><?php echo $this->_bookTitle ?></h2>
                            <ul data-book-isbn="<?php echo $this->_bookIsbnNumber ?>">
                                <?php
                                    $bookIsbnList = $this->_bookIsbnNumberArray;
                                    foreach ($bookIsbnList as $listElement){?>
                                        <li><?php echo $listElement ?></li>
                                      <?php }
                                    ?>
                            </ul>
                            <p data-book-author="<?php echo $this->_authorsList ?>"><?php echo $this->_authorsList ?></p>
                            <p data-book-pubdate="<?php echo $this->_publishedDate ?>"><?php echo $this->_publishedDate ?></p>
                        </div>
                        </div><?php
                    } else { ?>
                        <div data-book-id="">
                            <h3>Please insert a valid ISBN</h3>
                        </div>
                        <?php
                    }
                }
            }
        }
    }?>

</div>