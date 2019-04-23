<?php

namespace Hcode;

/**
 * 
 */
class Model
{
	
	private $values = [];
	
	public function __call($name, $args)
	{
		$method = substr($name, 0, 3);
		$fieldName = substr($name, 3, strlen($name ));

		//var_dump($method, $fieldName);
		
		switch ($method)
		{
			case 'get':
				return (isset($this->values[$fieldName]) ? $this->values[$fieldName] : NULL);
				break;

			case 'set':
				$this->values[$fieldName] = $args[0];
				break;
		}
		
		//exit;
	}

	public function setData($data = array())
	{
		foreach ($data as $key => $value) 
		{
			//print_r($data[$key]);
			$this->{"set".$key}($value);
		}
	}

	public function getValues()
	{
		return $this->values;
	}
}

?>