<?php
/** @var CategoriesCollection $data */

use QuizAd\Model\Registration\API\Registration\CategoriesCollection;

?>

<div class="topPane">
    <div class="leftPane pane" id="sources">
		<?php
		foreach ($data->getCategories() as $key => $category)
		{
			?>
            <div data-option="<?php echo esc_attr($category->getId()) ?>"
                 value="<?php echo esc_attr($category->getId()) ?>"
                 class="option"><?php echo esc_html($category->getName()) ?>
            </div>
		<?php } ?>
    </div>

    <div class="rightPane pane" id="targets">
    </div>
</div>
