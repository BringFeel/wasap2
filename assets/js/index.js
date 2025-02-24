const params = new URLSearchParams(window.location.search);

if (params.get('fail') === 'true') {
    const messageElement = document.getElementById('message');
    if (messageElement) {
        messageElement.style.opacity = 1;
        messageElement.style.color = "#df0000";

        const errorMessage = params.get('error');
        document.getElementById('message').innerText = errorMessage ? errorMessage : "Pasó algo, pero no tengo idea flaco.";
    }
} else {
    const messageElement = document.getElementById('message');
    if (messageElement) {
        messageElement.style.opacity = 0;
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