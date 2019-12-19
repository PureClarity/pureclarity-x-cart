<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Core\Signup;

use PureClarity\Api\Signup\Status as SignupStatus;
use XLite\Base\Singleton;
use XLite\Module\PureClarity\Personalisation\Core\State;

/**
 * Class Process
 *
 * model for processing signup requests
 */
class Status extends Singleton
{
    /**
     * Calls PureClarity API to check status of signup request
     *
     * @return mixed[]
     */
    public function checkStatus()
    {
        $result = [
            'error' => '',
            'response' => [],
            'complete' => false,
        ];

        try {
            $state = State::getInstance();
            $requestData = $state->getStateValue('signup_request');

            if (!empty($requestData) && $requestData !== 'complete') {
                $signUpRequest = (array)json_decode($requestData);
                $signup = new SignupStatus();
                $result = $signup->request($signUpRequest);
            }
        } catch (\Exception $e) {
            $result['error'] = $e->getMessage();
        }

        return $result;
    }
}
