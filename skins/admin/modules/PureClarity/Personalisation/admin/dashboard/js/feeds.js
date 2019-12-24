/**
 * Add payment method JS controller
 *
 * Copyright (c) 2011-present Qualiteam software Ltd. All rights reserved.
 * See https://www.x-cart.com/license-agreement.html for license details.
 */

function PopupButtonSignup()
{
  PopupButtonSignup.superclass.constructor.apply(this, arguments);
}

extend(PopupButtonSignup, PopupButton);
PopupButton.prototype.options = {
  width: 'auto',
  class: 'pureclarity-feeds-window'
};
PopupButtonSignup.prototype.pattern = '.popup-pureclarity-feeds';
PopupButtonSignup.prototype.enableBackgroundSubmit = false;

core.autoload(PopupButtonSignup);

jQuery().ready(
  function() {
    function checkFeedProgress() {
      core.get(
          URLHandler.buildURL({
            target: 'pureclarity_feed_progress',
          })
      ).done(function (data) {
        var response = JSON.parse(data);

        $('#pc-productFeedStatusLabel').html(response.product.label);
        $('#pc-categoryFeedStatusLabel').html(response.category.label);
        $('#pc-brandFeedStatusLabel').html(response.brand.label);
        $('#pc-userFeedStatusLabel').html(response.user.label);
        $('#pc-ordersFeedStatusLabel').html(response.orders.label);
        $('#pc-productFeedStatusClass').attr('class', 'pc-feed-status-icon ' + response.product.class);
        $('#pc-categoryFeedStatusClass').attr('class', 'pc-feed-status-icon ' + response.category.class);
        $('#pc-brandFeedStatusClass').attr('class', 'pc-feed-status-icon ' + response.brand.class);
        $('#pc-userFeedStatusClass').attr('class', 'pc-feed-status-icon ' + response.user.class);
        $('#pc-ordersFeedStatusClass').attr('class', 'pc-feed-status-icon ' + response.orders.class);

        if (response.in_progress === true) {
          setTimeout(checkFeedProgress, 2000);
        } else {
          $('#pc-feeds-button-disabled').hide();
          $('#pc-feeds-button-active').show();
        }
      })
    }

    if ($('#pc-feeds-in-progress').val() === 'true') {
      setTimeout(checkFeedProgress, 2000);
    }
  }
)
