<?php

namespace TokenAuthentication\Mapper;

class Container {
	
	/**
	 * @var array
	 */
	private $data;
	
	public function __construct($tokenData, $key) {
		$this->data = $tokenData;
	}
	
	/**
	 * @return string
	 */
	public function getUsername() {
		return $this->data['USERNAME'];
	}

	/**
	 * @return integer
	 */
	public function getCreationTime() {
		return $this->data['CREATION_TIME'];
	}

	/**
	 * @return string
	 */
	public function getHash() {
		return $this->data['TOKEN'];
	}
}

