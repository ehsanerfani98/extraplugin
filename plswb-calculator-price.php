<?php
/*
	Plugin Name: محاسبه گر قیمت
	Version: 1
	Plugin URI: http://plussweb.ir
	Description: محاسبه قیمت بر اساس ویژگی های محصول
	Author: 👑 گروه طراحی پلاس وب 👑
	Author URI: http://plussweb.ir
	License: GPL2
 */


defined('ABSPATH') || exit();

function dd($data)
{
    wp_die(var_dump($data));
}

include plugin_dir_path( __FILE__ ).'/fields.php';

add_action('wp_head', 'header_scripts');
function header_scripts()
{
?>
    <style>
        #myprice .woocommerce-Price-amount {
            background: #ddffd1;
            color: #38b30d;
            padding: .8rem !important;
            width: auto;
            border-radius: 8px;
            font-weight: bold;
        }
    </style>
<?php
}


add_action('wp_footer', 'footer_scripts');

function footer_scripts()
{
?>
    <script>
        function change_price(item, price) {
            var total_price = jQuery(item).val();
            if (total_price == '') {
                total_price = 0;
            }
            jQuery('#myprice').html('<span class="woocommerce-Price-amount amount"><bdi>قیمت نهایی : ' + numberToPersian(separate((parseInt(price) + parseInt(total_price)))) + '<span class="woocommerce-Price-currencySymbol"> ' + currency + ' </span></bdi></span>');
        }



        function separate(Number) {
            Number += '';
            Number = Number.replace(',', '');
            x = Number.split('.');
            y = x[0];
            z = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(y))
                y = y.replace(rgx, '$1' + ',' + '$2');
            return y + z;
        }


        function numberToPersian(number) {
            const persian = {
                0: "۰",
                1: "۱",
                2: "۲",
                3: "۳",
                4: "۴",
                5: "۵",
                6: "۶",
                7: "۷",
                8: "۸",
                9: "۹",
                ",": ","
            };
            number = number.toString().split("");
            let persianNumber = ""
            for (let i = 0; i < number.length; i++) {
                number[i] = persian[number[i]];
            }
            for (let i = 0; i < number.length; i++) {
                persianNumber += number[i];
            }
            return persianNumber;
        }
    </script>
<?php
}






// add_action( 'woocommerce_variation_options_pricing', 'rudr_v_fields', 10, 3 );

// function rudr_v_fields( $loop, $variation_data, $variation ) {
// 	echo '<p class="form-row form-row-full">hey there</p>';
// }

add_action('woocommerce_product_after_variable_attributes', 'rudr_v_fields', 10, 3);

function rudr_v_fields($loop, $variation_data, $variation)
{
    woocommerce_wp_text_input(
        array(
            'id'            => 'text_field[' . $loop . ']',
            'label'         => 'Text field value',
            'wrapper_class' => 'form-row',
            'placeholder'   => 'Type here...',
            'desc_tip'      => true,
            'description'   => 'We can add some description for a field.',
            'value'         => get_post_meta($variation->ID, 'rudr_text', true)
        )
    );
}

add_action('woocommerce_save_product_variation', 'rudr_save_fields', 10, 2);
function rudr_save_fields($variation_id, $loop)
{

    // Text Field
    $text_field = !empty($_POST['text_field'][$loop]) ? $_POST['text_field'][$loop] : '';
    update_post_meta($variation_id, 'rudr_text', sanitize_text_field($text_field));
}


add_filter('woocommerce_available_variation', function ($variation) {

    $variation['text_field_anything'] = get_post_meta($variation['variation_id'], 'rudr_text', true);
    return $variation;
});

// Frontend: Handle Conditional display and include custom field value on product variation
add_filter('woocommerce_available_variation', 'variation_data_custom_field_conditional_display', 10, 3);
function variation_data_custom_field_conditional_display($data, $product, $variation)
{
    // Get custom field value and set it in the variation data array (not for display)
    $data['text_field_anything'] = $variation->get_meta('rudr_text');

    // Defined custom field conditional display
    $displayed_value = $data['text_field_anything'] > 10 ? $data['text_field_anything'] : '';
    $base_price = (float) wc_get_price_to_display($product);

?>
    <script>
        var currency = <?= "'" . get_woocommerce_currency_symbol() . "'" ?>;
    </script>
<?php
    // Frontend variation: Display value below formatted price
    $data['price_html'] .= '</div><hr style="margin-top: 1rem"><div>' . '<input type="text" oninput="change_price(this, ' . $data['display_price'] . ')"><div id="myprice" style="padding:1rem 0"></div>' . '
    </div><div class="woocommerce-variation-custom_field_html">';

    return $data;
}
