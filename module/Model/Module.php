<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Admin for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Model;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Model\Entity\UserTable;
use Model\Entity\User;

/**
 *
 * @author sandeepnarwal
 *        
 */
class Module implements AutoloaderProviderInterface {
	
	/**
	 *
	 * @var unknown
	 */
	public static $table_prefix = "bv2_";
	
	/**
	 *
	 * @var unknown
	 */
	public static $table_map = array (
		"user" => array (
			"entity" => "\Model\Entity\User",
			"associate" => array (
				"business_agent" => "agent_id",
				"calendar" => "agent_id",
				"service" => "agent_id",
				"booking" => array (
					"customer_id",
					"agent_id" 
				) 
			),
			"columns" => array (
				"id",
				"first_name",
				"last_name",
				"username",
				"passwd",
				"mobile",
				"telephone",
				"dated",
				"status" 
			) 
		),
		"business" => array (
			"entity" => "\Model\Entity\Business",
			"associate" => array (
				"business_agent" => "business_id" 
			),
			"columns" => array (
				"id",
				"name",
				"description",
				"address",
				"geo_latitude",
				"geo_longitude",
				"mobile",
				"telephone",
				"fax",
				"website",
				"dated",
				"status" 
			) 
		),
		"business_agent" => array (
			"entity" => "\Model\Entity\BusinessAgent",
			"associate" => array (),
			"columns" => array (
				"id",
				"business_id",
				"agent_id",
				"dated" 
			) 
		),
		"calendar" => array (
			"entity" => "\Model\Entity\Calendar",
			"associate" => array (),
			"columns" => array (
				"id",
				"agent_id",
				"name",
				"dated" 
			) 
		),
		"service" => array (
			"entity" => "\Model\Entity\Service",
			"associate" => array (
				"service_availability" => "service_id" 
			),
			"columns" => array (
				"id",
				"agent_id",
				"parent_id",
				"name",
				"price",
				"currency",
				"price_unit",
				"dated" 
			) 
		),
		"service_availability" => array (
			"entity" => "\Model\Entity\ServiceAvailability",
			"associate" => array (),
			"columns" => array (
				"id",
				"service_id",
				"from_timestamp",
				"to_timestamp",
				"dated" 
			) 
		),
		"booking" => array (
			"entity" => "\Model\Entity\Booking",
			"associate" => array (
				"booking_detail" => "booking_id" 
			),
			"columns" => array (
				"id",
				"agent_id",
				"customer_id",
				"dated" 
			) 
		),
		"booking_detail" => array (
			"entity" => "\Model\Entity\BookingDetail",
			"associate" => array (),
			"columns" => array (
				"id",
				"booking_id",
				"from_timetamp",
				"to_timestamp",
				"status",
				"dated" 
			) 
		) 
	);
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see \Zend\ModuleManager\Feature\AutoloaderProviderInterface::getAutoloaderConfig()
	 */
	public function getAutoloaderConfig() {
		return array (
			'Zend\Loader\StandardAutoloader' => array (
				'namespaces' => array (
					__NAMESPACE__ => __DIR__ 
				) 
			) 
		);
	}
	
	/**
	 *
	 * @return multitype:multitype:NULL |\Model\Entity\UserTable|\Zend\Db\TableGateway\TableGateway
	 */
	public function getServiceConfig() {
		
		// map each table in service factories
		foreach ( self::$table_map as $key => $value ) {
			$entity = $value ["entity"] . 'Table';
			$name = self::$table_prefix . $key;
			$gateway = $value ['entity'] . 'Gateway';
			// set table factory
			$factory [$value ['entity']] = function ($sm) use($name, $entity, $gateway) {
				$tableGateway = $sm->get ( $gateway );
				$table = new $entity ( $tableGateway );
				return $table;
			};
			
			// set gateway factory
			$factory [$gateway] = function ($sm) use($name, $entity) {
				$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
				$initResultSet = new ResultSet ();
				$initResultSet->setArrayObjectPrototype ( new \Model\Entity\User () );
				return new TableGateway ( $name, $dbAdapter, null, $initResultSet );
			};
		}
		// return all factories
		return array (
			'factories' => $factory 
		);
	}
	
	/**
	 *
	 * @param MvcEvent $e        	
	 */
	public function onBootstrap(MvcEvent $e) {
	}
}
