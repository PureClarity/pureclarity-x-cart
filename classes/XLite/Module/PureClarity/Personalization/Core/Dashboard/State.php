<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Core\Dashboard;

use XLite\Base\Singleton;
use XLite\Module\PureClarity\Personalization\Core\State as CoreState;

/**
 * Class State
 *
 * Calculates the Dashboard State to determine what content to display
 */
class State extends Singleton
{
    /** @var string */
    const STATE_NOT_CONFIGURED = 'not_configured';

    /** @var string */
    const STATE_WAITING = 'waiting';

    /** @var string */
    const STATE_CONFIGURED = 'configured';

    /** @var bool $isNotConfigured */
    protected $isNotConfigured;

    /** @var bool $signupStarted */
    protected $signupStarted;

    /** @var CoreState $state */
    protected $state;

    /**
     * Returns current dashboard state
     *
     * @return string
     */
    public function getState() : string
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
    public function isNotConfigured() : bool
    {
        return ($this->getIsNotConfigured() === true && $this->getSignupStarted() === false);
    }

    /**
     * Returns whether the dashboard is waiting for signup to complete
     *
     * @return boolean
     */
    public function isWaiting() : bool
    {
        return ($this->getIsNotConfigured() === true && $this->getSignupStarted() === true);
    }

    /**
     * Returns whether the dashboard is in the "not configured" state
     *
     * @return bool
     */
    protected function getIsNotConfigured() : bool
    {
        if ($this->isNotConfigured === null) {
            $state = $this->getStateValue('is_configured');
            $this->isNotConfigured = empty($state);
        }

        return $this->isNotConfigured;
    }

    /**
     * Returns whether a signup request has started
     *
     * @return bool
     */
    protected function getSignupStarted() : bool
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
    protected function getStateValue(string $nameKey) : string
    {
        if ($this->state === null) {
            $this->state = CoreState::getInstance();
        }

        return $this->state->getStateValue($nameKey);
    }
}
