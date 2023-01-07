<?php

function extra_fields_plswb()
{

    $labels = array(
        'name'                  => _x('فیلدهای اضافه', 'فیلد General Name', 'text_domain'),
        'singular_name'         => _x('فیلد', 'فیلد Singular Name', 'text_domain'),
        'menu_name'             => __('فیلدها', 'text_domain'),
        'name_admin_bar'        => __('فیلد', 'text_domain'),
        'archives'              => __('بایگانی فیلد ها', 'text_domain'),
        'all_items'             => __('همه فیلد ها', 'text_domain'),
        'add_new_item'          => __('ایجاد فیلد جدید', 'text_domain'),
        'add_new'               => __('ایجاد فیلد جدید', 'text_domain'),
        'new_item'              => __('فیلد جدید', 'text_domain'),
        'edit_item'             => __('ویرایش فیلد', 'text_domain'),
        'update_item'           => __('بروزرسانی فیلد', 'text_domain'),
        'view_item'             => __('نمایش فیلد', 'text_domain'),
        'view_items'            => __('نمایش فیلد ها', 'text_domain'),
        'search_items'          => __('جستجو فیلد', 'text_domain'),
        'not_found'             => __('یافت نشد', 'text_domain'),
        'not_found_in_trash'    => __('در زباله دان یافت نشد', 'text_domain'),
        'insert_into_item'      => __('اضافه کردن به فیلد', 'text_domain'),
        'uploaded_to_this_item' => __('این فیلد بارگذاری شد', 'text_domain'),
        'items_list'            => __('لیست فیلد ها', 'text_domain'),
        'items_list_navigation' => __('لیست مسیریابی فیلد ها', 'text_domain'),
        'filter_items_list'     => __('فیلتر لیست فیلد ها', 'text_domain'),
    );
    $args = array(
        'label'                 => __('فیلد', 'text_domain'),
        'description'           => __('فیلدهای اضافه برای محصولات', 'text_domain'),
        'labels'                => $labels,
        'supports'              => ['title'],
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
    );
    register_post_type('extra_fields_plswb', $args);
}
add_action('init', 'extra_fields_plswb', 0);




