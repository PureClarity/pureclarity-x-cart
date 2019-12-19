<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Core\Signup;

use XLite\Core\Database;
use XLite\Core\Translation;
use XLite\Model\Repo\Config;
use XLite\Module\PureClarity\Personalisation\Core\State;

/**
 * Class Process
 *
 * model for processing signup requests
 */
class Process extends \XLite\Base\Singleton
{
    /** @var Config $coreConfig */
    protected $configRepo;

    /**
     * Processes a manual configuration from the dashboard page
     *
     * @param mixed[] $requestData
     *
     * @return mixed[]
     */
    public function processManualConfigure($requestData)
    {
        $result = [
            'errors' => []
        ];

        $result['errors'] = $this->validateManualConfigure($requestData);

        if (empty($result['errors'])) {
            try {
                $this->saveConfig($requestData);
                $this->setConfiguredState();
                $this->triggerFeeds($requestData);
            } catch (\Exception $e) {
                $result['errors'][] = Translation::lbl('Error processing request: X', ['error' => $e->getMessage()]);
            }
        }

        return $result;
    }

    /**
     * Validates the params in the manual configure request
     *
     * @param mixed[] $requestData
     * @return array
     */
    protected function validateManualConfigure($requestData)
    {
        $errors = [];

        if (!isset($requestData['access_key']) || empty($requestData['access_key'])) {
            $errors[] = Translation::lbl('Missing Access Key');
        }

        if (!isset($requestData['secret_key']) || empty($requestData['secret_key'])) {
            $errors[] = Translation::lbl('Missing Secret Key');
        }

        if (!isset($requestData['region']) || empty($requestData['region'])) {
            $errors[] = Translation::lbl('Missing Region');
        }

        return $errors;
    }

    /**
     * Processes the signup request
     *
     * @param mixed[] $requestData
     *
     * @return mixed[]
     */
    public function processAutoSignup($requestData)
    {
        $result = [
            'errors' => []
        ];

        try {
            $this->saveConfig($requestData);
            $this->setConfiguredState();
            $this->completeSignup();
            $this->triggerFeeds($requestData);
        } catch (\Exception $e) {
            $result['errors'][] = Translation::lbl('Error processing request: X', ['error' => $e->getMessage()]);
        }

        return $result;
    }

    /**
     * Saves the PureClarity credentials to the Magento config
     *
     * @param mixed[] $requestData
     */
    protected function saveConfig($requestData)
    {
        $this->updateConfig('pc_enabled', '1');
        $this->updateConfig('pc_access_key', $requestData['access_key']);
        $this->updateConfig('pc_secret_key', $requestData['secret_key']);
        $this->updateConfig('pc_region', $requestData['region']);
        $this->updateConfig('pc_feeds_nightly', '1');
        $this->updateConfig('pc_feeds_deltas', '1');
    }

    protected function updateConfig(string $key, string $value)
    {
        if ($this->configRepo === null) {
            $this->configRepo = Database::getRepo('XLite\Model\Config');
        }

        /** @var \XLite\Model\Config $config */
        $config = $this->configRepo->findOneBy(array('name' => $key, 'category' => 'PureClarity\Personalisation'));

        $this->configRepo->update(
            $config,
            array('value' => $value)
        );
    }

    /**
     * Saves the is_configured flag
     *
     * @return void
     */
    protected function setConfiguredState()
    {
        $state = State::getInstance();
        $state->setStateValue('is_configured', '1');
    }

    /**
     * Updates the signup request to be complete
     *
     * @return void
     */
    protected function completeSignup()
    {
        $state = State::getInstance();
        $state->setStateValue('signup_request', 'complete');
    }

    /**
     * Triggers a run of all feeds
     *
     * @param mixed[] $requestData
     */
    protected function triggerFeeds($requestData)
    {
        $feeds = [
            'product',
            'category',
            'user',
            'orders'
        ];

        $state = State::getInstance();
        $state->setStateValue('requested_feeds', json_encode($feeds));
    }
}
