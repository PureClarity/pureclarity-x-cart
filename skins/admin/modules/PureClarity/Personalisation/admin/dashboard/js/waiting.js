
/* vim: set ts=2 sw=2 sts=2 et: */

function checkStatus() {
    core.get(
        URLHandler.buildURL({
            target: 'pureclarity_signup_progress',
        })
    ).done(function (data) {
        var result = JSON.parse(data);
        if (result.complete) {
            location.reload();
        } else if (result.error !== '') {
            core.trigger('message', { 'message': result.error, 'type': MESSAGE_ERROR });
        } else {
            setTimeout(checkStatus, 5000);
        }
    })
}

setTimeout(checkStatus, 5000);