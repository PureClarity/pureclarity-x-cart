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
 * PureClarity Dashboard Page
 */
class PureclaritySignupProgress extends AAdmin
{
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
