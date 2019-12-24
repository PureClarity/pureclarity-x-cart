<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Controller\Admin;

use XLite\Controller\Admin\AAdmin;
use XLite\Module\PureClarity\Personalisation\Core\Signup\Process;
use XLite\Module\PureClarity\Personalisation\Core\Signup\Status;

/**
 * Class PureclaritySignupProgress
 *
 * PureClarity Signup progress check ajax call Controller
 */
class PureclaritySignupProgress extends AAdmin
{
    /**
     * Default AJAX action
     *
     * Checks the current status of the PureClarity signup request
     * and returns a JSON string to be used by JS to update the display
     */
    public function doNoAction()
    {
        $error = '';
        $complete = false;
        $signupStatus = Status::getInstance();
        $response = $signupStatus->checkStatus();

        if ($response['error']) {
            $error = $response['error'];
        } elseif ($response['complete']) {
            $processor = Process::getInstance();
            $result = $processor->processAutoSignup($response['response']);

            if ($result['errors']) {
                $error = implode(',', $response['errors']);
            } else {
                $complete = true;
            }
        }

        $return = json_encode(['complete' => $complete, 'error' => $error]);

        header('Content-Type: application/json; charset=UTF-8');
        header('Content-Length: ' . strlen($return));
        header('ETag: ' . md5($return));

        print ($return);
    }
}
