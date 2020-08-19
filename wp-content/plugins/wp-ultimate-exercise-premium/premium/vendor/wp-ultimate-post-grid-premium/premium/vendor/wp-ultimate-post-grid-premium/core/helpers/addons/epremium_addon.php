<?php

class WPUPG_Epremium_Addon {

    public $addonPath;
    public $addonDir;
    public $addonUrl;
    public $addonName;

    public function __construct( $name )
    {
        $this->addonPath = '/addons/' . $name;
        $this->addonDir = WPUltimateEPostEgridPremium::get()->premiumDir . $this->addonPath;
        $this->addonUrl = WPUltimateEPostEgridPremium::get()->premiumUrl . $this->addonPath;
        $this->addonName = $name;
    }
}