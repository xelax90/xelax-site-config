<?php

/*
 * Copyright (C) 2015 schurix
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

namespace XelaxSiteConfig\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use XelaxSiteConfig\Form\ConfigEmailForm;
use Eye4web\SiteConfig\Service\SiteConfigAwareInterface;
use Eye4web\SiteConfig\Service\SiteConfigAwareTrait;
use Doctrine\ORM\EntityManager;
use XelaxSiteConfig\Entity\SiteConfig;
use XelaxSiteConfig\Options\SiteEmailOptions;
use Zend\I18n\Translator\Translator;
use XelaxSiteConfig\Options\AbstractSiteOptions;
use XelaxSiteConfig\Form\AbstractSiteConfigForm;

/**
 * Description of SiteConfigController
 *
 * @author schurix
 */
abstract class SiteConfigController extends AbstractActionController implements SiteConfigAwareInterface{
	use SiteConfigAwareTrait;
	
	/** @var EntityManager */
	protected $entityManager;
	
	/** @var Translator */
	protected $translator = null;
	
	/**
	 * @return EntityManager
	 */
	public function getEntityManager(){
		if(null === $this->entityManager){
			$this->entityManager = $this->getServiceLocator()->get(EntityManager::class);
		}
		return $this->entityManager;
	}
	
	/**
	 * @return Translator
	 */
	public function getTranslator() {
		if(null === $this->translator){
			$this->translator = $this->getServiceLocator()->get('MVCTranslator');
		}
		return $this->translator;
	}
	
	/**
	 * Returns current configuration
	 * @return AbstractSiteOptions
	 */
	abstract public function getConfig();
	
	/**
	 * Returns config form
	 * @return AbstractSiteConfigForm
	 */
	abstract public function getForm();
	
	/**
	 * Returns dot-separated prefix for configuration
	 * @return string
	 */
	abstract public function getConfigPrefix();
	
	/**
	 * Returns heading of indexAction view
	 * @return string
	 */
	abstract public function getIndexTitle();
	
	/**
	 * Returns heading of editAction view
	 * @return string
	 */
	abstract public function getEditTitle();
	
	
	
	/**
	 * Show current config
	 * @return ViewModel
	 */
	public function indexAction(){
		$configForm = $this->getForm();
		$configForm->setData($this->getConfig());
		
		$view = new ViewModel(array('title' => $this->getEditTitle(), 'configForm' => $configForm));
		$view->setTemplate('xelax-site-config/site-config/index.phtml');
		return $view;
	}
	
	/**
	 * Edit config
	 * @return ViewModel
	 */
	public function editAction() {
		$configForm = $this->getForm();
        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
		
        if ($request->isPost()) {
			$data = $request->getPost();
			$configForm->setData($data);
			if ($configForm->isValid()) {
				$configData = $configForm->getData();
				$flatConfig = $this->flattenConfig($configData['config'], $this->getConfigPrefix());
				$this->saveConfig($flatConfig);
				
				$this->flashMessenger()->addSuccessMessage($this->getTranslator()->translate('Configuration successfully saved'));
				return $this->_redirectToIndex();
			}
        } else {
			$configForm->setData($this->getConfig());
		}
		
		$view = new ViewModel(array('title' => $this->getEditTitle(), 'configForm' => $configForm));
		$view->setTemplate('xelax-site-config/site-config/edit.phtml');
		return $view;
	}
	
	protected function _redirectToIndex(){
		return $this->redirect()->toRoute('zfcadmin/siteconfig');
	}
	
	protected function flattenConfig($config, $prefix = ''){
		$prefix = ltrim($prefix, '.');
		$res = array();
		foreach ($config as $key => $value) {
			if(is_array($value)){
				$flat = $this->flattenConfig($value,  $prefix.'.'.$key);
				$res = array_merge($res, $flat);
			} else {
				$res[ltrim($prefix.'.'.$key, '.')] = $value;
			}
		}
		return $res;
	}
	
	protected function saveConfig($config){
		$em = $this->getEntityManager();
		$repo = $em->getRepository(SiteConfig::class);
		
		foreach ($config as $key => $value) {
			$existing = $repo->findOneBy(array('confKey' => $key));
			if(!$existing){
				$existing = new SiteConfig();
				$existing->setKey($key);
				$em->persist($existing);
			}
			$existing->setValue($value);
		}
		return $em->flush();
	}
}
