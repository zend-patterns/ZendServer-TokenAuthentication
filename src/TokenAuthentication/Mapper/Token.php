<?php

namespace TokenAuthentication\Mapper;

use Configuration\MapperAbstract;
use Application\Module as appModule;
use Zend\Crypt\Hmac;
use WebAPI\Db\Mapper;
use Zend\Math\Rand;
use ZendServer\Exception;
use ZendServer\Set;
use ZendServer\Log\Log;
use WebAPI\Db\ApiKeyContainer;
use Users;
use Users\IdentityAwareInterface;
use Zend\Db\Sql\Expression;
use TokenAuthentication\Exception as TokenException;

class Token extends MapperAbstract implements IdentityAwareInterface {
	
	/**
	 * Token expiration in seconds
	 */
	const TOKEN_EXPIRATION = 30;
	
	protected $setClass = '\TokenAuthentication\Mapper\Container';
	
	/**
	 * @var Mapper
	 */
	private $webapiMapper;

	/**
	 * @var Users\Identity
	 */
	private $identity;
	
	/**
	 * @param string $hash
	 * @return Container
	 */
	public function findTokenByHash($hash) {
		$this->gc();
		$result = $this->select(array('TOKEN' => $hash));
		if ($result instanceof Set) {
			return $result->current();
		}
		throw new TokenException(_t('No token found')); // TRANSLATE
	}
	
	/**
	 * @throws Exception
	 * @return \ArrayObject
	 */
	public function generateTokenByIdentity() {
		$identity = $this->identity->getIdentity();
		if (! $identity) {
			throw new TokenException(_t('No identity set'));
		}
		
		$key = $this->getWebapiMapper()->findKeyByName($identity);
		if (! ($key instanceof ApiKeyContainer)) {
			throw new TokenException(_t('Key name was not found'));
		}
		
		/// clean out any current existing key
		$this->getTableGateway()->delete(array('USERNAME' => $key->getUsername()));
		
		$token = Hmac::compute($key->getHash(), 'sha256', Rand::getString(Hmac::getOutputSize('sha256'), null, true));
		
		if (appModule::isSingleServer()) {
			$creationTime = new Expression("strftime('%s', 'now')");
		} else {
			$creationTime = new Expression('UNIX_TIMESTAMP()');
			
		}
		
		$this->getTableGateway()->insert(array('USERNAME' => $key->getUsername(), 'TOKEN' => $token, 'CREATION_TIME' => $creationTime));
		$tokenId = $this->getTableGateway()->getLastInsertValue();
		return $this->select(array('TOKEN_ID' => $tokenId))->current();
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
		$expire = self::TOKEN_EXPIRATION;
		if (appModule::isSingleServer()) {
	 		$this->getTableGateway()->delete("(CREATION_TIME + {$expire}) < CAST(strftime('%s', 'now') AS integer)");
		} else {
	 		$this->getTableGateway()->delete("(CREATION_TIME + {$expire}) < UNIX_TIMESTAMP()");
		}
	}
	

}