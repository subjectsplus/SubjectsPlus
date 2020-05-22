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
	$new_pluslet = empty( $this->_pluslet_id );

?>

<div
	class="booklist-edit-container"
	data-booklist-id="<?php echo $booklist_id ?>">
	
	<div class="booklist-description-container">
		<textarea
			rows="4"
			cols="30"
			name="BookList-extra-listDescription"
			placeholder="Please insert a description for this list"><?php
				if ($this->_extra != null) {
						echo isset($this->_extra['listDescription']) ? trim($this->_extra['listDescription']) : '';
				}
			?></textarea>
	</div>

	<div class="booklist-update-container">

		<div class="isbn-input-container">
			<input
				<?php echo ($new_pluslet ? 'disabled' : ''); ?>
				type="text"
				class="isbn-input"
				name="isbn-input"
				placeholder="10 or 13 digit ISBN">
			<button
			  <?php echo ($new_pluslet ? 'disabled' : ''); ?>
				data-booklist-id="<?php echo $booklist_id ?>"
				type="button"
				class="add-isbn button"
				name="add-isbn">Add</button>
		</div>
		
		<ul
			class="booklist-draggables-container"
			style="padding: 0; margin-top: 0;"
			data-booklist-id="<?php echo $booklist_id ?>">
				<?php

					$items_to_show = ( isset($this->_extra['isbn']) && !empty($this->_extra['isbn']) );
				
					// Account for new BL Pluslet, which doesn't have this field yet
					if ( $items_to_show ) {
						$isbn_list = $this->_extra['isbn'];
						$list_split = explode(',', $isbn_list);
	
						foreach($list_split as $index=>$isbn) {
							// NOTE: This template must be kept in sync with the one returned from
							// getSortableIsbnLi() in bookList.js
							$li = "
								<li
									data-isbn='$isbn'
									class='booklist-item-draggable'>
										<div>
											<i class='fa fa-bars'></i>
											<span class='isbn-number'>&nbsp;&nbsp;&nbsp;$isbn</span>
										</div>
										<i class='fa fa-trash booklist-delete-button' data-isbn='$isbn'></i>
										</li>";
							print $li;
						};
					} elseif ( $new_pluslet ) {
						echo "<p>(Please click the 'Save Changes' button before adding ISBNs.)</p>";
					};

				?>
		</ul>

		<div style="margin-left: 0px; width: auto; max-width: none;">
			<span>
				<strong>You may rearrange the items in the list using the drag bar (<i class='fa fa-bars'></i>).</strong>
			</span>
		</div>

		<textarea
			data-booklist-id="<?php echo $booklist_id ?>"
			class="booklist-hidden-textarea"
			rows="6"
			cols="11"
			name="BookList-extra-isbn"
			placeholder="Please insert comma-separated ISBN numbers"><?php
				if ($items_to_show) {
					echo trim($this->_extra['isbn']);
				}
			?></textarea>
	</div>
</div>

<script>
	var b = bookList();
	b.initEditView();
</script>