(function( $ ) {
    jQuery(document).ready(function () {
        jQuery('.add-repeater').repeater({
            initEmpty: true,
            show: function () {
                jQuery(this).slideDown();
                jQuery(".cs_popup_country_code").chosen({disable_search_threshold: 10, width: "100%"});
            },
            hide: function (deleteElement) {
                if(confirm('Are you sure you want to delete this element?')) {
                    jQuery(this).slideUp(deleteElement);
                }
            },
            ready: function (setIndexes) {

            }
        });
        jQuery('.edit-repeater').repeater({
            show: function () {
                jQuery(this).slideDown();
            },
            hide: function (deleteElement) {
                let this_ele = $(this);
                if(confirm('Are you sure you want to delete this row?')) {
                    $(this_ele).find('.delete-cr').attr('disabled', 'disabled');
                    var cr_id = $(this).data('repeater-item');
                    jQuery.ajax({
                        url: custom_vars.ajax_url+"?action=delete_country_redirect_url",
                        type:"POST",
                        data:{ cr_id : cr_id },
                        success:function(response){
                            
                            alert(response.message);
                            $(deleteElement).slideUp(deleteElement);
                            $(this_ele).find('.delete-cr').removeAttr('disabled');
                        },
                        error:function(response){
                            $(this_ele).find('.delete-cr').removeAttr('disabled');
                        }
                    });
                }
            },
            ready: function (setIndexes) {
                $dragAndDrop.on('drop', setIndexes);
            }
        });
        jQuery(".cs_popup_country_code").chosen({disable_search_threshold: 10, width: "100%"});
    });
})( jQuery );