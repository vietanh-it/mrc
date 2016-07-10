/**
 * Created by vo sy dao on 10/03/2015.
 */
var $ = jQuery.noConflict();
jQuery(document).ready(function ($) {

    $(".owl-carousel").owlCarousel({
        autoPlay: 3000,
        items : 1,
        itemsDesktop : [1199,1],
        itemsDesktopSmall : [760,1],
        navigation: false,
        pagination: false,
    });
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
        nextEffect: 'fade'
    });

    $("#owl-demo .owl-next").html('<i class="fa fa-angle-right"></i>');
    $("#owl-demo .owl-prev").html('<i class="fa fa-angle-left"></i>');

    window.fbAsyncInit = function() {
        FB.init({
            appId      : MyAjax.app_fb_id,
            xfbml      : true,
            version    : 'v2.0'
        });
    };
    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    //ajax_post_view();


    $('.vendor-slider > .slides').cycle({
        fx: 'scrollHorz',
        speed: 'fast',
        timeout: 0,
        pagerAnchorBuilder: function(idx) {
            return '.vendor-slider-pager > ul > li:eq(' + idx + ') a';
        }
    });
    $(".vendor-slider-pager ul ").owlCarousel();
    $('.lists-galary .owl-item').css('width','100%');
    
    $('.btn-pana').click(function () {
        var n = $(this).attr('data-id_next');
        var id = $(this).attr('data-id');
        $('#bimg_'+n).css('display','list-item');
        $('#bimg_'+id).css('display','none');

        $(this).closest('div').css('display','none');
        $('#box-pana_'+n).css('display','block');
    });

    if($('body').hasClass('tax-directory')){
        var i = 0;
        for (i = 0 ; i < 3 ; i++){
            var cate_id = $('#list_products_'+i).attr('data-cate_id');
            var style_id = $('#list_products_'+i).attr('data-style_id');

            load_products(4,1,cate_id,'list_products_'+i,'wp-pagenavi-'+i,'ctn_cate_'+i);
        }
    }

    $(document).delegate('.add-to-cart','mouseenter mouseleave',function () {
        var obj=$(this);
        obj.validate({
            rules: {
                size: "required"
            },
            messages: {
                size: 'Please select size.'
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
                $.ajax({
                    url: MyAjax.ajax_url,
                    type: "post",
                    dataType: 'json',
                    data: obj.serialize(),
                    beforeSend: function () {
                        $('input, button[type=submit]', obj).attr('disabled', true).css({'opacity': '0.5'});
                    },
                    success: function (data) {
                        $('input, button[type=submit]', obj).attr('disabled', false).css({'opacity': 1});
                        if (data.status == 'success') {
                            var total_items = ($('.items_number').text());
                            if(total_items !=''){
                                $('.items_number').text(parseInt(total_items) + 1);
                            }else {
                                $('.items_number').text(1);
                            }
                            swal({
                                    title: data.message,
                                    text: "<p style='font-weight: bold;color: black'>Do you want to your shopping cart page ?</p>",
                                    type: "success",
                                    showCancelButton: true,
                                    confirmButtonColor: "#424242 ",
                                    confirmButtonText: "Go to page",
                                    closeOnConfirm: false,
                                    cancelButtonText: "No, thanks",
                                    html: true
                                },
                                function () {
                                    window.location.href = data.data.url;
                                });
                        }
                        else {
                            swal({"title": "Error", "text": data.message, "type": "error", html: true});
                        }
                    }
                });
            }
        });
    });

    $('.quantity-cart').on("keyup change", function() {
        var obj = $(this);
        var product_id = obj.attr('data-product_id');
        var size = obj.attr('data-size');
        var price = obj.attr('data-price');
        var quantity = obj.val();

        if(quantity <= 0){
            quantity = 1;
            obj.val(1);
        }
        $.ajax({
            url:MyAjax.ajax_url,
            type: "post",
            dataType: 'json',
            data: {
                action : 'update_sl',
                product_id : product_id,
                size : size,
                quantity : quantity
            },
            beforeSend : function(){
            },
            success: function (data) {
                console.log(data);
                if(data.status == 'success') {
                    var new_price = parseInt(price)*parseInt(quantity);
                    var price_total = parseInt($('.price-total').attr('data-price_total'));

                    $('.price-cart-'+product_id+'-'+size).text($.number(new_price) +' VND');
                    $('.price-total').text($.number(data.data.price_total) + ' VND');
                }
                else {
                    if(data){
                        obj.val(data.data.size);
                    }
                    swal({"title": "Error", "text": data.message, "type": "error", html: true});
                }
            }
        });
    });

    $('.close-cart').click(function () {
        var obj = $(this);
        var product_id = obj.attr('data-product_id');
        var size = obj.attr('data-size');
        $.ajax({
            url:MyAjax.ajax_url,
            type: "post",
            dataType: 'json',
            data: {
                action : 'delete_cart',
                size : size,
                product_id : product_id
            },
            beforeSend : function(){
            },
            success: function (data) {
                if(data.status == 'success'){
                    $('.box-cart-'+product_id+'-'+size).remove();
                }else {
                    swal({"title": "Error", "text": data.message , "type": "error", html: true});
                }
            }
        });
    });


    var html_res = $('.ctn-register').html();
    $('#information-checkout input[name = "is_user"]').change(function () {
        obj = $(this);
        var is_user = obj.val();
        if(is_user == 'true'){
            var new_html = '' +
                '<div class="box-infor"> ' +
                '<div class="col-xs-12 col-sm-4"> ' +
                '<h3>Login</h3> ' +
                '<p>Enter e-mail and password</p> ' +
                '</div> ' +
                '<div class="col-xs-12 col-sm-4"> ' +
                '<div class="form-group"> ' +
                '<input type="email" name="email" class="form-control"  placeholder="Email"> ' +
                '</div> ' +
                '</div>' +
                '<div class="col-xs-12 col-sm-4"> ' +
                '<div class="form-group"> ' +
                '<input type="password" name="password" class="form-control"  placeholder="Password"> ' +
                '</div> ' +
                '</div>' +
                '</div>';
            $('.ctn-register').html(new_html);
        }else{
            $('.ctn-register').html(html_res)
        }
    });

    $('#form-regiter').validate({
        rules: {
            display_name: "required",
            email: "required",
            phone: "required",
            password: "required",
            birthday: "required",
            gender: "required",
            address: "required",
            zip_code: "required",
            city: "required"
        },
        messages: {
            display_name: "Please enter name",
            email: "Please enter email",
            phone: "Please enter phone",
            password: "Please enter password",
            birthday: "Please enter birthday",
            gender: "Please enter gender",
            address: "Please enter address",
            zip_code: "Please enter zip_code",
            city: "Please enter city"
        },
        errorPlacement: function(error, element) {
            element.attr('data-original-title', error.text())
                .attr('data-toggle', 'tooltip')
                .attr('data-placement', 'top');
            $(element).tooltip('show');
        },
        unhighlight: function(element) {
            $(element)
                .removeAttr('data-toggle')
                .removeAttr('data-original-title')
                .removeAttr('data-placement')
                .removeClass('error');
            $(element).unbind("tooltip");
        }
    });

    $('#form_account').validate({
        rules: {
            display_name: "required",
            user_email: "required",
            phone: "required",
            birthday: "required",
            gender: "required",
            address: "required",
            zip_code: "required",
            city: "required"
        },
        messages: {
            display_name: "Please enter name",
            user_email: "Please enter email",
            phone: "Please enter phone",
            birthday: "Please enter birthday",
            gender: "Please enter gender",
            address: "Please enter address",
            zip_code: "Please enter zip_code",
            city: "Please enter city"
        },
        errorPlacement: function(error, element) {
            element.attr('data-original-title', error.text())
                .attr('data-toggle', 'tooltip')
                .attr('data-placement', 'top');
            $(element).tooltip('show');
        },
        unhighlight: function(element) {
            $(element)
                .removeAttr('data-toggle')
                .removeAttr('data-original-title')
                .removeAttr('data-placement')
                .removeClass('error');
            $(element).unbind("tooltip");
        }
    });

    $('#information-checkout').validate({
        rules: {
            first_name: "required",
            last_name: "required",
            email: "required",
            phone: "required",
            password: "required",
            birthday: "required",
            gender: "required",
            address: "required",
            zip_code: "required",
            city: "required",
            password_confirm: {
                required: true,
                equalTo: "#password_v2"
            }
        },
        messages: {
            first_name: "Please enter first name",
            last_name: "Please enter last name",
            email: "Please enter email",
            phone: "Please enter phone",
            password: "Please enter password",
            birthday: "Please enter birthday",
            gender: "Please enter gender",
            address: "Please enter address",
            zip_code: "Please enter Zip / Postal code",
            password_confirm: {
                required: " Please enter password ",
                equalTo: "Retype password do not match"
            },
            city: "Please enter city"
        },
        errorPlacement: function(error, element) {
            element.attr('data-original-title', error.text())
                .attr('data-toggle', 'tooltip')
                .attr('data-placement', 'top');
            $(element).tooltip('show');
        },
        unhighlight: function(element) {
            $(element)
                .removeAttr('data-toggle')
                .removeAttr('data-original-title')
                .removeAttr('data-placement')
                .removeClass('error');
            $(element).unbind("tooltip");
        }
    });

    $("#birthday").datepicker({
        dateFormat: 'dd/mm/yy',
         changeMonth: true,
         changeYear: true,
         yearRange: '-60:-18'
    });

    $('.special .title a').click(function () {
        var action = $(this).attr('data-action');
        if(action == 'show'){
            $('.special').addClass('special-2');
            $(this).attr('data-action','hide');
        }else {
            $('.special').removeClass('special-2');
            $(this).attr('data-action','show');
        }
    })
});


