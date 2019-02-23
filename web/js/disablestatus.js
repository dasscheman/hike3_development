
$(function() {
    $( "#no_stations_warning" ).show();
    $('#eventnames-start_time_all_groups-datetime').hide();
    if($('#eventnames-status').val() != 3) {
        $('#eventnames-active_day').attr('disabled',true);
        $('#eventnames-max_time').attr('disabled',true);
        $('#eventnames-start_all_groups').attr('disabled',true);
    }
    if($('#eventnames-status').val() == 3) {
        $('#eventnames-active_day').attr('disabled',false);
        $('#eventnames-max_time').attr('disabled',true);
        $('#eventnames-start_all_groups').attr('disabled',true);
        $('#eventnames-start_time_all_groups-datetime').hide();
        for (i = 0; i < posten.length; i++) {
            if(posten[i]['date'] == $('#eventnames-active_day').val()) {
                $('#eventnames-max_time').attr('disabled',false);
                $('#eventnames-start_all_groups').attr('disabled',false);
                $( "#no_stations_warning" ).hide();
                break;
            }
        }
    }

    if($('#eventnames-start_all_groups').prop("checked") == 1) {
        $('#eventnames-start_time_all_groups-datetime').show();
    }

    $('#eventnames-status').change(function(){
        if ($(this).val() != 3 || $(this).val() != '') {
            $('#eventnames-active_day').attr('disabled',true);
            $('#eventnames-max_time').attr('disabled',true);
            $('#eventnames-start_all_groups').attr('disabled',true);
            $('#eventnames-start_time_all_groups-datetime').hide();
        }
        if ($(this).val() == 3) {
            $('#eventnames-active_day').attr('disabled',false);
            if(posten.length == 0) {
                $( "#no_stations_warning" ).show();
            }
        }
    });

    $('#eventnames-active_day').change(function(){
        $( "#no_stations_warning" ).show();
        for (i = 0; i < posten.length; i++) {
            if(posten[i]['date'] == $('#eventnames-active_day').val()) {
                $('#eventnames-max_time').attr('disabled',false);
                $('#eventnames-start_all_groups').attr('disabled',false);
                $( "#no_stations_warning" ).hide();
                break;
            }
        }
    });
    $('#eventnames-start_all_groups').change(function(){
        if ($(this).prop("checked") == 0 || $(this).prop("checked") == '') {
            $('#eventnames-start_time_all_groups-datetime').hide();
        }
    });
    $('#eventnames-start_all_groups').change(function(){
        if ($(this).prop("checked") == 1) {
            $('#eventnames-start_time_all_groups-datetime').show();
        }
    });
});
