/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
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
      ).done(function (response) {

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
        }
      })
    }

    if ($('#pc-feeds-in-progress').val() === 'true') {
      setTimeout(checkFeedProgress, 2000);
    }
  }
);
