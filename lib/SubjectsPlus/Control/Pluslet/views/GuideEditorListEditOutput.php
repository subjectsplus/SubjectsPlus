<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 5/3/16
 * Time: 9:18 AM
 */

?>


<div class='sp-modal guide-editor'  data-link-list-id="<? echo $id; ?>">
    <div class="modal-container">


        <div class="modal-header">
            <div class="pure-g">
                <div class="pure-u-3-4">
                    <label for="guide-editor-title-input">Title:
                        <input type="text" id="guide-editor-title-input" value="<?php  ?>" />
                    </label>
                    <button class="pure-button pure-button-primary modal-save" id="save-guide-editor-btn">Save</button>
                    <button class="delete-trigger modal-delete" id="delete-guide-editor-btn">Delete</button>
                </div>
                <div class="pure-u-1-4 modal-header-controls">
                    <button class="guide-editor-btn" id="close-guide-editor-btn"><i class="fa fa-times" aria-hidden="true"></i>Close Window</button>
                </div>
            </div>
        </div>


        <div class="modal-body">
            <div class="pure-g">

                <div class="pure-u-1-2">
                    <div id="guide-editor-settings">

                        <?php foreach($this->_staffArray as $staff): ?>
                            <strong><?php echo $staff['fname']; ?> <?php echo $staff['lname']; ?></strong>
                            <br>
                            <label for="tel_input">Tel:</label>
                            <input type="checkbox" class="form-control" name="tel" id="tel_checkbox"  /> <input id="tel_input" class="form-control" type="tel" placeholder="<?php echo $staff['tel']; ?>"></li>
                            <br>

                            <label for="email_input">Email:</label>
                            <input type="checkbox" class="form-control" name="email" id="email_checkbox"  /> <input id="email_input" class="form-control" type="email" placeholder="<?php echo $staff['email']; ?>"></li>
                            <br>


                            <hr>
                        <?php endforeach; ?>

                        <button id="create-guide-editor-list-btn" class="pure-button pure-button-primary">Create List</button>

                    </div>


                </div>


                <div class="pure-u-1-2">

                    <div id="GuideEditorList-body">

                        <ul id="guide-editor-list">

                        </ul>


                    </div>


                </div>

            </div>




        </div>

    </div>
</div>
