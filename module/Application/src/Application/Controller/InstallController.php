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
			
			$adapter = new \Zend\Db\Adapter\Adapter ( $adapter = new \Zend\Db\Adapter\Adapter ( array (
				'driver' => 'Mysqli',
				'database' => $params ['dbname'],
				'username' => $params ['username'],
				'password' => $params ['password'] 
			) ) );
			
			$metadata = new \Zend\Db\Metadata\Metadata ( $adapter );
			
			// get the table names
			$tableNames = $metadata->getTableNames ();
			
			foreach ( $tableNames as $tableName ) {
				echo 'In Table ' . $tableName . PHP_EOL;
				
				$table = $metadata->getTable ( $tableName );
				
				echo '    With columns: ' . PHP_EOL;
				foreach ( $table->getColumns () as $column ) {
					echo '        ' . $column->getName () . ' -> ' . $column->getDataType () . PHP_EOL;
				}
				
				echo PHP_EOL;
				echo '    With constraints: ' . PHP_EOL;
				
				foreach ( $metadata->getConstraints ( $tableName ) as $constraint ) {
					/**
					 *
					 * @var $constraint Zend\Db\Metadata\Object\ConstraintObject
					 */
					echo '        ' . $constraint->getName () . ' -> ' . $constraint->getType () . PHP_EOL;
					if (! $constraint->hasColumns ()) {
						continue;
					}
					echo '            column: ' . implode ( ', ', $constraint->getColumns () );
					if ($constraint->isForeignKey ()) {
						$fkCols = array ();
						foreach ( $constraint->getReferencedColumns () as $refColumn ) {
							$fkCols [] = $constraint->getReferencedTableName () . '.' . $refColumn;
						}
						echo ' => ' . implode ( ', ', $fkCols );
					}
					echo PHP_EOL;
				}
				
				echo '----' . PHP_EOL;
			}
		}
	}
}
