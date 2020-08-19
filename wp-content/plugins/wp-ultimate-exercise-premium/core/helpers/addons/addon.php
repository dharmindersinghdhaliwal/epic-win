<?php

class WPUEP_Addon {

    public $addonPath;
    public $addonDir;
    public $addonUrl;
    public $addonName;

    public function __construct( $name )
    {		
        $this->addonPath = '/addons/' . $name;
        $this->addonDir = WPUltimateExercise::get()->coreDir . $this->addonPath;
        $this->addonUrl = WPUltimateExercise::get()->coreUrl . $this->addonPath;
        $this->addonName = $name;
    }
}