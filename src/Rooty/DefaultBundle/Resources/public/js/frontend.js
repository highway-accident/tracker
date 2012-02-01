$(function() {
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
        var screenshotCount = $('#rooty_torrentbundle_torrentformtype_screenshots').children('div').size();
        newWidget = newWidget.replace(/\$\$name\$\$/g, screenshotCount);
        $(newWidget).hide().insertBefore(addScreenshot).fadeIn();
        return false;
    });
    $('#rooty_torrentbundle_torrentformtype_screenshots').append(addScreenshot);
    $('a.remove_screenshot').livequery('click', function () {
        $(this).parent().fadeOut(function() {$(this).remove();});
        return false;
    });
    
    $('.group1').colorbox({rel:'group1', maxWidth: '95%', maxHeight: '95%', scalePhotos: true});
    
    $('#torrent__screenshots__carousel').jcarousel();
});