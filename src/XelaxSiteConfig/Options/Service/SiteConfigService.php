<?php


namespace XelaxSiteConfig\Options\Service;

use Eye4web\SiteConfig\Service\SiteConfigService as EyeService;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class SiteConfigService implements ServiceLocatorAwareInterface{
	use ServiceLocatorAwareTrait;
	
	const CONFIG_PREFIX = 'xelax_site_config.email';
	
	/**
	 * Get config for specified prefix
	 * @param string $prefix
	 * @return array
	 */
	public function getConfig($prefix){
		/* @var $siteConfigService EyeService */
		$siteConfigService = $this->getServiceLocator()->get(EyeService::class);
		$config = $siteConfigService->getAll();
		
		// get config selected by prefix
		$prefixParts = explode('.', $prefix);
		$conf = $config;
		foreach ($prefixParts as $prefix) {
			if(isset($conf[$prefix])){
				$conf = $conf[$prefix];
			} else {
				$conf = array();
			}
		}
		return $conf;
	}
	
	protected function preprocessConfig($config){
	}
	
}

