/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 *
 * Covers events that are triggered by JS Events:
 *
 *   - Cart Update Event
 *   - Login Event
 *
 */

core.bind('pccartupdated', function (event, data) {
    _pc(
        'set_basket',
        data.items
    );
});

core.bind('pclogin', function (event, data) {
    _pc(
        'customer_details',
        data
    );
});