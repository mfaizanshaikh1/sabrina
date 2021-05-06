<?php
/**
 * Listing
 *
 * Template can be modified by copying it to yourtheme/ulisting/listing/listing.php.
 *
 * @see     #
 * @package uListing/Templates
 * @version 1.3.8
 */

$top = "";
$center = "";
$bottom = "";


$element['params']['data-id'] = $args['model']->ID;

$model = $args['model']->getType();
$attrs = [];
$attributes = [];
$model = $args['model']->getType();
if($attributeIds = $model->getMeta('ulisting_listing_similar_attribute', true))
    $attributes = \uListing\Classes\StmListingAttribute::query()->where_in('id', array_flip($attributeIds))->find();
else
    $attributes = [];
foreach ($attributes as $key => $val){
  $val = (array)$val;
    $attrs[] = ["name" => $val['name'], 'value' => $args['model']->getAttributeValue($val['name'])];
}

$region = $args['model']->getRegion();
$category = $args['model']->getCategory();

$models = \uListing\Classes\StmListingType::get_similar_listings(
    [
        "type_id"       => $model->ID,
        "listing_id"    => $args['model']->ID,
        "region"        => isset($region[0]->term_id) ? $region[0]->term_id : null,
        "category"      => isset($category[0]->term_id) ? $category[0]->term_id : null,
        'attributes'    => $attrs,
        'limits'        => isset($element['params']['listings_count']) ? $element['params']['listings_count'] : 3,
    ]
);

?>

    <div class="ulisting-similar-listings">
        <h3><?php echo esc_html__('Similar Listings','motors'); ?></h3>
        <?php if( count($models) > 0 ): ?>
            <?php foreach ( $models as $model ) { ?>
                <?php
                $feature_image = $model->getfeatureImage('c-f-gallery-75-75');
                $feature_background_image = ($feature_image ) ? $feature_image : ulisting_get_placeholder_image_url();
                ?>
                <div class="similar-listing-item">
                    <?php
                    $price = $model->getAttributeValue('price');
                    ?>
                    <div class="similar-thumbnail-wrapper">
                        <div class="ulisting-listing-image">
                            <img width="75" height="75" src="<?php echo esc_url($feature_background_image); ?>" alt="<?php echo esc_attr__('Feature Image','motors'); ?>">
                        </div>
                        <div class="ulisting-similar-lists">
                            <?php echo ulisting_get_field($element, ["model" => $model], 'elements_top')?>
                            <?php echo ulisting_get_field($element, ["model" => $model], 'elements_center')?>
                            <?php echo ulisting_get_field($element, ["model" => $model], 'elements_bottom')?>
                        </div>
                    </div>
                </div>
            <?php } ?>
		<?php else:; ?>
            <p class="ulisting-no-similar-listing"><?php echo __("No similar listings found", 'motors')?></p>
		<?php endif; ?>
    </div>
