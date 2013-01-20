<?php

namespace TokenAuthentication\Mapper;

use Configuration\MapperAbstract;
use Application\Module as appModule;
use Zend\Crypt\Hmac;
use WebAPI\Db\Mapper;
use Zend\Math\Rand;
use ZendServer\Exception;
use WebAPI\Db\ApiKeyContainer;
use Users;
use Users\IdentityAwareInterface;
use Zend\Db\Sql\Expression;

class Token extends MapperAbstract implements IdentityAwareInterface {
	
	/**
	 * Token expiration in seconds
	 */
	const TOKEN_EXPIRATION = 30;
	
	/**
	 * @var Mapper
	 */
	private $webapiMapper;

	/**
	 * @var Users\Identity
	 */
	private $identity;
	
	/**
	 * @param string $username
	 * 
	 */
	public function findToken($username) {
		$this->gc();
		return $this->select(array('USERNAME' => $username))->current();
	}
	
	public function generateTokenByIdentity() {
		$identity = $this->identity->getIdentity();
		if (! $identity) {
			throw new Exception(_t('No identity set'));
		}
		
		$key = $this->getWebapiMapper()->findKeyByName($identity);
		if (! ($key instanceof ApiKeyContainer)) {
			throw new Exception(_t('Key name was not found'));
		}
		
		$token = Hmac::compute($key->getHash(), 'sha256', Rand::getString(Hmac::getOutputSize('sha256'), null, true));
		
		if (appModule::isSingleServer()) {
			$creationTime = new Expression("strftime('%s', 'now')");
		} else {
			$creationTime = new Expression('UNIX_TIMESTAMP()');
			
		}
		
		$this->getTableGateway()->insert(array('USERNAME' => $identity, 'TOKEN' => $token, 'CREATION_TIME' => $creationTime));
		
		return $token;
	}
	
	/**
	 * @return \WebAPI\Db\Mapper
	 */
	public function getWebapiMapper() {
		return $this->webapiMapper;
	}
	
	/**
	 * @param \WebAPI\Db\Mapper $webapiMapper
	 */
	public function setWebapiMapper($webapiMapper) {
		$this->webapiMapper = $webapiMapper;
	}

	/**
	 * @param Users\Identity $identity
	 * @return \TokenAuthentication\Controller\WebAPIController
	 */
	public function setIdentity(Users\Identity $identity) {
		$this->identity = $identity;
		return $this;
	}
	
	private function gc() {
		if (appModule::isSingleServer()) {
	 		$this->getTableGateway()->delete("CREATION_TIME + 30 < strftime('%s', 'now')");
		} else {
	 		$this->getTableGateway()->delete("CREATION_TIME + 30 < UNIX_TIMESTAMP()");
		}
	}
	

}