<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Core\Signup;

use PureClarity\Api\Signup\Submit;
use XLite\Base\Singleton;
use XLite\Module\PureClarity\Personalisation\Core\State;

/**
 * Class Request
 *
 * model for submitting signup requests to PureClarity
 */
class Request extends Singleton
{
    /**
     * Sends the signup request to PureClarity
     *
     * @param mixed[] $params
     *
     * @return mixed[]
     */
    public function sendRequest($params)
    {
        $signup = new Submit();
        $result = $signup->request($params);

        if ($result['error'] === '') {
            $this->saveRequest($result['request_id'], $params);
        }

        return $result;
    }

    /**
     * Saves the request details to state table
     *
     * @param string $requestId
     * @param mixed[] $params
     */
    protected function saveRequest($requestId, $params)
    {
        $signupData = [
            'id' => $requestId,
            'region' =>  $params['region']
        ];

        $state = State::getInstance();
        $state->setStateValue('signup_request', json_encode($signupData));
    }
}
