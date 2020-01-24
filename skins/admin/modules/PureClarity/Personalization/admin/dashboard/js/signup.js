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
  class: 'pureclarity-signup-window'
};
PopupButtonSignup.prototype.pattern = '.popup-pureclarity-signup';
PopupButtonSignup.prototype.enableBackgroundSubmit = false;

core.autoload(PopupButtonSignup);

$.validationEngineLanguage.allRules.pureclarityPassword = {
  regex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}$/g,
  alertText: "Password not strong enough, must contain 1 lowercase letter, 1 uppercase letter, 1 number and be 8 characters or longer"
};
