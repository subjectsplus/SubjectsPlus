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
	
	<div class="booklist-description-container">
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
	</div>

	<div class="booklist-update-container">
		
		<ul
			class="booklist-draggables-container">
				<?php
				
					$isbn_list = $this->_extra['isbn'];
					$list_split = explode(',', $isbn_list);

					foreach($list_split as $index=>$isbn) {
						$li = "
							<li
								data-isbn='$isbn'
								class='booklist-item-draggable'>
									<span class='isbn-number'>
										<i class='fa fa-bars'></i>	
										$isbn</span>
									<i class='fa fa-trash booklist-delete-button' data-isbn='$isbn'></i>
									</li>";
						print $li;
					};

				?>
		</ul>

		<div class="isbn-input-container">
			<input
				type="text"
				class="isbn-input"
				name="isbn-input"
				placeholder="10 or 13 digit ISBN">
			<button
				type="button"
				class="add-isbn"
				name="add-isbn">
					Add
			</button>
		</div>

		<div>
			<p>
				<strong>You may rearrange the items in the list using the drag bar (<i class='fa fa-bars'></i>).</strong>
			</p>
		</div>

		<textarea
			rows="6"
			cols="50"
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
</script>