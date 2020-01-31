<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Core\Task;

use XLite\Core\Task\Base\Periodic;
use XLite\Module\PureClarity\Personalization\Core\Signup\Process;
use XLite\Module\PureClarity\Personalization\Core\Signup\Status;
use XLite\Module\PureClarity\Personalization\Core\State;

/**
 * Scheduled task that checks for signup progress & processes it if it's finished
 */
class Signup extends Periodic
{
    /**
     * Returns the title of this task
     *
     * @return string
     */
    public function getTitle() : string
    {
        return static::t('PureClarity signup check');
    }

    /**
     * Check for signup progress & process if complete
     */
    protected function runStep() : void
    {
        $state = State::getInstance();
        $signup = $state->getStateValue('signup_request');
        if ($signup !== 'complete') {
            $signupStatus = Status::getInstance();
            $response = $signupStatus->checkStatus();

            $error = '';
            if ($response['errors']) {
                $error = implode(',', $response['errors']);
            } elseif ($response['complete']) {
                $processor = Process::getInstance();
                $result = $processor->processAutoSignup($response['response']);

                if ($result['errors']) {
                    $error = implode(',', $response['errors']);
                }
            }

            if ($error) {
                $state = State::getInstance();
                $state->setStateValue('signup_error', $error);
            }
        }
    }

    /**
     * Get recurrence period (seconds)
     *
     * @return int
     */
    protected function getPeriod() : int
    {
        return Periodic::INT_5_MIN;
    }
}
