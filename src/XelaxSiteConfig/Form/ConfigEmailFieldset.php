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

namespace XelaxSiteConfig\Form;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use XelaxSiteConfig\Options\EmailConfigOptions;

/**
 * ConfigEmailFieldset Fieldset
 *
 * @author schurix
 */
class ConfigEmailFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface{
	use ServiceLocatorAwareTrait;
	
	protected $configOptions;
	
	public function __construct($name = "", $options = array()){
		if($name == ""){
			$name = 'ConfigEmailFieldset';
		}
		parent::__construct($name, $options);
	}
	
	/**
	 * @return EmailConfigOptions
	 */
	public function getConfigOptions(){
		if(null === $this->configOptions){
			$this->configOptions = $this->getServiceLocator()->getServiceLocator()->get(EmailConfigOptions::class);
		}
		return $this->configOptions;
	}

	public function init(){
		$options = $this->getConfigOptions();
		
		$this->add(array(
            'name' => 'type',
            'type' => 'select',
            'options' => array(
                'label' => gettext_noop('Transport type'),
                'value_options' => $options->getAllowedEmailTransports(),
				'column-size' => 'sm-10',
				'label_attributes' => array(
					'class' => 'col-sm-2',
				),
            )
        ));
		
		$this->add(array(
			'name' => 'smtp_options',
            'type' => SmtpOptionsFieldset::class,
            'options' => array(
				'label' => gettext_noop('SMTP Options (only for type SMTP)'),
                'use_as_base_fieldset' => false,
            ),
        ));
		
	}
	
	public function getInputFilterSpecification() {
		$filters = array(
			'type' => array(
				'required' => true,
			),
		);
		return $filters;
	}
}
