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
use Zend\Stdlib\AbstractOptions;

use Zend\Mail\Transport\Sendmail;

/**
 * Description of SiteEmailOptions
 *
 * @author schurix
 */
class SiteEmailOptions extends AbstractOptions{
	/** @var string */
	protected $type = Sendmail::class;
	
	/** @var array */
	protected $smtpOptions = array();
	
	public function getType() {
		return $this->type;
	}

	public function getSmtpOptions() {
		return $this->smtpOptions;
	}

	public function setType($type) {
		$this->type = $type;
		return $this;
	}

	public function setSmtpOptions($smtpOptions) {
		$this->smtpOptions = $smtpOptions;
		return $this;
	}


	
}
