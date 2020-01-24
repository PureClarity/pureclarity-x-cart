/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

jQuery().ready(
  function() {
    $('#pc-step2 button').click(function() {
        return confirm('Are you sure you want to go live?');
    });
  }
);
