<?php
/**
 * Created by PhpStorm.
 * User: acarrasco
 * Date: 8/23/2016
 * Time: 2:50 PM
 */?>

<div class="booklist-edit-container">
	<textarea
		rows="4"
		cols="30"
		name="BookList-extra-listDescription"
		placeholder="Please insert a description for this list"><?php
				if ($this->_extra != null) {
						echo isset($this->_extra['listDescription']) ? $this->_extra['listDescription'] : '';
				}
		?>
	</textarea>

	<div class="booklist-scrollable">
		
		<ul
			class="booklist-draggables-container ui-sortable db-list-results">
				<?php
				
					$isbn_list = $this->_extra['isbn'];
					$list_split = explode(',', $isbn_list);

					foreach($list_split as $index=>$isbn) {
						$li = "
							<li
								class='db-list-item-draggable'>
								$isbn
							</li>
						";
						print $li;
					};

				?>
		</ul>

		<textarea
			rows="4"
			cols="30"
			name="BookList-extra-isbn"
			placeholder="Please insert comma-separated ISBN numbers"><?php
				if ($this->_extra != null) {
					echo $this->_extra['isbn'];
				}
			?>
		</textarea>
	</div>
</div>

<script>
    var b = bookList();
		b.initEditView();
		
		// Set up sortable, draggable table on page load completion
		$(document).ready(()=>{
			// Make whole Book List <ul> sortable via jQuery
			$('.booklist-draggables-container').sortable();

			// Make individual <li>'s draggable via jQuery
			$('.draggable').draggable({
				connectToSortable: '.booklist-draggables-container',
				helper: 'clone'
			});
		});
</script>