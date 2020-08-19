<?php

class WPUEP_Premium_Addon {

    public $addonPath;
    public $addonDir;
    public $addonUrl;
    public $addonName;

    public function __construct( $name )
    {	
        $this->addonPath = '/addons/' . $name;
        $this->addonDir = WPUltimateExercisePremium::get()->premiumDir . $this->addonPath;		
        $this->addonUrl = WPUltimateExercisePremium::get()->premiumUrl . $this->addonPath;		
        $this->addonName = $name;		
    }
}