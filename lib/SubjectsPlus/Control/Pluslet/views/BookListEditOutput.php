<?php
/**
 * Created by PhpStorm.
 * User: acarrasco
 * Date: 8/23/2016
 * Time: 2:50 PM
 */?>

<?php

	// Set Pluslet ID for use below
	$booklist_id = $this->_pluslet_id;

?>

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

	<div class="booklist-draggable">
		
		<ul
			class="booklist-draggables-container">
				<?php
				
					$isbn_list = $this->_extra['isbn'];
					$list_split = explode(',', $isbn_list);

					foreach($list_split as $index=>$isbn) {
						$li = "<li class='booklist-item-draggable'>$isbn</li>";
						print $li;
					};

				?>
		</ul>

		<div>
			<input
				type="text"
				class="isbn-input"
				name="isbn-input">
			<button
				type="button"
				class="add-isbn"
				name="add-isbn">
					Add
			</button>
		</div>

		<!-- <textarea
			rows="4"
			cols="30"
			name="BookList-extra-isbn"
			placeholder="Please insert comma-separated ISBN numbers"><?php
				if ($this->_extra != null) {
					echo $this->_extra['isbn'];
				}
			?>
		</textarea> -->
	</div>
</div>

<script>
    var b = bookList();
		b.initEditView();
</script>