/**
 * Created by vo sy dao on 10/03/2015.
 */
var $ = jQuery.noConflict();
jQuery(document).ready(function ($) {

    $(".owl-carousel").owlCarousel({
        autoPlay: 5000,
        items: 1,
        itemsDesktop: [1199, 1],
        itemsDesktopSmall: [760, 1],
        navigation: true,
        pagination: true
    });

    $(".owl-carousel-2").owlCarousel({
        autoPlay: 5000,
        items: 3,
        itemsDesktop: [1199, 3],
        itemsDesktopSmall: [760, 3],
        navigation: true,
        pagination: false
    });

    $(".list-galary").owlCarousel({
        autoPlay: 5000,
        items: 1,
        itemsDesktop: [1199, 1],
        itemsDesktopSmall: [760, 1],
        navigation: true,
        pagination: false
    });

    $(".owl-carousel .owl-next").html('<i class="fa fa-angle-right" aria-hidden="true"></i>');
    $(".owl-carousel .owl-prev").html('<i class="fa fa-angle-left" aria-hidden="true"></i>');

    $(".datepicker").datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'dd M yy'
    });
    /* $('.month-year-input').attr('readonly', true);
     $('.month-year-input').MonthPicker({Button: false});*/


    $("select.select-2").select2({
        formatResult: function (object, container, query, escapeMarkup) {
            var markup = [];
            window.Select2.util.markMatch(object.text, query.term, markup, escapeMarkup);
            if ($(object.element).data('group') == 1) {
                $(container).css({'font-weight': 'bold'});
            } else {
                $(container).css({'padding-left': 20});
            }
            return markup.join("");
        }
    });
    $('select.select-2').on("select2-open", function (e) {
        e.preventDefault();
        $(".select2-results").mCustomScrollbar();
    });


    window.fbAsyncInit = function () {
        FB.init({
            appId: MyAjax.app_fb_id,
            xfbml: true,
            version: 'v2.7'
        });
    };

    $("a.auto_fancybox, a.fancybox").fancybox({
        helpers: {
            title: {
                type: 'over'
            },
            overlay: {
                speedOut: 0
            }
        },
        padding: 10,
        prevEffect: 'fade',
        nextEffect: 'fade',
        afterShow: function () {
            /*$('.fancybox-wrap').swipe({
             swipe: function (event, direction) {
             if (direction === 'left' || direction === 'up') {
             $.fancybox.prev(direction);
             } else {
             $.fancybox.next(direction);
             }
             }
             });*/
        }
    });

    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {
            return;
        }
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));


    $('.btn-show-journey').click(function () {
        var obj = $(this);
        var journey_type_id = obj.attr('data-journey_type');

        $.ajax({
            type: "post",
            url: ajaxurl,
            dataType: 'json',
            data: ({
                action: "ajax_handler_journey",
                method: "GetJourneyByJourneyType",
                journey_type_id: journey_type_id
            }),
            beforeSend: function () {
            },
            success: function (data) {
                var html = '';
                if (data.total > 0) {
                    //console.log(data);
                    var result = data.data;
                    $.each(result, function (key, value) {
                        var text = '';
                        if (value.min_price_fm != value.min_price_offer_fm) {
                            text = '<span style="text-decoration: line-through;color: burlywood;">' + value.min_price_fm + '</span>';
                        }
                        var htm_offer = '';
                        if (value.is_offer) {
                            var offer_info = value.journey_type_info.offer_main_info;
                            var img_ticket = $('#ctn-list-journey').attr('data-img_ticket');
                            htm_offer = '<b>' + offer_info.post_title + '<br>' + offer_info.month_year + ' and save ' + offer_info.promotion + '%.</b> <img src="' + img_ticket + '"> ';
                        }

                        var class_navi = 'downstream';
                        if (value.navigation == 'upstream') {
                            class_navi = 'upstream';
                        }

                        html += '<tr class="' + class_navi + '"> ' +
                            '<td> <b>' + value.departure_fm + '</b> </td> ' +
                            '<td> ' + value.journey_type_info.destination_info.post_title + '<br> '
                            + value.journey_type_info.nights + ' nights<br> ' + htm_offer + '</td> ' +
                            '<td style="text-decoration: underline"> <a href="' + value.journey_type_info.ship_info.permalink + '" target="_blank" style="color: rgb(84, 84, 84);">' + value.journey_type_info.ship_info.post_title + '</a></td> ' +
                            '<td> ' + value.navigation + ' </td> ' +
                            '<td> from ' + text + ' <b style="color: #e4a611">US$' + value.min_price_offer_fm + '</b> pp <br>based on twin cabin </td> ' +
                            '<td> <a href="' + value.permalink + '" class="bnt-jn">Select</a>' +
                            'Some availability </td> ' +
                            '</tr>';
                    });
                } else {
                    html += '<tr> <td colspan="6"> No result is found</td> </tr>';
                }

                //console.log(html);

                $('.ctn-list-journey tbody').html(html);
                $('#ctn-list-journey').fadeIn();
                $('html, body').animate({
                    scrollTop: $('#ctn-list-journey').offset().top - 50
                }, 500);
            }
        })
    });

    $('.order-navigation').click(function () {
        var obj = $(this);
        var navigation = obj.attr('data-navigation');
        $('.order-navigation').removeClass('active');
        obj.addClass('active');
        switch (navigation) {
            case 'all':
                $('tr.upstream').fadeIn();
                $('tr.downstream').fadeIn();
                break;
            case 'upstream':
                $('tr.upstream').fadeIn();
                $('tr.downstream').fadeOut();
                break;
            case 'downstream':
                $('tr.downstream').fadeIn();
                $('tr.upstream').fadeOut();
                break;
        }
    });

    var is_active_menu = false;
    $('.btn-menu-jn').click(function () {
        if (is_active_menu == false) {
            $('.ctn-btn-action').fadeIn();
            $(this).css('padding-right', '30px');
            is_active_menu = true;
        } else {
            $('.ctn-btn-action').fadeOut();
            is_active_menu = false;
            $(this).css('padding-right', '0');
        }
    });

    $('.quick-search-journey-form').submit(function (e) {
        var _destination = $('select[name = "_destination"]').val();
        var _month = $('select[name = "_month"]').val();
        var _port = $('select[name = "_port"]').val();
        var _ship = $('select[name = "_ship"]').val();

        if (_ship == '' && _destination == '' && _month == '' && _port == '') {
            e.preventDefault();

            swal({'title': 'Warning', "text": 'Please choose one of the options', "type": "warning", html: true});
        }
    });

    $('.connect-email').validate({
        ignore: [],
        rules: {
            c_email: {
                required: true,
                email: true
            }
        },
        messages: {
            c_email: {
                required: 'Please enter your email',
                email: 'Email not email'
            }
        },
        errorPlacement: function (error, element) {
            element.attr('data-original-title', error.text())
                .attr('data-toggle', 'tooltip')
                .attr('data-placement', 'top');
            $(element).tooltip('show');
        },
        unhighlight: function (element) {
            $(element)
                .removeAttr('data-toggle')
                .removeAttr('data-original-title')
                .removeAttr('data-placement')
                .removeClass('error');
            $(element).unbind("tooltip");
        },
        submitHandler: function (form) {
            var objfrm = $(form);
            $.ajax({
                type: "post",
                url: ajaxurl,
                dataType: 'json',
                data: objfrm.serialize(),
                beforeSend: function () {
                    $('input, button[type=submit]', objfrm).attr('disabled', true).css({'opacity': '0.5'});
                },
                success: function (data) {
                    $('input, button[type=submit]', objfrm).attr('disabled', false).css({'opacity': 1});
                    if (data.status == 'success') {
                        swal({
                            "title": "Success",
                            "text": "<p style='color: #008000;font-weight: bold'>" + data.message + "</p>",
                            "type": "success",
                            html: true
                        });
                        objfrm.trigger("reset");
                    }
                    else {
                        var result = data.message;
                        var htmlErrors = "";
                        if (result.length > 0) {
                            htmlErrors += "<ul style='color: red'>";
                            for (var i = 0; i < result.length; i++) {
                                htmlErrors += "<li style='list-style: none'>" + result[i] + "</li>";
                            }
                            htmlErrors += "</ul>";
                        }
                        swal({"title": "Error", "text": htmlErrors, "type": "error", html: true});
                    }
                }
            });
            return false;
        }
    });

    $('.contact-us').validate({
        ignore: [],
        rules: {
            contact_full_name: 'required',
            contact_country: 'required',
            contact_phone: 'required',
            contact_subject: 'required',
            contact_message: 'required',
            contact_email: {
                required: true,
                email: true
            }
        },
        messages: {
            contact_full_name: 'Please enter your name.',
            contact_country: 'Please enter your country.',
            contact_phone: 'Please enter your phone number.',
            contact_subject: 'Please enter your message subject .',
            contact_message: 'Please enter your message.',
            contact_email: {
                required: 'Please enter your email',
                email: 'Email not valid.'
            }
        },
        errorPlacement: function (error, element) {
            element.attr('data-original-title', error.text())
                .attr('data-toggle', 'tooltip')
                .attr('data-placement', 'top');
            $(element).tooltip('show');
        },
        unhighlight: function (element) {
            $(element)
                .removeAttr('data-toggle')
                .removeAttr('data-original-title')
                .removeAttr('data-placement')
                .removeClass('error');
            $(element).unbind("tooltip");
        },
        submitHandler: function (form) {
            var objfrm = $(form);
            $.ajax({
                type: "post",
                url: ajaxurl,
                dataType: 'json',
                data: objfrm.serialize(),
                beforeSend: function () {
                    $('input, button[type=submit]', objfrm).attr('disabled', true).css({'opacity': '0.5'});
                },
                success: function (data) {
                    $('input, button[type=submit]', objfrm).attr('disabled', false).css({'opacity': 1});
                    if (data.status == 'success') {
                        swal({
                            "title": "Success",
                            "text": "<p style='color: #008000;font-weight: bold'>" + data.message + "</p>",
                            "type": "success",
                            html: true
                        });
                        objfrm.trigger("reset");
                    }
                    else {
                        var result = data.message;
                        var htmlErrors = "";
                        if (result.length > 0) {
                            htmlErrors += "<ul style='color: red'>";
                            for (var i = 0; i < result.length; i++) {
                                htmlErrors += "<li style='list-style: none'>" + result[i] + "</li>";
                            }
                            htmlErrors += "</ul>";
                        }
                        swal({"title": "Error", "text": htmlErrors, "type": "error", html: true});
                    }
                }
            });
            return false;
        }
    });


    var flag_alert = true;
    $('.refer-friend').fancybox({
        helpers: {
            title: {
                type: 'over'
            },
            overlay: {
                speedOut: 0
            }
        },
        padding: 10,
        beforeShow: function () {
        },
        afterLoad: function () {
            flag_alert = false;
            $('#form-refer-friend').on("hover", function () {
                var obj = $(this);
                obj.validate({
                    ignore: [],
                    rules: {
                        email_friend: {
                            required: true,
                            email: true
                        }
                    },
                    messages: {
                        email_friend: {
                            required: 'Please enter your email',
                            email: 'Email not email'
                        }
                    },
                    errorPlacement: function (error, element) {
                        if (element.attr('name') == "cate_favourite") {
                            element.parent().attr('data-original-title', error.text())
                                .attr('data-toggle', 'tooltip')
                                .attr('data-placement', 'top');
                            $(element).parent().tooltip('show');
                        }
                        else {
                            element.attr('data-original-title', error.text())
                                .attr('data-toggle', 'tooltip')
                                .attr('data-placement', 'top');
                            $(element).tooltip('show');
                        }

                    },
                    unhighlight: function (element) {
                        $(element)
                            .removeAttr('data-toggle')
                            .removeAttr('data-original-title')
                            .removeAttr('data-placement')
                            .removeClass('error');
                        $(element).unbind("tooltip");
                    },
                    submitHandler: function (form) {
                        $.ajax({
                            type: "post",
                            url: ajaxurl,
                            dataType: 'json',
                            data: obj.serialize(),
                            beforeSend: function () {
                                $('input, button[type=submit]', obj).attr('disabled', true).css({'opacity': '0.5'});
                            },
                            success: function (data) {
                                $('input, button[type=submit]', obj).attr('disabled', false).css({'opacity': '1'});
                                if (data.status == "success") {
                                    swal({
                                        "title": "Success",
                                        "text": "<p style='color: #008000;font-weight: bold'>" + data.message + "</p>",
                                        "type": "success",
                                        html: true
                                    });

                                    flag_alert = true;
                                    parent.jQuery.fancybox.close();
                                }
                                else {
                                    var result = data.message;
                                    var htmlErrors = "";
                                    if (result.length > 0) {
                                        htmlErrors += "<ul style='color: red'>";
                                        for (var i = 0; i < result.length; i++) {
                                            htmlErrors += "<li style='list-style: none'>" + result[i] + "</li>";
                                        }
                                        htmlErrors += "</ul>";
                                    }
                                    swal({"title": "Error", "text": htmlErrors, "type": "error", html: true});
                                }
                            }
                        });
                        return false;
                    }
                });
            });
        }
    });

    $('.btn-kep-offer').fancybox({
        helpers: {
            title: {
                type: 'over'
            },
            overlay: {
                speedOut: 0
            }
        },
        padding: 10,
        beforeShow: function () {
        },
        afterLoad: function () {
            flag_alert = false;
            $('#form-kep-offer').on("hover", function () {
                var obj = $(this);
                obj.validate({
                    ignore: [],
                    rules: {
                        your_email: {
                            required: true,
                            email: true
                        }
                    },
                    messages: {
                        your_email: {
                            required: 'Please enter your email',
                            email: 'Email not email'
                        }
                    },
                    errorPlacement: function (error, element) {
                        if (element.attr('name') == "cate_favourite") {
                            element.parent().attr('data-original-title', error.text())
                                .attr('data-toggle', 'tooltip')
                                .attr('data-placement', 'top');
                            $(element).parent().tooltip('show');
                        }
                        else {
                            element.attr('data-original-title', error.text())
                                .attr('data-toggle', 'tooltip')
                                .attr('data-placement', 'top');
                            $(element).tooltip('show');
                        }

                    },
                    unhighlight: function (element) {
                        $(element)
                            .removeAttr('data-toggle')
                            .removeAttr('data-original-title')
                            .removeAttr('data-placement')
                            .removeClass('error');
                        $(element).unbind("tooltip");
                    },
                    submitHandler: function (form) {
                        $.ajax({
                            type: "post",
                            url: ajaxurl,
                            dataType: 'json',
                            data: obj.serialize(),
                            beforeSend: function () {
                                $('input, button[type=submit]', obj).attr('disabled', true).css({'opacity': '0.5'});
                            },
                            success: function (data) {
                                $('input, button[type=submit]', obj).attr('disabled', false).css({'opacity': '1'});
                                if (data.status == "success") {
                                    swal({
                                        "title": "Success",
                                        "text": "<p style='color: #008000;font-weight: bold'>" + data.message + "</p>",
                                        "type": "success",
                                        html: true
                                    });

                                    flag_alert = true;
                                    parent.jQuery.fancybox.close();
                                }
                                else {
                                    var result = data.message;
                                    var htmlErrors = "";
                                    if (result.length > 0) {
                                        htmlErrors += "<ul style='color: red'>";
                                        for (var i = 0; i < result.length; i++) {
                                            htmlErrors += "<li style='list-style: none'>" + result[i] + "</li>";
                                        }
                                        htmlErrors += "</ul>";
                                    }
                                    swal({"title": "Error", "text": htmlErrors, "type": "error", html: true});
                                }
                            }
                        });
                        return false;
                    }
                });
            });
        }
    });

    $('.show-answer').click(function () {
        var obj = $(this);
        obj.closest('.box-qa').find('.answer').fadeIn();
        obj.closest('.box-qa').find('.hide-answer').css('display', 'block');
        obj.css('display', 'none');
    });

    $('.hide-answer').click(function () {
        var obj = $(this);
        obj.closest('.box-qa').find('.answer').fadeOut();
        obj.closest('.box-qa').find('.show-answer').css('display', 'block');
        obj.css('display', 'none');
    });

    $('.back-top').click(function (e) {
        $("html, body").animate({scrollTop: 0}, 600);
        return false;
    });
});

function switch_loading(is_loading) {
    if (is_loading) {
        $('.loading-wrapper').fadeIn();
    } else {
        $('.loading-wrapper').fadeOut();
    }
}