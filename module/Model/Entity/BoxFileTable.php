<?php

namespace Model\Entity;

class BoxFileTable extends \Model\Entity\EntityTable
{

    public function __construct(\Zend\Db\TableGateway\TableGateway $tableGateway)
    {
        parent::__construct ( $tableGateway );
    }


}

