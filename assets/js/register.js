const params = new URLSearchParams(window.location.search);

if (params.get('fail') === 'true') {
    const errorElement = document.getElementById('error');
    const errorMessage = params.get('error');
    if (errorElement) {
        errorElement.style.opacity = 1;
        document.getElementById('error').innerText = errorMessage;
    }
} else {
    const errorElement = document.getElementById('error');
    if (errorElement) {
        errorElement.style.opacity = 0;
    }
}

$(function() {
    $('#username').on('keypress', function(e) {
        if (e.which == 32) return false;
    });

    $('#password').on('keypress', function(e) {
        if (e.which == 32) return false;
    });
});