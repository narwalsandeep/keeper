<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 *
 * @author sandeepnarwal
 *        
 */
class InstallController extends AbstractActionController {
	/*
	 * (non-PHPdoc)
	 * @see \Zend\Mvc\Controller\AbstractActionController::indexAction()
	 */
	public function indexAction() {
		$view = new ViewModel ();
		return $view;
	}
	
	/**
	 */
	public function startAction() {
		
		// if posted db names etc
		if ($this->getRequest ()->isPost ()) {
			
			$params = $this->params ()->fromPost ();
			
			$this->adapter = new \Zend\Db\Adapter\Adapter ( array (
				'driver' => 'mysqli',
				'host' => ($params ['host'] == "") ? "localhost" : $params ['host'],
				'database' => $params ['dbname'],
				'username' => $params ['username'],
				'password' => $params ['password'] 
			) );
			
			$this->_processDdl ();
		}
		
		die ( "<br> -- Finished ---" );
	}
	
	/**
	 */
	private function _processDdl() {
		
		// this will turn off buffering,
		// so we see outout right away
		while ( ob_get_level () > 0 )
			ob_end_flush ();
			
			/*
		 *
		 * $table = new \Zend\Db\Sql\Ddl\CreateTable ( 'keyper_bar' );
		 *
		 * $table->addColumn ( new \Zend\Db\Sql\Ddl\Column\Integer ( 'id' ) );
		 * $table->addColumn ( new \Zend\Db\Sql\Ddl\Column\Varchar ( 'name', 200 ) );
		 * $table->addConstraint ( new \Zend\Db\Sql\Ddl\Constraint\PrimaryKey ( 'id' ) );
		 *
		 * $sql = new \Zend\Db\Sql\Sql ( $this->adapter );
		 *
		 * $this->adapter->query ( $sql->getSqlStringForSqlObject ( $table ), \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE );
		 */
		echo "<div>";
		try {
			require_once DOC_ROOT . "/config/schema/ddl.php";
			foreach ( $schema as $key => $value ) {
				
				$this->validateTable ( $key );
				$this->createTable ( $key, $prefix );
				
				// create table
				$this->createColumn ( "id", "integer", 11, true );
				
				if (count ( $value ['associate'] )) {
					foreach ( $value ['associate'] as $key_3 => $value_3 ) {
						$this->table->addConstraint ( new \Zend\Db\Sql\Ddl\Constraint\ForeignKey ( $value_3, $value_3, $prefix . $key_3, "id", "cascade", "cascade" ) );
					}
				}
				
				// create columns
				foreach ( $value ['columns'] as $key_2 => $value_2 ) {
					
					$col = $this->validateColumn ( $value_2 [0] );
					$data_type = $this->validateDataType ( $value_2 [1] ['data_type'] [0] );
					$data_type_param = ($data_type == "varchar") ? 200 : $value_2 [1] ['data_type'] [1];
					$this->createColumn ( $col, $data_type, $data_type_param, false );
				}
				
				// execute now
				$sql = new \Zend\Db\Sql\Sql ( $this->adapter );
				echo $sql->getSqlStringForSqlObject ( $this->table );
				echo "<br>";
				$this->adapter->query ( $sql->getSqlStringForSqlObject ( $this->table ), \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE );
				
				// create physical file now
				$this->generateClassFile ( $key, $value );
			}
		} catch ( \Exception $e ) {
			echo "<span class=''>{$e->getMessage()}</span>";
		}
		
		echo "</div>";
	}
	
	/**
	 *
	 * @param unknown $col        	
	 * @param unknown $data_type        	
	 */
	private function createTable($table,$prefix) {
		echo "<br><span class='label label-info'>{$prefix}{$table}</span> ";
		$this->table = new \Zend\Db\Sql\Ddl\CreateTable ( $prefix.$table );
	}
	
	/**
	 *
	 * @param unknown $col        	
	 * @param unknown $data_type        	
	 */
	private function createColumn($col, $data_type, $data_type_params, $primary = null) {
		$data_type_class = ucfirst ( $data_type );
		$type = "\Zend\Db\Sql\Ddl\Column\\" . $data_type_class;
		$this->table->addColumn ( new $type ( $col, $data_type_params ) );
		if ($primary)
			$this->table->addConstraint ( new \Zend\Db\Sql\Ddl\Constraint\PrimaryKey ( $col ) );
		
		echo "<span class='label label-default'>" . $col . " " . $data_type . "(" . $data_type_params . ")</span> ";
	}
	
