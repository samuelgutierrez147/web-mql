<?php
/**
 * WAPO Template
 *
 * @author  YITH <plugins@yithemes.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 *
 * @var YITH_WAPO_Addon $addon
 * @var int $x
 * @var string $setting_hide_images
 * @var string $required_message
 * @var array $settings
 * @var string $image_replacement
 * @var string $option_description
 * @var string $option_image
 * @var string $price
 * @var string $price_method
 * @var string $price_sale
 * @var string $price_type
 * @var string $currency
 */

defined('YITH_WAPO') || exit; // Exit if accessed directly.

// Settings configuration.
extract($settings);

$hide_options_prices = apply_filters('yith_wapo_hide_option_prices', $hide_option_prices, $addon);
$show_in_a_grid = wc_string_to_bool($show_in_a_grid);
$options_width_css = $show_in_a_grid && 1 == $options_per_row ? 'width: ' . $options_width . '%' : 'width: 100%';

$hide_option_images = wc_string_to_bool($hide_option_images);
$hide_option_label = wc_string_to_bool($hide_option_label);
$hide_option_prices = wc_string_to_bool($hide_option_prices);
$hide_product_prices = wc_string_to_bool($hide_product_prices);

$image_replacement = $addon->get_image_replacement($addon, $x);

// Options configuration.
$required = $addon->get_option('required', $x, 'no', false) === 'yes';
$remove_spaces = apply_filters('yith_wapo_remove_spaces', false);

// Obtener el valor del campo personalizado "ID"
$custom_id = $addon->get_setting('addon_identificador', '', false);

?>

<div id="yith-wapo-option-<?php echo esc_attr($addon->id); ?>-<?php echo esc_attr($x); ?>" class="yith-wapo-option">

    <div class="label <?php echo wp_kses_post(!empty($addon_image_position) ? 'position-' . $addon_image_position : ''); ?>">

        <div class="textarea-container">

            <div class="option-container">
                <!-- ABOVE / LEFT IMAGE -->
                <?php
                if ('above' === $addon_options_images_position || 'left' === $addon_options_images_position) {
                    //TODO: use wc_get_template() function.
                    include YITH_WAPO_DIR . '/templates/front/option-image.php';
                }
                ?>
                <label for="<?php echo esc_attr($custom_id); ?>-<?php echo esc_attr($x); ?>">

                    <!-- LABEL -->
                    <?php echo !$hide_option_label ? wp_kses_post($addon->get_option('label', $x)) : ''; ?>
                    <?php echo $required ? '<span class="required">*</span>' : ''; ?>

                    <!-- PRICE -->
                    <?php echo !$hide_option_prices ? wp_kses_post($addon->get_option_price_html($x, $currency, $product)) : ''; ?>

                </label>
                <!-- UNDER / RIGHT IMAGE -->
                <?php
                if ('under' === $addon_options_images_position || 'right' === $addon_options_images_position) {
                    //TODO: use wc_get_template() function.
                    include YITH_WAPO_DIR . '/templates/front/option-image.php';
                }
                ?>

            </div>

            <!-- INPUT -->
            <textarea id="<?= esc_attr($custom_id); ?>" class="yith-wapo-option-value"
                      name="yith_wapo[][<?= esc_attr($custom_id); ?>]"
                <?php if ($addon->get_option('characters_limit', $x, 'no', false) === 'yes') : ?>
                    minlength="<?php echo esc_attr($addon->get_option('characters_limit_min', $x)); ?>"
                    maxlength="<?php echo esc_attr($addon->get_option('characters_limit_max', $x)); ?>"
                <?php endif; ?>
                data-default-price="<?php echo esc_attr($default_price); ?>"
                <?php
                if ($default_price > 0) {
                    ?>
                    data-default-sale-price="<?php echo esc_attr($default_sale_price); ?>"
                    <?php
                }
                ?>
                data-price="<?php echo esc_attr($price); ?>"
                <?php
                if ($price > 0) {
                    ?>
                    data-price-sale="<?php echo esc_attr($price_sale); ?>"
                    <?php
                }
                ?>
                data-price-type="<?php echo esc_attr($price_type); ?>"
                      data-price-method="<?php echo esc_attr($price_method); ?>"
                      data-first-free-enabled="<?php echo esc_attr($first_options_selected); ?>"
                      data-first-free-options="<?php echo esc_attr($first_free_options); ?>"
                      data-addon-id="<?php echo esc_attr($addon->id); ?>"
                <?php echo 'characters' === $price_type && $remove_spaces ? 'data-remove-spaces=\'yes\'' : ''; ?>
                <?php echo $required ? 'required' : ''; ?>
                style="<?php echo esc_attr($options_width_css); ?>"
                      placeholder="<?php echo esc_attr($addon->get_option('placeholder', $x)); ?>"></textarea>
        </div>
    </div>


    <!-- TOOLTIP -->
    <?php if ($addon->get_option('tooltip', $x) !== '') : ?>
        <span class="tooltip position-<?php echo esc_attr(get_option('yith_wapo_tooltip_position')); ?>">
			<span><?php echo wp_kses_post($addon->get_option('tooltip', $x)); ?></span>
		</span>
    <?php endif; ?>

    <!-- DESCRIPTION -->
    <?php if ('' !== $option_description) : ?>
        <p class="description"><?php echo wp_kses_post($option_description); ?></p>
    <?php endif; ?>
    <!-- Sold individually -->
    <?php if ('yes' === $sell_individually) : ?>
        <input type="hidden" name="yith_wapo_sell_individually[<?php echo esc_attr($addon->id . '-' . $x); ?>]"
               value="yes">
    <?php endif; ?>
</div>
