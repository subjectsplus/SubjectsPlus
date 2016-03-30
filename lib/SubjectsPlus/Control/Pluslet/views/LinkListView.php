<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 3/18/16
 * Time: 3:41 PM
 */

$linkList = $this->_linkList;
?>

<div class=“link-list-text-top”><?php if(!is_array($linkList['topContent'])) {echo $linkList['topContent'];} ?></div>
    <ul class=“link-list” data-link-list-pluslet-id=“123”>
        <?php foreach($linkList['record'] as $item): ?>

            <?php $displayOptions = $item['displayOptions']['showIcons'].$item['displayOptions']['showDesc'].$item['displayOptions']['showNote']; ?>

            <li data-link-list-item-id=“<?php echo $item['recordId']; ?>” data-link-list-display-options=“<?php echo $displayOptions; ?>”>{{dab},{<?php echo $item['recordId']; ?>},{<?php echo $item['title']; ?>},{<?php echo $displayOptions; ?>}}</li>
        <?php endforeach; ?>
    </ul>
<div class=“link-list-text-bottom”><?php if(!is_array($linkList['bottomContent'])) {echo $linkList['bottomContent'];} ?></div>
