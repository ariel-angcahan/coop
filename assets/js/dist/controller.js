$(document).ready(function() {
    isLoading = !1;
    var a = $('.timer');
    a.length && $('time.timer').timeago(), 

    $('.navbar-right .dropdown-menu .body .menu').length && core.initSlimScroll().slimScroll().bind('slimscroll', function(b, c) {
        'bottom' == c && !1 == isLoading && core.notifLoader(!1, !1)
    }), 

    $('body').on('click', '.notif-indi', function(b) {
        b.preventDefault(), 
        localStorage.setItem('_EmpId', $(this).attr('data-EmpId')), 
        localStorage.setItem('_data-id', $(this).attr('data-id')), 
        localStorage.setItem('_type', $(this).attr('data-type')), 
        location.href = $(this).attr('data-url')
    })
});