<?php

namespace TokenAuthentication\Authentication\Adapter;

use Zend\Authentication\Adapter\AdapterInterface;
use TokenAuthentication\Mapper\Container;
use Zend\Authentication\Result;
use TokenAuthentication\Exception;
use Users\Identity;
use Users\Db\Mapper;
use ZendServer\Log\Log;
class TokenAdapter implements AdapterInterface {
	
	/**
	 * @var string
	 */
	private $token;
	
	/**
	 * @var \TokenAuthentication\Mapper\Token
	 */
	private $tokenMapper;
	
	/**
	 * @var Mapper
	 */
	private $usersMapper;
	
	/* (non-PHPdoc)
	 * @see \Zend\Authentication\Adapter\AdapterInterface::authenticate()
	 */
	public function authenticate() {
		/// retrieve the token parameter
    	$tokenMapper = $this->getTokenMapper();
    	/// authenticate the token
    	try {
    		$token = $tokenMapper->findTokenByHash($this->token);
    	} catch (Exception $ex) {
    		return new Result(Result::FAILURE_CREDENTIAL_INVALID, $this->token);
    	}
    	
    	try {
	    	/// retrieve username
	    	$username = $token->getUsername();
	    	$user = $this->getUsersMapper()->findUserByName($username);
    	} catch (\ZendServer\Exception $ex) {
    		return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, $username, array($ex->getMessage()));
    	}
    	
    	$role = $user['ROLE'];
    	/// generate identity
    	return new Result(Result::SUCCESS, new Identity($username, $role));
	}

	/**
	 * @return \TokenAuthentication\Mapper\Token
	 */
	public function getTokenMapper() {
		return $this->tokenMapper;
	}
	
	/**
	 * @return Mapper
	 */
	public function getUsersMapper() {
		return $this->usersMapper;
	}

	/**
	 * @param \Users\Db\Mapper $usersMapper
	 */
	public function setUsersMapper($usersMapper) {
		$this->usersMapper = $usersMapper;
	}

	/**
	 * @param string $token
	 */
	public function setToken($token) {
		$this->token = $token;
	}

	/**
	 * @param \TokenAuthentication\Mapper\Token $tokenMapper
	 */
	public function setTokenMapper($tokenMapper) {
		$this->tokenMapper = $tokenMapper;
	}


	
}