function ajax_post_view(){
    var cls = $('body').attr("class");
    var postid = 0;
    if( cls.indexOf("postid") != -1 ){
        var index = cls.indexOf("postid");
        cls = cls.substr(index);
        postid = cls.substr(0, cls.indexOf(" "));
        postid = postid.split("-");
        postid = parseInt(postid[1]);
    }
    else if( cls.indexOf("page-id") != -1 ){
        var index = cls.indexOf("page-id");
        cls = cls.substr(index);
        postid = cls.substr(0, cls.indexOf(" "));
        postid = postid.split("-");
        postid = parseInt(postid[2]);
    }

    if( postid ){
        $.ajax({
            type: "get",
            url: MyAjax.ajax_url,
            data: ({
                action: "ajax_post_view",
                post_id : postid
            })
        })
    }
}


function load_products(limit,page,cate_id,container,ctn_pagination,ctn_cate) {
    $.ajax({
        url: MyAjax.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'ajaxGetProducts',
            limit: limit,
            cate_id: cate_id,
            page: page
        },
        success: function(respon) {
            if(respon.data){
                var visiblePages = 5;
                var total = respon.total;
                var totalPages = parseInt(total) / parseInt(limit);
                if (parseInt(total) % parseInt(limit) != 0){
                    totalPages = totalPages + 1;
                }

                $('#'+ctn_pagination).twbsPagination({
                    totalPages: totalPages,
                    visiblePages: visiblePages,
                    first: false,
                    last : false,
                    prev : '<span class="fa fa-angle-left"></span>',
                    next : '<span class="fa fa-angle-right"></span>',
                    paginationClass : 'pagination pagination-custom',
                    onPageClick: function (event, page1) {
                        $.ajax({
                            url: MyAjax.ajax_url,
                            type: 'post',
                            dataType: 'json',
                            data: {
                                action: 'ajaxGetProducts',
                                limit: limit,
                                cate_id: cate_id,
                                page: page1
                            },
                            success: function(respon) {
                                $('#'+container).html('');
                                var url_ingaddtocart = $('#'+container).attr('data-url_ingaddtocart');
                                if(respon.data){
                                    $.each(respon.data, function(key, value) {
                                        var html = html_product(value,url_ingaddtocart);
                                        $('#'+container).append(html);
                                    });
                                }
                            }
                        });
                    }
                });
            }
            else {
                $('#'+ctn_cate).css('display','none');
            }
        }
    });
}


