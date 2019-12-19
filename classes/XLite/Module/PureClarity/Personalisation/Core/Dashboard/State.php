<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Core\Dashboard;

use XLite\Base\Singleton;
use XLite\Module\PureClarity\Personalisation\Core\State as CoreState;

/**
 * Class State
 *
 * Dashboard State ViewModel
 */
class State extends Singleton
{
    const STATE_NOT_CONFIGURED = 'not_configured';
    const STATE_WAITING = 'waiting';
    const STATE_CONFIGURED = 'configured';

    /** @var bool $isNotConfigured */
    private $isNotConfigured;

    /** @var bool $signupStarted */
    private $signupStarted;

    /** @var CoreState $state */
    private $state;

    /**
     * Returns current dashboard state
     *
     * @return string
     */
    public function getState()
    {
        if ($this->isNotConfigured()) {
            return self::STATE_NOT_CONFIGURED;
        } elseif ($this->isWaiting()) {
            return self::STATE_WAITING;
        } else {
            return self::STATE_CONFIGURED;
        }
    }

    /**
     * Returns whether the dashboard is not configured
     *
     * @return boolean
     */
    public function isNotConfigured()
    {
        return ($this->getIsNotConfigured() === true && $this->getSignupStarted() === false);
    }

    /**
     * Returns whether the dashboard is waiting for signup to complete
     *
     * @return boolean
     */
    public function isWaiting()
    {
        return ($this->getIsNotConfigured() === true && $this->getSignupStarted() === true);
    }

    /**
     * @return bool
     */
    private function getIsNotConfigured()
    {
        if ($this->isNotConfigured === null) {
            $state = $this->getStateValue('is_configured');
            $this->isNotConfigured = empty($state);
        }

        return $this->isNotConfigured;
    }

    /**
     * @return bool
     */
    private function getSignupStarted()
    {
        if ($this->signupStarted === null) {
            $signupState = $this->getStateValue('signup_request');
            $this->signupStarted = empty($signupState) === false;
        }

        return $this->signupStarted;
    }

    /**
     * Gets the value for the given state name key
     *
     * @param string $nameKey
     * @return string
     */
    private function getStateValue(string $nameKey) : string
    {
        if ($this->state === null) {
            $this->state = CoreState::getInstance();
        }

        return $this->state->getStateValue($nameKey);
    }
}
