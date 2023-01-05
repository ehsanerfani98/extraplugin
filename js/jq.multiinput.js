jQuery(document).ready(function ($) {



    $('.products_rules').select2({
        language: {
            searching: function() {
                return "در حال جستجو...";
            }
        },        placeholder: "محصولات مورد نظر خود را انتخاب کنید.",
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
            searching: function() {
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


    $('#btn-new-field').click(function () {


        let unique_id = makeid(20);

        let fields = $('#wrap-fields').contents().clone();

        $(fields).find('#ext_title').attr({'name': 'ext_options[data]' + '[' + unique_id + '][title]', 'required':'required'});
        $(fields).find('#ext_help').attr('name', 'ext_options[data]' + '[' + unique_id + '][help]');
        $(fields).find('#ext_required').attr('name', 'ext_options[data]' + '[' + unique_id + '][required]');
        $(fields).find('#ext_type').attr({'name': 'ext_options[data]' + '[' + unique_id + '][type]', 'required':'required'});
        $(fields).find('#ext_price').attr('name', 'ext_options[data]' + '[' + unique_id + '][price]');
        $(fields).find('#ext_value_select').attr('name', 'ext_options[data]' + '[' + unique_id + '][value_select]');
        $(fields).find('#not_show_products_rules').attr('name', 'ext_options[data]' + '[' + unique_id + '][not_show_products_rules][]');
        $(fields).find('#ext_disable_org_show_products_rules').attr('name', 'ext_options[data]' + '[' + unique_id + '][disable_org_show_products_rules]');
        $(fields).find('#inside_show_products_rules').attr('name', 'ext_options[data]' + '[' + unique_id + '][inside_show_products_rules][]');


        $(fields).find('.new_select').addClass('products_rules_pluss');

        $('#plswb_sortable').prepend(fields);

        let selects3 = $('.products_rules_pluss');

        $(selects3).each(function () {
            $(this).select2({
                language: {
                    searching: function() {
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


jQuery( "#plswb_sortable" ).sortable();