

core.bind('block.product.details.postprocess', function (event, data) {
    _pc(
        'product_view',
        {
            id: core.getCommentedData(jQuery('body'), 'product_id'),
            category_id: core.getCommentedData(jQuery('body'), 'category_id')
        }
    );
});

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

core.bind('pclogout', function (event, data) {
    _pc('customer_logout');
});