function html_product(v,url_ingaddtocart) {

    var  html = '<div class="col-xs-12 col-sm-3">' +
        '<div class="box-sp" >' +
        '<div class="images"> ' +
        '<a href="'+v.permalink+'" title="'+v.post_title+'">' +
        '<img src="'+ v.featured.product_featured +'" alt="'+v.post_title+'" class="main-img"> ' +
        '<img src="'+ v.img1 +'" alt="'+v.post_title+'" class="nomarl-img"> ' +
        '</a> ' +
        '</div> ' +
        '<div class="desc">Code '+v.sku+' - '+v.color+'</div> ' +
        '<div class="price"> Price : '+$.number(v.price)+' VND</div> ' +
        '<form class="add-to-cart add-to-cart-quick">' +
        '<div class="param-size">' +
        '<select name="size" id="size">' +
        ' <option value=""> ---Choose size --- </option>' ;
    $.each(v.size, function(key, value) {
        var status = 'Available';
        if(parseInt(value) < 1){
            status = 'Solt';
        }
        html+='<option value="'+key+'"> '+key+' ('+status+') </option>'
    });
    html +='</select>' +
        '</div>' +
        '<input type="hidden" value="1" name="quantity">' +
        '<input type="hidden" name="action" value="ajax_addtocart">' +
        '<input type="hidden" name="product_id" value="'+v.ID+'">' +
        '<button type="submit">Add to cart <img src="'+url_ingaddtocart+'" alt=""></button>' +
        '</form>' +
        '</div> ' +
        '</div>';

    return html;
}