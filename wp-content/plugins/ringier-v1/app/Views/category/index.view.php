<?php
get_header();

var_dump($category, $listArticles);
?>

category view

<button class="deleteBlog" data-object-id="2">Delete</button>
<script>
    var $ = jQuery.noConflict();
    $(document).ready(function($) {
        $(".deleteBlog").on("click", function(e){
            var $objID = $(this).data('object-id');

            //alert($objID);

            $.ajax({
                type: "POST",
                url: ajaxurl,
                dataType: 'json',
                data: ({
                    action: 'ringier_ajax_handler',
                    method: 'delete',
                    objectID : $objID
                }),
                success: function(data){
                    console.log(data);

                    // error
                    if (data.status=='error') {
                        alert(data.message);
                    } else {
                        alert(data.message);
                    }
                },
                error: function(data){
                    console.error(data);
                }
            });
        });
    });
</script>
<?php get_footer() ?>