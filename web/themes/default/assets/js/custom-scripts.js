$(document).ready(function(e) {

    $("html").niceScroll({styler:"fb",cursorcolor:"#4ECDC4", cursorwidth: '10', cursorborderradius: '10px', background: '#404040', spacebarenabled:false,  cursorborder: '', zindex: '1000'});
    $('.validation').formValidation({ framework: 'bootstrap', excluded: ':disabled' });
    $('.tooltips').tooltip();
    $('.po').popover();
    $('.del-po').popover({trigger: 'focus', html: true, container: 'body', placement: 'left'});
    $('.datepicker').datepicker({autoclose:true, format: 'yyyy-mm-dd', orientation: 'left top'});
    $('.redactor').redactor({
        formatting: ['p', 'blockquote', 'h3', 'h4', 'pre'],
        minHeight: 100,
        maxHeight: 400,
        linebreaks: true,
        tabAsSpaces: 4,
        dragImageUpload: false,
        dragFileUpload: false,
        plugins: ['newbuttons']
    });

    $(":file").filestyle();
    $('select').select2({ minimumResultsForSearch: 6 });

    if(message != '') {
        var success_noti = $.gritter.add({
            title: 'Success!',
            text: message,
            sticky: true,
            class_name: 'bg-theme03 border-round'
        });
        setTimeout(function(){
            $.gritter.remove(success_noti, { fade: true, speed: 'slow' });
        }, 30000);
    }

    if(error != '') {
        var error_noti = $.gritter.add({
            title: 'Error!',
            text: error,
            sticky: true,
            class_name: 'bg-theme04 border-round'
        });
        setTimeout(function(){
            $.gritter.remove(error_noti, { fade: true, speed: 'slow' });
        }, 30000);
    }

    if(warning != '') {
        var warning_noti = $.gritter.add({
            title: 'Warning!',
            text: warning,
            sticky: true,
            class_name: 'bg-theme02 border-round'
        });
        setTimeout(function(){
            $.gritter.remove(warning_noti, { fade: true, speed: 'slow' });
        }, 30000);
    }

});

function get(name) {
    if (typeof (Storage) !== "undefined") {
        return localStorage.getItem(name);
    } else {
        alert('Please use a modern browser as this site needs localstroage!');
    }
}

function store(name, val) {
    if (typeof (Storage) !== "undefined") {
        localStorage.setItem(name, val);
    } else {
        alert('Please use a modern browser as this site needs localstroage!');
    }
}

function remove(name) {
    if (typeof (Storage) !== "undefined") {
        localStorage.removeItem(name);
    } else {
        alert('Please use a modern browser as this site needs localstroage!');
    }
}

function hrsd(sdate) {
    if (sdate !== null) {
        return date(dateformat, strtotime(sdate));
    }
    return sdate;
}

function hrld(ldate) {
    if (ldate !== null) {
        return date(dateformat+' '+timeformat, strtotime(ldate));
    }
    return ldate;
}

function is_numeric(mixed_var) {
    var whitespace =
    " \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
    return (typeof mixed_var === 'number' || (typeof mixed_var === 'string' && whitespace.indexOf(mixed_var.slice(-1)) === -
        1)) && mixed_var !== '' && !isNaN(mixed_var);
}

$(document).ajaxStart(function(){
    $('#ajaxCall').show();
}).ajaxStop(function(){
    $('#ajaxCall').hide();
});

$(document).ready(function() {
    $('body').on('click', '.check_out_link td:not(:nth-child(5), :last-child)', function() {
        $.get( base_url + 'check_out/view/' + $(this).parent('.check_out_link').attr('id'), function( data ) {
            $('#myModal').html(data);
            $('#myModal').modal('show');
        });
    });
    $('body').on('click', '.check_in_link td:not(:nth-child(5), :last-child)', function() {
        $.get( base_url + 'check_in/view/' + $(this).parent('.check_in_link').attr('id'), function( data ) {
            $('#myModal').html(data);
            $('#myModal').modal('show');
        });
    });
});
