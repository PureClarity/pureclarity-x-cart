/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

function checkStatus() {
    core.get(
        URLHandler.buildURL({
            target: 'pureclarity_signup_progress',
        })
    ).done(function (result) {
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