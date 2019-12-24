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
class PureClarity extends \XLite\Base\Singleton
{
    /** @var string */
    const CONFIG_ENABLED                    = 'enabled';

    /** @var string */
    const CONFIG_ACCESS_KEY                 = 'access_key';

    /** @var string */
    const CONFIG_SECRET_KEY                 = 'secret_key';

    /** @var string */
    const CONFIG_REGION                     = 'region';

    /** @var string */
    const CONFIG_FEEDS_NIGHTLY              = 'feeds_nightly';

    /** @var string */
    const CONFIG_FEEDS_DELTAS               = 'feeds_deltas';

    /** @var string */
    const CONFIG_FEEDS_BRAND                = 'feeds_brands';

    /** @var string */
    const CONFIG_FEEDS_BRAND_PARENT         = 'feeds_brands_parent';

    /** @var string */
    const CONFIG_FEEDS_PRODUCT_OOS_EXCLUDE  = 'feeds_product_oos_exclude';

    /** @var string */
    const CONFIG_ZONE_DEBUG                 = 'zone_debug';

    /** @var string */
    const CONFIG_ZONE_HP01                  = 'zone_HP-01';

    /** @var string */
    const CONFIG_ZONE_HP02                  = 'zone_HP-02';

    /** @var string */
    const CONFIG_ZONE_HP03                  = 'zone_HP-03';

    /** @var string */
    const CONFIG_ZONE_HP04                  = 'zone_HP-04';

    /** @var string */
    const CONFIG_ZONE_PP01                  = 'zone_PP-01';

    /** @var string */
    const CONFIG_ZONE_PP02                  = 'zone_PP-02';

    /** @var string */
    const CONFIG_ZONE_BP01                  = 'zone_BP-01';

    /** @var string */
    const CONFIG_ZONE_BP02                  = 'zone_BP-02';

    /** @var string */
    const CONFIG_ZONE_OC01                  = 'zone_OC-01';

    /** @var string */
    const CONFIG_ZONE_OC02                  = 'zone_OC-02';

    /**
     * Runtime cache of options
     */
    protected $config;

    /**
     * Returns whether PureClarity is active
     *
     * @return boolean
     */
    public function isActive() : bool
    {
        $this->loadConfig();

        $active = true;
        if (empty($this->config[self::CONFIG_ENABLED]) ||
            empty($this->config[self::CONFIG_ACCESS_KEY]) ||
            empty($this->config[self::CONFIG_SECRET_KEY]) ||
            empty($this->config[self::CONFIG_REGION])
        ) {
            $active = false;
        }

        return $active;
    }

    /**
     * Returns whether zone debugging is enabled
     *
     * @return boolean
     */
    public function isZoneDebugEnabled() : bool
    {
        $this->loadConfig();
        return $this->config[self::CONFIG_ZONE_DEBUG] ? true : false;
    }

    /**
     * Returns whether the provided zone is active
     *
     * @param string $zone
     * @return bool
     */
    public function isZoneActive(string $zone) : bool
    {
        $this->loadConfig();
        return isset($this->config['zone_' . $zone]) ? (bool) $this->config['zone_' . $zone] : false;
    }

    /**
     * Returns the config setting for the given key
     *
     * @param string $key
     * @return string
     */
    public function getConfig(string $key) : string
    {
        $this->loadConfig();
        return isset($this->config[$key]) ? $this->config[$key] : '';
    }

    /**
     * Returns a bool representation of a config setting that is a yes/no flag
     *
     * @param string $key
     * @return boolean
     */
    public function getConfigFlag(string $key) : bool
    {
        $this->loadConfig();
        return isset($this->config[$key]) ? (bool)$this->config[$key] : false;
    }

    /**
     * Loads & caches config
     */
    protected function loadConfig() : void
    {
        if ($this->config === null) {
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
