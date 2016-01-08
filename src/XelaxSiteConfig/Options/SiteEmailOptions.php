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

namespace XelaxSiteConfig\Options;

use Zend\Mail\Transport\Sendmail;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\File;

/**
 * Site options to set up Zend\Mail\Transport
 *
 * @author schurix
 */
class SiteEmailOptions extends AbstractSiteOptions{
	/** @var string */
	protected $type = Sendmail::class;
	
	/** @var array */
	protected $smtpOptions = array();
	
	/** @var array*/
	protected $fileOptions = array();
	
	public function getType() {
		return $this->type;
	}

	public function getSmtpOptions() {
		return $this->smtpOptions;
	}
	
	public function getFileOptions() {
		return $this->fileOptions;
	}

	public function setType($type) {
		$this->type = $type;
		return $this;
	}

	public function setSmtpOptions($smtpOptions) {
		$this->smtpOptions = $smtpOptions;
		return $this;
	}

	public function setFileOptions($fileOptions) {
		$this->fileOptions = $fileOptions;
		return $this;
	}
	
	public function getTransportConfig(){
		$options = array(
			'type' => $this->getType()
		);
		if($this->getType() === Smtp::class){
			$smtpOptions = $this->getSmtpOptions();
			$connectionOptions = array();
			if(isset($smtpOptions['connection_options'])){
				$connectionOptions = $smtpOptions['connection_options'];
			} else {
				$connectionKeys = array('username', 'password', 'ssl', 'tls');
				foreach($connectionKeys as $connectionKey){
					if(isset($smtpOptions[$connectionKey])){
						$connectionOptions[$connectionKey] = $smtpOptions[$connectionKey];
						unset($smtpOptions[$connectionKey]);
					}
				}
			}
			$smtpOptions['connection_config'] = $connectionOptions;
			$options['options'] = $smtpOptions;
		}
		if($this->getType() === File::class){
			$options['options'] = $this->getFileOptions();
		}
		return $options;
	}
	
	public function toArray() {
		$data = array();
		if($this->getType() === Smtp::class){
			$data['options'] = $this->getSmtpOptions();
		} elseif($this->getType() === File::class){
			$data['options'] = $this->getFileOptions();
		}
		return $data;
	}
}
