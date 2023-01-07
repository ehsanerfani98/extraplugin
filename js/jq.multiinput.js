jQuery(document).ready(function ($) {



    $('.products_rules').select2({
        language: {
            searching: function () {
                return "در حال جستجو...";
            }
        }, placeholder: "محصولات مورد نظر خود را انتخاب کنید.",
        ajax: {
            url: ajaxurl,
            data: function (params) {
                return {
                    search: params.term,
                    action: 'get_products_org_plswb'
                }
            },
            type: "post",
            processResults: function (data) {
                return {
                    results: data.items.results
                }
            },
        }

    });


    $('.products_rules_db').select2({
        language: {
            searching: function () {
                return "در حال جستجو...";
            }
        },
        placeholder: "محصولات مورد نظر خود را انتخاب کنید.",
        ajax: {
            url: ajaxurl,
            data: function (params) {
                return {
                    search: params.term,
                    action: 'get_products_plswb'
                }
            },
            type: "post",
            processResults: function (data) {
                return {
                    results: data.items.results
                }
            },
        }

    });

    $(document).on('click', '#btn-new-field', function () {
        let unique_id = makeid(20);

        // let fields = $('#wrap-fields').contents().clone();

        // $(fields).find('#ext_title').attr({ 'name': 'ext_options[data]' + '[' + unique_id + '][title]', 'required': 'required' });
        // $(fields).find('#ext_help').attr('name', 'ext_options[data]' + '[' + unique_id + '][help]');
        // $(fields).find('#ext_required').attr('name', 'ext_options[data]' + '[' + unique_id + '][required]');
        // $(fields).find('#ext_type').attr({ 'name': 'ext_options[data]' + '[' + unique_id + '][type]', 'required': 'required' });
        // $(fields).find('#ext_price').attr('name', 'ext_options[data]' + '[' + unique_id + '][price]');
        // $(fields).find('#ext_value_select').attr('name', 'ext_options[data]' + '[' + unique_id + '][value_select]');
        // $(fields).find('#not_show_products_rules').attr('name', 'ext_options[data]' + '[' + unique_id + '][not_show_products_rules][]');
        // $(fields).find('#ext_disable_org_show_products_rules').attr('name', 'ext_options[data]' + '[' + unique_id + '][disable_org_show_products_rules]');
        // $(fields).find('#inside_show_products_rules').attr('name', 'ext_options[data]' + '[' + unique_id + '][inside_show_products_rules][]');



        $('#plswb_sortable').prepend('<div id="wrap-fields">\n' +
            '        <div class="col-lg-12 wrap-section-fields drag-card">\n' +
            '            <div class="row">\n' +
            '                <div class="col-lg-12">\n' +
            '                    <div class="row px-3">\n' +
            '                        <div class="col-6">\n' +
            '                            <button onclick="remove_filed(this)" type="button" class="btn btn-danger btn-sm">حذف فیلد</button>\n' +
            '                        </div>\n' +
            '                        <div class="col-6" style="text-align: left;">\n' +
            '                            <button onclick="close_filed(this)" type="button" class="btn btn-primary btn-sm">بستن فیلد</button>\n' +
            '                        </div>\n' +
            '                    </div>\n' +
            '                </div>\n' +
            '                <div class="col-lg-12">\n' +
            '                    <div class="plswb-card">\n' +
            '                        <div class="plswb-card-body">\n' +
            '                            <div class="row">\n' +
            '                                <div class="col-lg-6">\n' +
            '                                    <div class="mb-3">\n' +
            '                                        <label for="ext_title">عنوان فیلد <span style="color: red;"> (الزامی) </span></label>\n' +
            '                                        <input oninvalid="this.setCustomValidity(عنوان فیلد را وارد کنید.)" oninput="this.setCustomValidity()" type="text" name="' + 'ext_options[data]' + '[' + unique_id + '][title]' + '" required id="ext_title" class="form-control">\n' +
            '                                    </div>\n' +
            '                                </div>\n' +
            '                                <div class="col-lg-6">\n' +
            '                                    <div class="mb-3">\n' +
            '                                        <label for="ext_help">متن راهنما</label>\n' +
            '                                        <input type="text" name="' + 'ext_options[data]' + '[' + unique_id + '][help]' + '" id="ext_help" class="form-control">\n' +
            '                                    </div>\n' +
            '                                </div>\n' +
            '                                <div class="col-lg-12">\n' +
            '                                    <div class="mb-3">\n' +
            '                                        <input class="" type="checkbox" value="true" name="' + 'ext_options[data]' + '[' + unique_id + '][required]' + '" id="ext_required">\n' +
            '                                        <label class="form-check-label" for="ext_required">\n' +
            '                                            الزامی\n' +
            '                                        </label>\n' +
            '                                    </div>\n' +
            '                                </div>\n' +
            '                            </div>\n' +
            '                        </div>\n' +
            '                    </div>\n' +
            '                </div>\n' +
            '                <div class="col-lg-12 close-wrap">\n' +
            '                    <div class="row">\n' +
            '                        <div class="col-lg-12">\n' +
            '                            <div class="plswb-card">\n' +
            '                                <div class="plswb-card-body">\n' +
            '                                    <div class="row">\n' +
            '                                        <div class="col-lg-6">\n' +
            '                                            <div class="mb-3">\n' +
            '                                                <label for="ext_type">نوع فیلد <span style="color: red;"> (الزامی) </span></label>\n' +
            '                                                <select oninvalid="this.setCustomValidity(نوع فیلد را انتخاب کنید.)" oninput="this.setCustomValidity()" class="form-control" name="' + 'ext_options[data]' + '[' + unique_id + '][type]' + '" required id="ext_type">\n' +
            '                                                    <option value="">نوع فیلد را انتخاب کنید</option>\n' +
            '                                                    <option value="text">متنی</option>\n' +
            '                                                    <option value="email">ایمیل</option>\n' +
            '                                                    <option value="password">رمز عبور</option>\n' +
            '                                                    <option value="number_char">عدد</option>\n' +
            '                                                    <option value="textarea">توضیحات متنی</option>\n' +
            '                                                    <option value="checkbox">تیک زدنی</option>\n' +
            '                                                    <option value="select">انتخابی</option>\n' +
            '                                                </select>\n' +
            '                                            </div>\n' +
            '                                        </div>\n' +
            '                                        <div class="col-lg-6">\n' +
            '                                            <div class="mb-3">\n' +
            '                                                <label for="ext_price">قیمت</label>\n' +
            '                                                <input type="text" name="' + 'ext_options[data]' + '[' + unique_id + '][price]' + '" id="ext_price" class="form-control">\n' +
            '                                                <div class="form-text">چنانچه مقدار این فیلد به قیمت محصول اضافه می شود ، یک مبلغ برای آن وارد کنید.</div>\n' +
            '                                            </div>\n' +
            '                                        </div>\n' +
            '                                        <div class="col-lg-12">\n' +
            '                                            <div class="mb-3">\n' +
            '                                                <label for="ext_value_select">مقدار فیلد انتخابی</label>\n' +
            '                                                <input type="text" name="' + 'ext_options[data]' + '[' + unique_id + '][value_select]' + '" id="ext_value_select" class="form-control">\n' +
            '                                                <div class="form-text">چنانچه نوع فیلد را از نوع انتخابی انتخاب کرده اید ، مقدار هر گزینه رو با # جدا کنید.\n' +
            '                                                    مثال : گوگل#فیسبوک#اینستاگرام</div>\n' +
            '                                            </div>\n' +
            '                                        </div>\n' +
            '                                    </div>\n' +
            '                                </div>\n' +
            '                            </div>\n' +
            '                        </div>\n' +
            '                        <div class="col-lg-12">\n' +
            '                            <div class="plswb-card">\n' +
            '                                <div class="plswb-card-body">\n' +
            '                                    <div class="row">\n' +
            '                                        <div class="col-lg-12">\n' +
            '                                            <div class="mb-3">\n' +
            '                                                <label for="">عدم نمایش</label>\n' +
            '                                                <div class="col-lg-12">\n' +
            '\n' +
            '                                                    <select class="org_products_not_show_rules new_select form-control products_rules_pluss" name="' + 'ext_options[data]' + '[' + unique_id + '][not_show_products_rules][]' + '" id="not_show_products_rules" multiple="multiple">\n' +
            '\n' +
            '                                                    </select>\n' +
            '                                                </div>\n' +
            '                                                <div class="form-text">محصولات یا متغیرهایی را که قصد دارید این فیلد در آن ها نمایش داده نشود انتخاب کنید.</div>\n' +
            '                                            </div>\n' +
            '                                        </div>\n' +
            '                                    </div>\n' +
            '                                </div>\n' +
            '                            </div>\n' +
            '                        </div>\n' +
            '                        <div class="col-lg-12">\n' +
            '                            <div class="plswb-card">\n' +
            '                                <div class="plswb-card-body">\n' +
            '                                    <fieldset>\n' +
            '                                        <legend>\n' +
            '                                            <h6>قوانین نمایش</h6>\n' +
            '                                        </legend>\n' +
            '                                        <div class="row">\n' +
            '                                            <div class="col-lg-12">\n' +
            '                                                <div class="mb-3">\n' +
            '                                                    <div class="">\n' +
            '                                                        <input class="" type="checkbox" value="true" name="' + 'ext_options[data]' + '[' + unique_id + '][disable_org_show_products_rules]' + '" id="ext_disable_org_show_products_rules">\n' +
            '                                                        <label class="form-check-label" for="ext_disable_org_show_products_rules">\n' +
            '                                                            غیر فعال کردن محصولات شامل کلی\n' +
            '                                                        </label>\n' +
            '                                                        <div class="form-text">اگر می خواهید محصولات انتخابی در بخش "محصولات شامل کلی" به این فیلد اعمال نشود ، این گزینه رو انتخاب کنید.\n' +
            '                                                            توجه : در صورت انتخاب این گزینه باید حتما از "محصولات شامل درون فیلدی" استفاده نمایید.</div>\n' +
            '                                                    </div>\n' +
            '                                                </div>\n' +
            '                                            </div>\n' +
            '                                            <div class="col-lg-12">\n' +
            '                                                <div class="mb-3">\n' +
            '                                                    <label for="">محصولات شامل درون فیلدی</label>\n' +
            '                                                    <div class="col-lg-12">\n' +
            '\n' +
            '                                                        <select class="org_products_not_show_rules new_select form-control products_rules_pluss" name="' + 'ext_options[data]' + '[' + unique_id + '][inside_show_products_rules][]' + '" id="inside_show_products_rules" multiple="multiple">\n' +
            '\n' +
            '                                                        </select>\n' +
            '                                                    </div>\n' +
            '                                                    <div class="form-text">محصولات یا متغیرهایی را که قصد دارید این فیلد در آن ها نمایش داده نشود انتخاب کنید.</div>\n' +
            '                                                </div>\n' +
            '                                            </div>\n' +
            '\n' +
            '                                        </div>\n' +
            '                                    </fieldset>\n' +
            '                                </div>\n' +
            '                            </div>\n' +
            '                        </div>\n' +
            '                    </div>\n' +
            '                </div>\n' +
            '            </div>\n' +
            '        </div>\n' +
            '    </div>');



        let selects3 = $('.products_rules_pluss');

        $(selects3).each(function () {
            $(this).select2({
                language: {
                    searching: function () {
                        return "در حال جستجو...";
                    }
                },
                placeholder: "محصولات مورد نظر خود را انتخاب کنید.",
                ajax: {
                    url: ajaxurl,
                    data: function (params) {
                        return {
                            search: params.term,
                            action: 'get_products_plswb'
                        }
                    },
                    type: "post",
                    processResults: function (data) {
                        return {
                            results: data.items.results
                        }
                    },
                }
            });
        });
    });
});
//     $('#btn-new-field').click(function () {




// });

function remove_filed(item) {
    jQuery(item).parents().eq(4).remove();
}

function close_filed(item) {
    jQuery(item).parents().eq(3).find('.close-wrap').slideToggle();
}

function makeid(length) {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() *
            charactersLength));
    }
    return result;
}


jQuery("#plswb_sortable").sortable();
