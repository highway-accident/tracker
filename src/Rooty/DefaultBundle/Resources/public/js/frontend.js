$(document).ready(function() {
    // Advanced search type fields loading
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
    
    // Filter ordering
    $('.search_order').click(function() {
        if ($('.torrent_search:visible .order_by').val() == $(this).attr('id')) {
            // if already sorting by current row inverse direction
            direction = ($('.torrent_search:visible .order_direction').val() == 'ASC') ? 'DESC' : 'ASC';
            $('.torrent_search:visible .order_direction').val(direction)
        } else {
            $('.torrent_search:visible .order_by').val($(this).attr('id'));
            direction = 'ASC';
            $('.torrent_search:visible .order_direction').val(direction)
        }
        $('.torrent_search:visible').submit();
        return false;
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
    
    $('a.colorbox.inline').colorbox({inline: true});
    $('.group1').colorbox({rel:'group1', maxWidth: '95%', maxHeight: '95%', scalePhotos: true});
    
    $('#torrent__screenshots__carousel').jcarousel();
    
    minval = parseInt($('#search_advanced_size_min').val());
    maxval = parseInt($('#search_advanced_size_max').val());
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
            $('#search_advanced_size_min').val(from.toFixed(0));
            $('#search_advanced_size_max').val(to.toFixed(0));
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
    
    /* Torrent rating AJAX */
    $('.torrent__rate').click(function() {
        $.get($(this).attr('href'), function(response) {
            var obj = $.parseJSON(response);
            obj.likes = parseInt(obj.likes);
            obj.dislikes = parseInt(obj.dislikes);
            
            if (obj.type == 'like') {
                if (obj.likes == 1) {
                    likeContent = 'только вы';
                } else {
                    likeContent = obj.likes-1 + ' ' + declOfNum(obj.likes-1, ['человек', 'человека', 'человек']) + ' и вы';
                }
                dislikeContent = obj.dislikes + ' ' + declOfNum(obj.dislikes, ['человек', 'человека', 'человек']);
            }
            
            if (obj.type == 'dislike') {
                if (obj.dislikes == 1) {
                    dislikeContent = 'только вы';
                } else {
                    dislikeContent = obj.dislikes-1 + ' ' + declOfNum(obj.dislikes-1, ['человек', 'человека', 'человек']) + ' и вы';
                }
                likeContent = obj.likes + ' ' + declOfNum(obj.likes, ['человек', 'человека', 'человек']);
            }
            
            $('.torrent__rating__bar .like').animate({width: obj.likes/(obj.likes+obj.dislikes)*100 + '%'}, 400);
                        
            $('#torrent__rating__like .votes').html(likeContent);
            $('#torrent__rating__dislike .votes').html(dislikeContent);
        });
        return false;
    });
    
    $('a.confirm').click(function () {
        if (confirm('Вы уверены?')) {
            return true;
        } else {
            return false;
        }
    });
    
    // Close notification on link click
    $('body').on('click', 'a.showMessage', function() {
        $(this).closest('.noty_message').find('.noty_close').click();
    });
    
    // AJAX chat message post
    $('.chat-message-create').ajaxForm({
        beforeSubmit: function(arr, form) {
            $(form).find('button').attr('disabled', 'true').text('Отправка...');
        },
        success: function(responseText, statusText, xhr, form) {
            $(form).find('input[type=text]').val('');
            $(form).find('button').removeAttr('disabled').text('Отправить');
            clearTimeout(chatTimeout);
            refreshChat();
        }
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

function declOfNum(number, titles)
{  
    cases = [2, 0, 1, 1, 1, 2];  
    return titles[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ];  
}

function IMNotify(url) {
    console.log('Checking for new messages..');
    $.get(url, function(response) {
        var obj = $.parseJSON(response);
        if (obj.status == 'ok') {
            $.each(obj.messages, function(index, message) {
                var text = '<a class="showMessage" href="'+message.path+'" target="_blank">Вы получили новое сообщение от пользователя '+message.username+'.</a>';
                noty({
                    "text":text,
                    "layout":"topLeft",
                    "type":"alert",
                    "textAlign":"left",
                    "easing":"swing",
                    "animateOpen":{"opacity":"toggle"},
                    "animateClose":{"opacity":"toggle"},
                    "speed":"500",
                    "timeout":false,
                    "closable":true,
                    "closeOnSelfClick":false
                });
            });
        }
    });
    setTimeout(IMNotify, 15000, url);
}

var chatTimeout;

function refreshChat(url) {
    console.log('refreshing chat');
    $.get(url, function(response) {
        var obj = $.parseJSON(response, true);
        var result = new Array();
        if (obj.status == 'ok') {
            $.each(obj.messages, function(index, message) {
                result.push('<div class="chat__messages__message">['+message.dateAdded+'] '+message.path+': '+message.text+'</div>');
            });
            $('.chat__messages').html(result.join(''));
        }
    });
    chatTimeout = setTimeout(refreshChat, 5000, url);
}
