<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Core\Signup;

use Exception;
use PureClarity\Api\Feed\Feed;
use XLite\Base\Singleton;
use XLite\Core\Database;
use XLite\Core\Translation;
use XLite\Model\Repo\Config;
use XLite\Module\PureClarity\Personalisation\Core\State;

/**
 * Class Process
 *
 * model for processing signup requests
 */
class Process extends Singleton
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
    public function processManualConfigure(array $requestData) : array
    {
        $result = [
            'errors' => []
        ];

        $result['errors'] = $this->validateManualConfigure($requestData);

        if (empty($result['errors'])) {
            try {
                $this->saveConfig($requestData);
                $this->setConfiguredState();
                $this->triggerFeeds();
            } catch (Exception $e) {
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
    protected function validateManualConfigure(array $requestData) : array
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
    public function processAutoSignup(array $requestData) : array
    {
        $result = [
            'errors' => []
        ];

        try {
            $this->saveConfig($requestData);
            $this->setConfiguredState();
            $this->completeSignup();
            $this->triggerFeeds();
        } catch (Exception $e) {
            $result['errors'][] = Translation::lbl('Error processing request: X', ['error' => $e->getMessage()]);
        }

        return $result;
    }

    /**
     * Saves the PureClarity credentials to the Magento config
     *
     * @param mixed[] $requestData
     */
    protected function saveConfig(array $requestData) : void
    {
        $this->updateConfig('pc_enabled', '1');
        $this->updateConfig('pc_access_key', $requestData['access_key']);
        $this->updateConfig('pc_secret_key', $requestData['secret_key']);
        $this->updateConfig('pc_region', $requestData['region']);
        $this->updateConfig('pc_feeds_nightly', '1');
        $this->updateConfig('pc_feeds_deltas', '1');
    }

    protected function updateConfig(string $key, string $value) : void
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
     */
    protected function setConfiguredState() : void
    {
        $state = State::getInstance();
        $state->setStateValue('is_configured', '1');
    }

    /**
     * Updates the signup request to be complete
     */
    protected function completeSignup() : void
    {
        $state = State::getInstance();
        $state->setStateValue('signup_request', 'complete');
    }

    /**
     * Triggers a run of feeds needed after signup
     */
    protected function triggerFeeds() : void
    {
        $feeds = [
            Feed::FEED_TYPE_PRODUCT,
            Feed::FEED_TYPE_CATEGORY,
            Feed::FEED_TYPE_USER,
            Feed::FEED_TYPE_ORDER
        ];

        $state = State::getInstance();
        $state->setStateValue('requested_feeds', json_encode($feeds));
    }
}
