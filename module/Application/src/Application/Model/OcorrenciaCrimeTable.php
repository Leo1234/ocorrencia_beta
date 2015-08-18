<?php

namespace Application\Model;

use Zend\Db\Adapter\Adapter,
    Zend\Db\ResultSet\ResultSet,
    Zend\Db\TableGateway\TableGateway,
    Zend\Db\Sql\Select,
    Zend\Paginator\Adapter\DbSelect,
    Zend\Paginator\Paginator;

class OcorrenciaCrimeTable {

    protected $tableGateway;
    protected $adapter;
    protected $resultSetPrototype;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new OcorrenciaCrime());
        $this->tableGateway = new TableGateway('ocorrencia_crime', $this->adapter, null, $this->resultSetPrototype);
    }
    
       
        public function isHomicidio($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id_ocorrencia' => $id));
        $row = $rowset->current();
        if (!$row)
            return false;
       else 
          $row;
    }
    
  

}