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

use XelaxSiteConfig\Form\ConfigEmailForm;
use XelaxSiteConfig\Options\SiteEmailOptions;
use XelaxSiteConfig\Options\Service\SiteEmailOptionsFactory;

/**
 * Description of EmailConfigController
 *
 * @author schurix
 */
class EmailConfigController extends SiteConfigController {
	/** @var SiteEmailOptions */
	protected $emailConfig;
	
	/**
	 * {@inheritDoc}
	 */
	public function getConfig() {
		if(null === $this->emailConfig){
			$this->emailConfig = $this->getServiceLocator()->get(SiteEmailOptions::class);
		}
		return $this->emailConfig;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getForm() {
		/* @var $emailForm ConfigEmailForm */
		$emailForm = $this->getServiceLocator()->get('FormElementManager')->get(ConfigEmailForm::class);
		return $emailForm;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getConfigPrefix() {
		return SiteEmailOptionsFactory::CONFIG_PREFIX;
	}
	
	public function getIndexTitle() {
		return gettext_noop('E-Mail Configuration');
	}
	
	public function getEditTitle() {
		return gettext_noop('E-Mail Configuration');
	}
}
