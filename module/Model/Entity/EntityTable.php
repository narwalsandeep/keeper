<?php

namespace Model\Entity;

use Zend\Db\TableGateway\TableGateway;

/**
 * Every table must extend this, it provides gateway and other relevant db tasks
 * for common CRUD or search, count, fetch task, extend here
 * This should NOT contain table specific business logics or App specific business logics
 *
 * @author sandeepnarwal
 *        
 */
abstract class EntityTable implements \Zend\ServiceManager\ServiceLocatorAwareInterface {
	
	/**
	 *
	 * @var unknown
	 */
	protected $serviceLocator;
	
	/**
	 *
	 * @var unknown
	 */
	public $err;
	
	/**
	 *
	 * @var TableGateway
	 */
	public $tableGateway;
	
	/**
	 *
	 * @var unknown
	 */
	protected $errorMessage = null;
	
	/**
	 *
	 * @param TableGateway $tableGateway        	
	 */
	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}
	
	/**
	 *
	 * @return unknown
	 */
	public function getFinder() {
		$class = get_class ( $this );
		$class = substr ( $class, 0, strlen ( $class ) - 5 );
		$class = $class . "Finder";
		return new $class ( $this );
	}
	
	/**
	 *
	 * @return \Model\Entity\unknown
	 */
	public function hasError() {
		return $this->err;
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::getServiceLocator()
	 */
	public function getServiceLocator() {
		return $this->serviceLocator;
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
	 */
	public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
	}
	
	/**
	 *
	 * @return \Model\Entity\unknown
	 */
	public function getErrorMessage() {
		return $this->errorMessage;
	}
	
	/**
	 *
	 * @param unknown $msg        	
	 */
	public function setErrorMessage($msg) {
		$this->errorMessage .= $msg;
	}
	
	/**
	 */
	public function clearErrorMessage() {
		$this->errorMessage = null;
	}
	
	/**
	 *
	 * @param unknown $code        	
	 */
	public function setErrorCode($code) {
		$this->errorCode = $code;
	}
	
	/**
	 */
	public function getErrorCode() {
		return $this->errorCode;
	}
	
	/**
	 */
	public function clearErrorCode() {
		$this->errorCode = null;
	}
	
	/**
	 *
	 * @return TableGateway
	 */
	public function getTableGateway() {
		return $this->tableGateway;
	}
	
	/**
	 *
	 * @param Entity $entity        	
	 * @throws \Exception
	 * @return number
	 */
	public function save(Entity $entity, $where = null) {
		$table = substr ( $this->getTableGateway ()->table, strlen ( \Model\Module::$table_prefix ) );
		$columns = \Model\Module::$table_map [$table] ['columns'];
		foreach ( $columns as $key => $value ) {
			if (property_exists ( $entity, $value ))
				$data [$value] = $entity->{$value};
		}
		
		try {
			
			if (! is_array ( $where )) {
				// insert and return last ID
				$this->tableGateway->insert ( $data );
				return $this->tableGateway->lastInsertValue;
			} else {
				$this->tableGateway->update ( $data, $where );
				return true;
			}
		} catch ( \Exception $e ) {
			return \Model\Custom\Error::trigger ( $e, $params );
		}
	}
	
	/**
	 * delete by id of the table or array condition
	 *
	 * @param int/array $id        	
	 */
	public function delete($id) {
		try {
			if (is_array ( $id )) {
				$effectedRows = $this->tableGateway->delete ( $id );
			} else {
				$effectedRows = $this->tableGateway->delete ( array (
					'id' => ( int ) $id 
				) );
			}
		} catch ( \Exception $e ) {
			return \Model\Custom\Error::trigger ( $e, $params );
		}
		
		return $effectedRows;
	}
}
