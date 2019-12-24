/* vim: set ts=2 sw=2 sts=2 et: */

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
  class: 'pureclarity-signup-window'
};
PopupButtonSignup.prototype.pattern = '.popup-pureclarity-signup';
PopupButtonSignup.prototype.enableBackgroundSubmit = false;

core.autoload(PopupButtonSignup);

$.validationEngineLanguage.allRules.pureclarityPassword = {
  regex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{8,})/g,
  alertText: "Password not strong enough, must contain 1 lowercase letter, 1 uppercase letter, 1 number and be 8 characters or longer"
};
