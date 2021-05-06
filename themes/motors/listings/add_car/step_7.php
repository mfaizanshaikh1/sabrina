<?php
if(!get_theme_mod('enable_plans', false) || !MultiplePlan::isMultiplePlans() || get_current_user_id() == 0) return;

$plans = MultiplePlan::getPlans();
$selectedPlan = MultiplePlan::getCurrentPlan($__vars['id']);

$isEdit = (!empty($_GET['edit_car']) && !empty($_GET['item_id'])) ? true : false;
?>
<div class="stm-form-plans">
	<div class="stm-car-listing-data-single stm-border-top-unit ">
		<div class="title heading-font"><?php esc_html_e('Choose plan', 'motors'); ?></div>
		<span class="step_number step_number_5 heading-font"><?php esc_html_e('step', 'motors'); ?> 7</span>
	</div>
	<div class="user-plans-list">
        <select name="selectedPlan">
            <option value="">Select Plan</option>
		<?php foreach ($plans['plans'] as $plan):
			$selected = '';
			if($plan['plan_id'] == $selectedPlan && $plan['used_quota'] < $plan['total_quota']) {
				$selected = 'selected';
			} elseif($plan['used_quota'] == $plan['total_quota']) {
			    $selected = 'disabled';
            }

            if($isEdit && $plan['plan_id'] == $selectedPlan && $plan['used_quota'] <= $plan['total_quota']) {
				$selected = 'selected';
            }

            ?>

            <option value="<?php echo esc_attr($plan['plan_id']); ?>" <?php echo esc_attr($selected); ?>>
				<?php echo sprintf(('%s %s / %s'), $plan['label'], $plan['used_quota'], $plan['total_quota']); ?>
            </option>
		<?php endforeach; ?>
        </select>
	</div>
</div>
