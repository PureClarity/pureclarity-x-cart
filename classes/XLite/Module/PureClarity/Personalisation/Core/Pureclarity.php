<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Core;

use XLite\Core\Config;

/**
 * Pureclarity product sources class
 */
class Pureclarity extends \XLite\Base\Singleton
{
    const CONFIG_ENABLED                    = 'enabled';
    const CONFIG_ACCESS_KEY                 = 'access_key';
    const CONFIG_SECRET_KEY                 = 'secret_key';
    const CONFIG_REGION                     = 'region';
    const CONFIG_FEEDS_NIGHTLY              = 'feeds_nightly';
    const CONFIG_FEEDS_DELTAS               = 'feeds_deltas';
    const CONFIG_FEEDS_BRAND                = 'feeds_brands';
    const CONFIG_FEEDS_BRAND_PARENT         = 'feeds_brands_parent';
    const CONFIG_FEEDS_PRODUCT_OOS_EXCLUDE  = 'feeds_product_oos_exclude';
    const CONFIG_ZONE_DEBUG                 = 'zone_debug';
    const CONFIG_ZONE_HP01                  = 'zone_HP-01';
    const CONFIG_ZONE_HP02                  = 'zone_HP-02';
    const CONFIG_ZONE_HP03                  = 'zone_HP-03';
    const CONFIG_ZONE_HP04                  = 'zone_HP-04';
    const CONFIG_ZONE_PP01                  = 'zone_PP-01';
    const CONFIG_ZONE_PP02                  = 'zone_PP-02';
    const CONFIG_ZONE_BP01                  = 'zone_BP-01';
    const CONFIG_ZONE_BP02                  = 'zone_BP-02';
    const CONFIG_ZONE_OC01                  = 'zone_OC-01';
    const CONFIG_ZONE_OC02                  = 'zone_OC-02';

    /**
     * Runtime cache of options
     */
    protected $config;

    /**
     * @return boolean
     */
    public function isActive()
    {
        $this->loadConfig();

        $active = true;
        if (empty($this->config[self::CONFIG_ENABLED]) ||
            empty($this->config['access_key']) ||
            empty($this->config['secret_key']) ||
            empty($this->config['region'])
        ) {
            $active = false;
        }

        return $active;
    }

    /**
     * @return boolean
     */
    public function isZoneDebugEnabled()
    {
        $this->loadConfig();
        return $this->config[self::CONFIG_ZONE_DEBUG] ? true : false;
    }

    /**
     * @param string $zone
     * @return bool
     */
    public function isZoneActive($zone)
    {
        $this->loadConfig();
        return isset($this->config['zone_' . $zone]) ? (bool) $this->config['zone_' . $zone] : false;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getConfig($key)
    {
        $this->loadConfig();
        return isset($this->config[$key]) ? $this->config[$key] : false;
    }

    /**
     * Loads & caches config
     *
     * @param bool $force
     */
    protected function loadConfig($force = false)
    {
        if ($this->config === null || $force === true) {
            $this->config = [];
            $config = Config::getInstance()->PureClarity->Personalisation;
            $this->config[self::CONFIG_ENABLED]                    = $config->pc_enabled;
            $this->config[self::CONFIG_ACCESS_KEY]                 = $config->pc_access_key;
            $this->config[self::CONFIG_SECRET_KEY]                 = $config->pc_secret_key;
            $this->config[self::CONFIG_REGION]                     = $config->pc_region;
            $this->config[self::CONFIG_FEEDS_NIGHTLY]              = $config->pc_feeds_nightly;
            $this->config[self::CONFIG_FEEDS_DELTAS]               = $config->pc_feeds_deltas;
            $this->config[self::CONFIG_FEEDS_BRAND]                = $config->pc_feeds_brands;
            $this->config[self::CONFIG_FEEDS_BRAND_PARENT]         = $config->pc_feeds_brands_parent;
            $this->config[self::CONFIG_FEEDS_PRODUCT_OOS_EXCLUDE]  = $config->pc_feeds_product_oos_exclude;
            $this->config[self::CONFIG_ZONE_DEBUG]                 = $config->pc_zone_debug;
            $this->config[self::CONFIG_ZONE_HP01]                  = $config->pc_zone_hp01;
            $this->config[self::CONFIG_ZONE_HP02]                  = $config->pc_zone_hp02;
            $this->config[self::CONFIG_ZONE_HP03]                  = $config->pc_zone_hp03;
            $this->config[self::CONFIG_ZONE_HP04]                  = $config->pc_zone_hp04;
            $this->config[self::CONFIG_ZONE_PP01]                  = $config->pc_zone_pp01;
            $this->config[self::CONFIG_ZONE_PP02]                  = $config->pc_zone_pp02;
            $this->config[self::CONFIG_ZONE_BP01]                  = $config->pc_zone_bp01;
            $this->config[self::CONFIG_ZONE_BP02]                  = $config->pc_zone_bp02;
            $this->config[self::CONFIG_ZONE_OC01]                  = $config->pc_zone_oc01;
            $this->config[self::CONFIG_ZONE_OC02]                  = $config->pc_zone_oc02;
        }
    }
}
