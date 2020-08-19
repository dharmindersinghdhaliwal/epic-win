<?php

class WPUPG_Eaddon {

    public $addonPath;
    public $addonDir;
    public $addonUrl;
    public $addonName;

    public function __construct( $name )
    {
        $this->addonPath = '/addons/' . $name;
        $this->addonDir = WPUltimateEPostGrid::get()->coreDir . $this->addonPath;
        $this->addonUrl = WPUltimateEPostGrid::get()->coreUrl . $this->addonPath;
        $this->addonName = $name;
    }
}