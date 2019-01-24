<div class="google-search">
<form action="<?php global $webpac_cat_action; echo $webpac_cat_action; ?>" method="get" name="search" id="search" class="pure-form">
<input type="hidden" id="iiiFormHandle_1">

<!-- Optional legend:
<fieldset>
<legend>Type the search term you want to find.</legend>-->
<div class="formEntryArea">
<label for="searchtype" class="accessibleAddInfo">Search type</label>
<select name="searchtype" id="searchtype">
<option value="t"selected="selected">Title</option>
<option value="X">Keyword</option>
<option value="a">Author</option>
<option value="d">Subject</option>
<option value="s">Journal Title</option>
</select>
<input type="hidden" name="SORT" id="SORT" value="D" />
<br />
<label for="searcharg" class="accessibleAddInfo">Search term</label>
<input maxlength="75" name="searcharg" size="20" >
<input type="hidden" id="iiiFormHandle_1"/>
<input name="Search" type="submit" id="Search" value="SEARCH" >


</span>
</div><!-- formEntryArea -->
<!--</fieldset> optional-->
</form>
</div>

<script language="javascript">
function iiiDoSubmit_1()
{
//getFormHandleForm() is in common.js
var obj = getFormHandleForm(1);
obj.submit();
}

</script>