	/**
	 *
	 * @param unknown $table        	
	 */
	private function validateTable($table) {
	}
	
	/**
	 *
	 * @param unknown $col        	
	 */
	private function validateColumn($col) {
		if ($col == "") {
			die ( "<span class='label label-danger'>Column seems to be empty !!</span>" );
		}
		
		return $col;
	}
	
	/**
	 *
	 * @param unknown $data_type        	
	 * @return string
	 */
	private function validateDataType($data_type) {
		if ($data_type == "")
			$data_type = "varchar";
		
		return $data_type;
	}
	
	/**
	 *
	 * @param unknown $key        	
	 * @param unknown $value        	
	 */
	private function generateClassFile($key, $value) {
		$pathArray = explode ( "\\", $value ['entity'] );
		$className = $pathArray [count ( $pathArray ) - 1];
		
		unset ( $pathArray [count ( $pathArray ) - 1] );
		
		$nameSpace = implode ( "\\", $pathArray );
		
		$this->generateEntityFile ( $className, $nameSpace, $value );
	}
	
	/**
	 *
	 * @param unknown $className        	
	 * @param unknown $nameSpace        	
	 * @param unknown $value        	
	 */
	private function generateEntityFile($className, $nameSpace, $value) {
		$this->generateEntityFileClass ( $className, $nameSpace, $value );
		$this->generateEntityFileClassTable ( $className, $nameSpace, $value );
		$this->generateEntityFileClassFinder ( $className, $nameSpace, $value );
	}
	
	/**
	 *
	 * @param unknown $className        	
	 * @param unknown $nameSpace        	
	 * @param unknown $value        	
	 */
	private function generateEntityFileClass($className, $nameSpace, $value) {
		
		// Passing configuration to the constructor:
		$file = new \Zend\Code\Generator\FileGenerator ( array (
			'classes' => array (
				new \Zend\Code\Generator\ClassGenerator ( $className, $nameSpace, null, "\Model\Entity\Entity", array (), array (), array () ) 
			) 
		) );
		
		// Render the generated file
		$file->generate ();
		$classFile = DOC_ROOT . '\\Module\\' . $value ['entity'] . '.php';
		fopen ( $classFile, "w" );
		
		// or write it to a file:
		file_put_contents ( $classFile, $file->generate () );
	}
	
	/**
	 *
	 * @param unknown $className        	
	 * @param unknown $nameSpace        	
	 * @param unknown $value        	
	 */
	private function generateEntityFileClassTable($className, $nameSpace, $value) {
		
		// Passing configuration to the constructor:
		$file = new \Zend\Code\Generator\FileGenerator ( array (
			'classes' => array (
				new \Zend\Code\Generator\ClassGenerator ( $className . "Table", $nameSpace, null, "\Model\Entity\EntityTable", array (), array (), array (
					new \Zend\Code\Generator\MethodGenerator ( '__construct', array (
						array (
							"name" => "tableGateway",
							"type" => "\Zend\Db\TableGateway\TableGateway" 
						) 
					), 'public', 'parent::__construct ( $tableGateway );' ) 
				) ) 
			) 
		) );
		
		// Render the generated file
		$file->generate ();
		$classFile = DOC_ROOT . '\\Module\\' . $value ['entity'] . 'Table.php';
		fopen ( $classFile, "w" );
		
		// or write it to a file:
		file_put_contents ( $classFile, $file->generate () );
	}
	
	/**
	 *
	 * @param unknown $className        	
	 * @param unknown $nameSpace        	
	 * @param unknown $value        	
	 */
	private function generateEntityFileClassFinder($className, $nameSpace, $value) {
		
		// Passing configuration to the constructor:
		$file = new \Zend\Code\Generator\FileGenerator ( array (
			'classes' => array (
				new \Zend\Code\Generator\ClassGenerator ( $className . "Finder", $nameSpace, null, "\Model\Entity\EntityFinder", array (), array (), array () ) 
			) 
		) );
		
		// Render the generated file
		$file->generate ();
		$classFile = DOC_ROOT . '\\Module\\' . $value ['entity'] . 'Finder.php';
		fopen ( $classFile, "w" );
		
		// or write it to a file:
		$returnFlag = file_put_contents ( $classFile, $file->generate () );
		
		if ($returnFlag > 0) {
			echo "<span class='glyphicon glyphicon-ok text text-success'></span>";
		} else {
			echo "<span class='glyphicon glyphicon-remove text text-danger'></span>";
		}
	}
}
