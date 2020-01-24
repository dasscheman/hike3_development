
$(function() {
    if($('#eventnames-status').val() != 3) {
        $('#eventnames-max_time').attr('disabled',true);
    }
    if($('#eventnames-status').val() == 3) {
        $('#eventnames-max_time').attr('disabled',false);
    }

    $('#eventnames-status').change(function(){
        if ($(this).val() != 3 || $(this).val() != '') {
            $('#eventnames-max_time').attr('disabled',true);
        }
        if ($(this).val() == 3) {
            $('#eventnames-max_time').attr('disabled',false);
        }
    });
});
