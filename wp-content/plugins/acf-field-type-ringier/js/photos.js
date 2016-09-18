jQuery(document).ready(function($) {
    var contain_error = $('<p class="error"/>');
    var post_id = $('#fileupload').data('post_id');
    $('#fileupload').fileupload({
        url: ajaxurl,
        dataType: 'json',
        formData: {
            action: 'r_photo_upload_custom_photo',
            post_id: post_id
        },
        maxFileSize: 2 * 1024 * 1024,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        messages: {
            maxNumberOfFiles: 'Bạn chỉ được chọn 5 hình.',
            acceptFileTypes: 'Hình này không hợp lệ.',
            maxFileSize: 'Kích thước hình phải nhỏ hơn 2M'
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10) - 10;
            $('#progress .progress-bar').css('width', progress + '%');
        },
        stop: function () {
            $('#progress .progress-bar').css(
                'width', '100%'
            );
            $('#progress .progress-bar').fadeOut(500, function () {
                $(this).css('width', 0);
            }).fadeIn();
        },
        done: function (e, data) {}
    })
        .on('fileuploadprocessalways', function (e, data) {

            var index = data.index,
                file = data.files[index];
            if (file.error) {
                contain_error.append('<span>' + file.name + ' : ' + file.error + '</span><br/>');
                contain_error.insertBefore($('#progress'));
            }

        })
        .on('fileuploaddone', function (e, data) {
            var str = '';
            $.each(data.result, function (index, file) {
                if (file.error) {
                    contain_error.append('<span>Đăng hình không thành công.</span><br/>');
                    contain_error.insertBefore($('#progress'));
                } else {
                    photo_id = file.photo_id;
                    src = file.url + "/" + file.thumbnail;
                    var caption  = 'No caption';
                    html = '<div class="has-image" id="div-photo-id-' + photo_id + '" >' +
                        ' <div class = "img">' +
                        '<img  src="' + src + '" width="150"><br/>' +
                        '<a class="update_caption" href="#frm-caption-'+photo_id+'" data-photo_id="'+photo_id+'" ">Update caption</a> ' +
                        '<span class ="text-caption-'+photo_id+'">'+caption+'</span> ' +
                        '<div id="frm-caption-'+photo_id+'" style="display:none" class ="frm-caption"> ' +
                        '<label>New caption</label> ' +
                        '<input type = "text" name = "cation" id = "input-cation-'+photo_id+'" value = "'+caption+'" /> ' +
                        '<a href = "javascript:void(0)" class="btn_update_caption" data-photo_id="'+photo_id+'">Update</a> ' +
                        '</div> ' +
                        '</div>' +
                        '<a class="delete_photo" href="javascript:void(0)" data-photo_id="' + photo_id + '"><i class="fa fa-times"></i> Delete </a>' +
                        '</div>';
                    $('#list_photo').append(html);
                }
            });

        })

        .on('fileuploadfail', function (e, data) {
        })
        .prop('disabled', !$.support.fileInput);


    $(document).on('click', '.delete_photo', function (event) {
        if (window.confirm("Bạn muốn xóa tấm hình này?")) {
            var self = $(this),
                photo_id = self.data('photo_id');
            $.ajax({
                type: "post",
                dataType: 'json',
                context: $(this),
                url: ajaxurl,
                data: ({
                    action: "r_photo_delete_photo",
                    photo_id: photo_id
                }),
                success: function (data) {
                    if (data.status == 1) {
                        $(".acf-image-uploader").addClass("active");
                        $("#div-photo-id-" + data.photo_id).remove();
                    } else {
                        alert("Xóa hình không thành công.");
                    }
                },
                error: function (data) {
                    alert('Có lỗi xảy ra, vui lòng thử lại sau!');
                }
            });
        }
    });


    $('.update_caption').fancybox({
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
            $(document).delegate(".btn_update_caption", "click", function() {
                var obj = $(this);
                var photo_id = obj.attr('data-photo_id');
                var caption = $('#input-cation-'+photo_id).val();
                $.ajax({
                    type: "post",
                    dataType: 'json',
                    context: $(this),
                    url: ajaxurl,
                    data: ({
                        action: "r_photo_update_caption",
                        photo_id: photo_id,
                        caption: caption
                    }),
                    success: function (data) {
                        if (data.status == 'success') {
                            parent.jQuery.fancybox.close();
                            $('.text-caption-'+photo_id).text(caption);
                            alert("Update caption thành công.");
                        } else {
                            alert("Update caption không thành công.");
                        }
                    },
                    error: function (data) {
                        alert('Có lỗi xảy ra, vui lòng thử lại sau!');
                    }
                });
            })
        }
    });
});