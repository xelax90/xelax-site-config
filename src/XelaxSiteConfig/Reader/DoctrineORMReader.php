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

namespace XelaxSiteConfig\Reader;

use Eye4web\SiteConfig\Reader\DoctrineORMReader as EyeReader;

/**
 * Description of DoctrineORMReader
 *
 * @author schurix
 */
class DoctrineORMReader extends EyeReader{
	public function getArray() {
		$flat = parent::getArray();
		return $this->unflatten($flat);
	}
	
	protected function unflatten($array){
		$result = array();
		foreach($array as $key=>$value){
			if(rawurlencode($key) !== $key){
				throw new \Exception(sprintf("Unallowed characters in key '%s'", $key));
			}
			$value = rawurlencode($value);
			if(strpos($key,'.') !== false){
				parse_str('result['.str_replace('.','][',$key)."]=".$value);
			} else {
				$result[$key] = $value;
			}
		}
		array_walk_recursive($result, function(&$val){
			try{
				$decoded = json_decode($val);
				if(is_array($decoded)){
					$val = $decoded;
				}
			} catch (\Exception $ex) {}
		});
		return $result;
	}
}
