$(function() {
    $('.advanced_search__type_fields').hide();
    switch ($('#rooty_torrentbundle_torrentadvancedfiltertype_type').val()) {
        case '3':
            $('#advanced_search__game_fields').fadeIn();
            break;
        case '4':
            $('#advanced_search__movie_fields').fadeIn();
            break;
    }
    $('.advanced_search__type_fields:hidden').val('');

    $('#rooty_torrentbundle_torrentadvancedfiltertype_type').change(function() {
        switch($(this).val()) {
            case '3':
                $('.advanced_search__type_fields:visible').fadeOut(function() {$('#advanced_search__game_fields').fadeIn()});
                break;
            case '4':
                $('.advanced_search__type_fields:visible').fadeOut(function() {$('#advanced_search__movie_fields').fadeIn()});
                break;
        }
        $('.advanced_search__type_fields:hidden :input').val('');
    });

    $('a.update_file').click(function() {
        $('a.update_file_undo').click(function() {
            $(this).parent().hide();
            $(this).parent().siblings('.file_update').show();
            $(this).parent().html($(this).parent().html());
            return false;
        });
        
        $(this).parent().hide();
        $(this).parent().siblings('.file_input').show();
        return false;
    });
    
    
    /* TorrentBundle screenshot adding */
    var addScreenshot = $('<a class="add_screenshot" href="#">Добавить</a>').click(function() {
        var newWidget = $(this).parent().attr('data-prototype');
        var screenshotCount = $('#rooty_torrentbundle_typeformtype_torrent_screenshots').children('div').size();
        newWidget = newWidget.replace(/\$\$name\$\$/g, screenshotCount);
        $(newWidget).hide().insertBefore(addScreenshot).fadeIn();
        return false;
    });
    $('#rooty_torrentbundle_typeformtype_torrent_screenshots').append(addScreenshot);
    $('a.remove_screenshot').livequery('click', function () {
        $(this).parent().fadeOut(function() {$(this).remove();});
        return false;
    });
    
    $('.group1').colorbox({rel:'group1', maxWidth: '95%', maxHeight: '95%', scalePhotos: true});
    
    $('#torrent__screenshots__carousel').jcarousel();
    
    minval = parseInt($('#rooty_torrentbundle_torrentadvancedfiltertype_size_min').val());
    maxval = parseInt($('#rooty_torrentbundle_torrentadvancedfiltertype_size_max').val());
    from = 0.22756*Math.log(1+minval/4026531840);
    to = 0.22756*Math.log(1+maxval/4026531840);
    $("#size_slider").slider({
        range: true,
        values: [from, to],
        create: function( event, ui ) {
            $('#size_from').html(calcFileSize(minval));
            $('#size_to').html(calcFileSize(maxval));
        },
        slide: function( event, ui ) {
            from = 322122547200 * (Math.exp(ui.values[0]*4.39445)-1) / 80;
            to = 322122547200 * (Math.exp(ui.values[1]*4.39445)-1) / 80;
            $('#rooty_torrentbundle_torrentadvancedfiltertype_size_min').val(from.toFixed(0));
            $('#rooty_torrentbundle_torrentadvancedfiltertype_size_max').val(to.toFixed(0));
            $('#size_from').html(calcFileSize(from));
            $('#size_to').html(calcFileSize(to));
        },
	min: 0,
	max: 1,
        step: 0.01
    });
    
    /* Open specific tab based on hash */
    if (window.location.hash) {
        $(".nav-tabs li a[href='#" + window.location.hash.substr(5) + "']").click();
    } else {
        if($(".nav-tabs li.active a").length > 0) {
            location.hash = 'tab' + $(".nav-tabs li.active a").attr('href');
        }
    }
    
    $(window).bind('hashchange', function() {
        $(".nav-tabs li a[href='#" + window.location.hash.substr(5) + "']").click();
    });
});

function calcFileSize(size) {
    var units = Array('B', 'KB', 'MB', 'GB', 'TB');
    var unitIndex = 0;
    while (size > 1024 && unitIndex < 4) {
        size /= 1024;
        unitIndex++;
    }
    return (unitIndex > 2) ? 
        size.toFixed(2) + ' ' + units[unitIndex] : 
        size.toFixed(0) + ' ' + units[unitIndex];
}