function admin_enqueue($hook)
{
    if ($hook == 'post-new.php') {
        wp_enqueue_style('plswb-css-bootstrap', plugin_dir_url(__FILE__) . '/css/bootstrap.min.css', false, '1.0.0');
        wp_enqueue_style('plswb-css-select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css', false, '1.0.0');
        wp_enqueue_script('plswb-js-select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array("jquery"), "1.0.0", true);
        wp_enqueue_style('plswb-css-reapeter', plugin_dir_url(__FILE__) . '/css/jq.multiinput.min.css', false, '1.0.0');
        wp_enqueue_script('plswb-js-reapeter', plugin_dir_url(__FILE__) . '/js/jq.multiinput.js', array("jquery"), "1.0.0", true);
    } elseif ($hook == 'post.php') {
        $post_type = get_post_type($_GET['post']);
        if ($post_type == 'extra_fields_plswb') {
            wp_enqueue_style('plswb-css-bootstrap', plugin_dir_url(__FILE__) . '/css/bootstrap.min.css', false, '1.0.0');
            wp_enqueue_style('plswb-css-select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css', false, '1.0.0');
            wp_enqueue_script('plswb-js-select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array("jquery"), "1.0.0", true);
            wp_enqueue_style('plswb-css-reapeter', plugin_dir_url(__FILE__) . '/css/jq.multiinput.min.css', false, '1.0.0');
            wp_enqueue_script('plswb-js-reapeter', plugin_dir_url(__FILE__) . '/js/jq.multiinput.js', array("jquery"), "1.0.0", true);
        }
    }
}

add_action('admin_enqueue_scripts', 'admin_enqueue');



add_action('init', 'woo_general_init');
function woo_general_init()
{
    function fx_check($pid, $vid)
    {

        unset($created_fields);

        $arg = array(
            'post_type' => 'extra_fields_plswb',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        );

        $fields_plswb = new WP_Query($arg);
        if ($fields_plswb->have_posts()) {
            while ($fields_plswb->have_posts()) {
                $fields_plswb->the_post();
                $display_rules = get_post_meta(get_the_ID(), "all_products_show_rules", true);
                $extra_fields = get_post_meta(get_the_ID(), "plswb_fields", true);
                foreach ($display_rules as $product_id) {
                    $product = wc_get_product($product_id);
                    if ($product->is_type('simple')) {
                        $all_org_variation_ids[] = $product_id;
                    } else {
                        if ($product->is_type('variation')) {
                            $all_org_variation_ids[] = $product_id;
                        } else {
                            $variations = new WC_Product_Variable($product_id);
                            foreach ($variations->get_children() as  $v_id) {
                                $all_org_variation_ids[] = $v_id;
                            }
                        }
                    }
                }

                $all_org_variation_ids = array_unique($all_org_variation_ids);
                foreach ($extra_fields as $field) {
                    if ($field['disable_org_show_products_rules']) {

                        foreach ($field['inside_show_products_rules'] as $inside_product_id) {
                            $product = wc_get_product($inside_product_id);
                            if ($product->is_type('simple')) {
                                $inside_variation_ids[] = $inside_product_id;
                            } else {
                                if ($product->is_type('variation')) {
                                    $inside_variation_ids[] = $inside_product_id;
                                } else {
                                    $inside_variations = new WC_Product_Variable($inside_product_id);
                                    foreach ($inside_variations->get_children() as  $inside_v_id) {
                                        $inside_variation_ids[] = $inside_v_id;
                                    }
                                }
                            }
                        }

                        $inside_variation_ids = array_unique($inside_variation_ids);

                        if (count($field['not_show_products_rules']) > 0) {
                            foreach ($field['not_show_products_rules'] as $not_show_product_id) {
                                $product = wc_get_product($not_show_product_id);
                                if ($product->is_type('simple')) {
                                    $not_show_variation_ids[] = $not_show_product_id;
                                } else {
                                    if ($product->is_type('variation')) {
                                        $not_show_variation_ids[] = $not_show_product_id;
                                    } else {
                                        $not_show_variations = new WC_Product_Variable($not_show_product_id);
                                        foreach ($not_show_variations->get_children() as  $not_show_v_id) {
                                            $not_show_variation_ids[] = $not_show_v_id;
                                        }
                                    }
                                }
                            }
                            $not_show_variation_ids = array_unique($not_show_variation_ids);
                            $display_rules_ids = array_diff($inside_variation_ids, $not_show_variation_ids);
                            unset($not_show_variation_ids);
                        } else {
                            $display_rules_ids = $inside_variation_ids;
                            unset($inside_variation_ids);
                        }

                        if (is_null($vid)) {
                            if (in_array($pid, $display_rules_ids)) {
                                $created_fields[] = $field;
                            }
                        } else {
                            if (in_array($vid, $display_rules_ids)) {
                                $created_fields[] = $field;
                            }
                        }
                    } else {
                        if (count($field['not_show_products_rules']) > 0) {

                            foreach ($field['not_show_products_rules'] as $not_show_product_id) {
                                $product = wc_get_product($not_show_product_id);
                                if ($product->is_type('simple')) {
                                    $not_show_variation_ids[] = $not_show_product_id;
                                } else {
                                    if ($product->is_type('variation')) {
                                        $not_show_variation_ids[] = $not_show_product_id;
                                    } else {
                                        $not_show_variations = new WC_Product_Variable($not_show_product_id);
                                        foreach ($not_show_variations->get_children() as  $not_show_v_id) {
                                            $not_show_variation_ids[] = $not_show_v_id;
                                        }
                                    }
                                }
                            }
                            $not_show_variation_ids = array_unique($not_show_variation_ids);
                            $display_rules_ids = array_diff($all_org_variation_ids, $not_show_variation_ids);
                            unset($not_show_variation_ids);

                            if (is_null($vid)) {
                                if (in_array($pid, $display_rules_ids)) {
                                    $created_fields[] = $field;
                                }
                            } else {
                                if (in_array($vid, $display_rules_ids)) {
                                    $created_fields[] = $field;
                                }
                            }
                        } else {

                            if (is_null($vid)) {
                                if (in_array($pid, $all_org_variation_ids)) {
                                    $created_fields[] = $field;
                                }
                            } else {
                                if (in_array($vid, $all_org_variation_ids)) {
                                    $created_fields[] = $field;
                                }
                            }
                        }
                    }
                }

                unset($all_org_variation_ids);
                unset($inside_variation_ids);
            }
            wp_reset_postdata();
        }

        return $created_fields;
    }
}



add_action('add_meta_boxes', 'extra_fields_add_meta_boxes', 10, 2);
function extra_fields_add_meta_boxes()
{
    add_meta_box('plswb_extra_options', 'فیلدهای اضافه', 'view_plswb_extra_options', 'extra_fields_plswb', 'normal');
}


function view_plswb_extra_options()
{
?>
    <div id="wrap-fields" style="display: none;">
        <div class="col-lg-12 wrap-section-fields drag-card">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row px-3">
                        <div class="col-6">
                            <button onclick="remove_filed(this)" type="button" class="btn btn-danger btn-sm">حذف فیلد</button>
                        </div>
                        <div class="col-6" style="text-align: left;">
                            <button onclick="close_filed(this)" type="button" class="btn btn-primary btn-sm">بستن فیلد</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="plswb-card">
                        <div class="plswb-card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="ext_title">عنوان فیلد <span style="color: red;"> (الزامی) </span></label>
                                        <input oninvalid="this.setCustomValidity('عنوان فیلد را وارد کنید.')" oninput="this.setCustomValidity('')" type="text" name="" id="ext_title" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="ext_help">متن راهنما</label>
                                        <input type="text" name="" id="ext_help" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <input class="form-check-input" type="checkbox" value="true" name="" id="ext_required">
                                        <label class="form-check-label" for="ext_required">
                                            الزامی
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 close-wrap">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="plswb-card">
                                <div class="plswb-card-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="ext_type">نوع فیلد <span style="color: red;"> (الزامی) </span></label>
                                                <select oninvalid="this.setCustomValidity('نوع فیلد را انتخاب کنید.')" oninput="this.setCustomValidity('')" class="form-control" name="" id="ext_type">
                                                    <option value="">نوع فیلد را انتخاب کنید</option>
                                                    <option value="text">متنی</option>
                                                    <option value="email">ایمیل</option>
                                                    <option value="password">رمز عبور</option>
                                                    <option value="number_char">عدد</option>
                                                    <option value="textarea">توضیحات متنی</option>
                                                    <option value="checkbox">تیک زدنی</option>
                                                    <option value="select">انتخابی</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="ext_price">قیمت</label>
                                                <input type="text" name="" id="ext_price" class="form-control">
                                                <div class="form-text">چنانچه مقدار این فیلد به قیمت محصول اضافه می شود ، یک مبلغ برای آن وارد کنید.</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label for="ext_value_select">مقدار فیلد انتخابی</label>
                                                <input type="text" name="" id="ext_value_select" class="form-control">
                                                <div class="form-text">چنانچه نوع فیلد را از نوع انتخابی انتخاب کرده اید ، مقدار هر گزینه رو با # جدا کنید.
                                                    مثال : گوگل#فیسبوک#اینستاگرام</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="plswb-card">
                                <div class="plswb-card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label for="">عدم نمایش</label>
                                                <div class="col-lg-12">

                                                    <select class="org_products_not_show_rules new_select form-control" name="" id="not_show_products_rules" multiple="multiple">

                                                    </select>
                                                </div>
                                                <div class="form-text">محصولات یا متغیرهایی را که قصد دارید این فیلد در آن ها نمایش داده نشود انتخاب کنید.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="plswb-card">
                                <div class="plswb-card-body">
                                    <fieldset>
                                        <legend>
                                            <h6>قوانین نمایش</h6>
                                        </legend>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <div class="">
                                                        <input class="form-check-input" type="checkbox" value="" name="" id="ext_disable_org_show_products_rules">
                                                        <label class="form-check-label" for="ext_disable_org_show_products_rules">
                                                            غیر فعال کردن محصولات شامل کلی
                                                        </label>
                                                        <div class="form-text">اگر می خواهید محصولات انتخابی در بخش "محصولات شامل کلی" به این فیلد اعمال نشود ، این گزینه رو انتخاب کنید.
                                                            توجه : در صورت انتخاب این گزینه باید حتما از "محصولات شامل درون فیلدی" استفاده نمایید.</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="">محصولات شامل درون فیلدی</label>
                                                    <div class="col-lg-12">

                                                        <select class="org_products_not_show_rules new_select form-control" name="" id="inside_show_products_rules" multiple="multiple">

                                                        </select>
                                                    </div>
                                                    <div class="form-text">محصولات یا متغیرهایی را که قصد دارید این فیلد در آن ها نمایش داده نشود انتخاب کنید.</div>
                                                </div>
                                            </div>

                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-lg-12">
            <div class="plswb-card">
                <div class="plswb-card-body">
                    <label for="">محصولات شامل کلی</label>
                    <select class="org_products_show_rules products_rules form-control" name="org_products_show_rules[]" multiple="multiple">


                        <?php foreach (get_post_meta(get_the_ID(), 'all_products_show_rules', true) as $pid) :
                            $product = wc_get_product($pid);
                            if ($product->is_type('variation')) :
                                $variation = new WC_Product_Variation($pid);
                        ?>
                                <option selected value="<?= $pid ?>"><?= $variation->get_formatted_name() . ' | ' . $pid ?></option>
                            <?php else : ?>
                                <option selected value="<?= $pid ?>"><?= $product->get_title() . ' | ' . $pid ?></option>
                        <?php
                            endif;
                        endforeach; ?>

                    </select>
                </div>
            </div>
        </div>

        <div id="wrap-rules" class="col-lg-12 pt-2 px-4">
            <button id="btn-new-field" type="button" class="btn btn-success btn-sm">فیلد جدید</button>
        </div>

        <div class="row" id="plswb_sortable">
            <?php if (!empty(get_post_meta(get_the_ID(), 'plswb_fields', true))) : ?>
                <?php foreach (get_post_meta(get_the_ID(), 'plswb_fields', true) as $key => $item) : ?>

                    <div class="col-lg-12 wrap-section-fields drag-card">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row px-3">
                                    <div class="col-6">
                                        <button onclick="remove_filed(this)" type="button" class="btn btn-danger btn-sm">حذف فیلد</button>
                                    </div>
                                    <div class="col-6" style="text-align: left;">
                                        <button onclick="close_filed(this)" type="button" class="btn btn-primary btn-sm">بستن فیلد</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="plswb-card">
                                    <div class="plswb-card-body">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="ext_title">عنوان فیلد <span style="color: red;"> (الزامی) </span></label>
                                                    <input required oninvalid="this.setCustomValidity('عنوان فیلد را وارد کنید.')" oninput="this.setCustomValidity('')" type="text" name="<?= 'ext_options[data]' . '[' . $key . '][title]' ?>" value="<?= $item['title'] ?>" id="ext_title" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="ext_help">متن راهنما</label>
                                                    <input type="text" name="<?= 'ext_options[data]' . '[' . $key . '][help]' ?>" value="<?= $item['help'] ?>" id="ext_help" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <input class="form-check-input" type="checkbox" value="true" name="<?= 'ext_options[data]' . '[' . $key . '][required]' ?>" <?= $item['required'] ? 'checked' : '' ?> id="ext_required">
                                                    <label class="form-check-label" for="ext_required">
                                                        الزامی
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 close-wrap">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="plswb-card">
                                            <div class="plswb-card-body">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="ext_type">نوع فیلد <span style="color: red;"> (الزامی) </span></label>
                                                            <select required oninvalid="this.setCustomValidity('نوع فیلد را انتخاب کنید.')" oninput="this.setCustomValidity('')" class="form-control" name="<?= 'ext_options[data]' . '[' . $key . '][type]' ?>" id="ext_type">
                                                                <option <?= $item['type'] == 'text' ? 'selected' : '' ?> value="text">متنی</option>
                                                                <option <?= $item['type'] == 'email' ? 'selected' : '' ?> value="email">ایمیل</option>
                                                                <option <?= $item['type'] == 'password' ? 'selected' : '' ?> value="password">رمز عبور</option>
                                                                <option <?= $item['type'] == 'number_char' ? 'selected' : '' ?> value="number_char">عدد</option>
                                                                <option <?= $item['type'] == 'textarea' ? 'selected' : '' ?> value="textarea">توضیحات متنی</option>
                                                                <option <?= $item['type'] == 'checkbox' ? 'selected' : '' ?> value="checkbox">تیک زدنی</option>
                                                                <option <?= $item['type'] == 'select' ? 'selected' : '' ?> value="select">انتخابی</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="ext_price">قیمت</label>
                                                            <input type="text" name="<?= 'ext_options[data]' . '[' . $key . '][price]' ?>" value="<?= $item['price'] ?>" id="ext_price" class="form-control">
                                                            <div class="form-text">چنانچه مقدار این فیلد به قیمت محصول اضافه می شود ، یک مبلغ برای آن وارد کنید.</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="mb-3">
                                                            <label for="ext_value_select">مقدار فیلد انتخابی</label>
                                                            <input type="text" name="<?= 'ext_options[data]' . '[' . $key . '][value_select]' ?>" value="<?= $item['value_select'] ?>" id="ext_value_select" class="form-control">
                                                            <div class="form-text">چنانچه نوع فیلد را از نوع انتخابی انتخاب کرده اید ، مقدار هر گزینه رو با # جدا کنید.
                                                                مثال : گوگل#فیسبوک#اینستاگرام</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="plswb-card">
                                            <div class="plswb-card-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="mb-3">
                                                            <label for="">عدم نمایش</label>
                                                            <div class="col-lg-12">

                                                                <select class="org_products_not_show_rules products_rules_db form-control" name="<?= 'ext_options[data]' . '[' . $key . '][not_show_products_rules][]' ?>" id="not_show_products_rules" multiple="multiple">
                                                                    <?php foreach ($item['not_show_products_rules'] as $pid) :
                                                                        $product = wc_get_product($pid);
                                                                        if ($product->is_type('variation')) :
                                                                            $variation = new WC_Product_Variation($pid);
                                                                    ?>
                                                                            <option selected value="<?= $pid ?>"><?= $variation->get_formatted_name() . ' | ' . $pid ?></option>
                                                                        <?php else : ?>
                                                                            <option selected value="<?= $pid ?>"><?= $product->get_title() . ' | ' . $pid ?></option>
                                                                    <?php
                                                                        endif;
                                                                    endforeach; ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-text">محصولات یا متغیرهایی را که قصد دارید این فیلد در آن ها نمایش داده نشود انتخاب کنید.</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="plswb-card">
                                            <div class="plswb-card-body">
                                                <fieldset>
                                                    <legend>
                                                        <h6>قوانین نمایش</h6>
                                                    </legend>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="mb-3">
                                                                <div class="">
                                                                    <input class="form-check-input" type="checkbox" <?= $item['disable_org_show_products_rules'] ? 'checked' : '' ?> name="<?= 'ext_options[data]' . '[' . $key . '][disable_org_show_products_rules]' ?>" id="ext_disable_org_show_products_rules">
                                                                    <label class="form-check-label" for="ext_disable_org_show_products_rules">
                                                                        غیر فعال کردن محصولات شامل کلی
                                                                    </label>
                                                                    <div class="form-text">اگر می خواهید محصولات انتخابی در بخش "محصولات شامل کلی" به این فیلد اعمال نشود ، این گزینه رو انتخاب کنید.
                                                                        توجه : در صورت انتخاب این گزینه باید حتما از "محصولات شامل درون فیلدی" استفاده نمایید.</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="mb-3">
                                                                <label for="">محصولات شامل درون فیلدی</label>
                                                                <div class="col-lg-12">
                                                                    <select class="org_products_not_show_rules products_rules_db form-control" name="<?= 'ext_options[data]' . '[' . $key . '][inside_show_products_rules][]' ?>" id="inside_show_products_rules" multiple="multiple">
                                                                        <?php foreach ($item['inside_show_products_rules'] as $pid) :
                                                                            $product = wc_get_product($pid);
                                                                            if ($product->is_type('variation')) :
                                                                                $variation = new WC_Product_Variation($pid);
                                                                        ?>
                                                                                <option selected value="<?= $pid ?>"><?= $variation->get_formatted_name() . ' | ' . $pid ?></option>
                                                                            <?php else : ?>
                                                                                <option selected value="<?= $pid ?>"><?= $product->get_title() . ' | ' . $pid ?></option>
                                                                        <?php
                                                                            endif;
                                                                        endforeach; ?>

                                                                    </select>
                                                                </div>
                                                                <div class="form-text">محصولات یا متغیرهایی را که قصد دارید این فیلد در آن ها نمایش داده نشود انتخاب کنید.</div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>





    </div>




<?php
}

add_action('save_post_extra_fields_plswb', function ($post_id) {

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (empty($_POST['org_products_show_rules']) && empty($_POST['ext_options']['data'])) {
        return;
    }

    $org_products_show_rules = $_POST['org_products_show_rules'];
    $extra_fields = $_POST['ext_options']['data'];

    dd($extra_fields);
    update_post_meta($post_id, 'all_products_show_rules', $org_products_show_rules);
    update_post_meta($post_id, 'plswb_fields', $extra_fields);
});


add_action('wp_ajax_get_products_plswb', 'get_products');
add_action('wp_ajax_get_nopriv_products_plswb', 'get_products');
add_action('wp_ajax_get_products_org_plswb', 'get_products_org');
add_action('wp_ajax_get_nopriv_products_org_plswb', 'get_products_org');

function get_products_org()
{

    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
    );
    $loop = new WP_Query($args);
    while ($loop->have_posts()) {
        $loop->the_post();

        $products[] = ["id" => get_the_ID(), "text" => get_the_title() . " | " . get_the_ID()];
    }
    wp_reset_postdata();


    $data_products = $products;

    if (isset($_POST['search']) && !empty($_POST['search'])) {

        $input = preg_quote($_POST['search'], '~');

        foreach ($products as  $value) {
            if (preg_grep('~' . $input . '~', $value)) {
                $products_new[] = $value;
            }
        }

        $data_products = $products_new;
    }

    $product['results'] = [
        [
            "text" => "محصولات",
            "children" => $data_products
        ],
    ];

    wp_send_json([
        'items' => $product
    ]);
}

function get_products()
{
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
    );
    $loop = new WP_Query($args);
    while ($loop->have_posts()) {
        $loop->the_post();

        $products[] = ["id" => get_the_ID(), "text" => get_the_title() . " | " . get_the_ID()];

        $variations = new WC_Product_Variable(get_the_ID());
        foreach ($variations->get_children() as  $variation_id) {
            $variation = new WC_Product_Variation($variation_id);
            $variations_products[] = ["id" => $variation_id, "text" => strip_tags($variation->get_formatted_name()) . " | " . $variation_id];
        }
    }
    wp_reset_postdata();

    $data_products = $products;
    $data_variations_products = $variations_products;


    if (isset($_POST['search']) && !empty($_POST['search'])) {

        $input = preg_quote($_POST['search'], '~');

        foreach ($products as  $value) {
            if (preg_grep('~' . $input . '~', $value)) {
                $products_new[] = $value;
            }
        }
        foreach ($variations_products as  $value) {
            if (preg_grep('~' . $input . '~', $value)) {
                $variations_products_new[] = $value;
            }
        }

        $data_products = $products_new;
        $data_variations_products = $variations_products_new;
    }


    $product['results'] = [
        [
            "text" => "محصولات",
            "children" => $data_products
        ],
        [
            "text" => "متغیرها",
            "children" => $data_variations_products
        ]
    ];

    wp_send_json([
        'items' => $product
    ]);
}
