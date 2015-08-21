<?php

namespace Application\Model;

use Zend\Db\Adapter\Adapter,
    Zend\Db\ResultSet\ResultSet,
    Zend\Db\TableGateway\TableGateway,
    Zend\Db\Sql\Select,
    Zend\Paginator\Adapter\DbSelect,
    Zend\Paginator\Paginator;

class LesaoTable {

    protected $tableGateway;
    protected $adapter;
    protected $resultSetPrototype;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Lesao());
        $this->tableGateway = new TableGateway('lesao', $this->adapter, null, $this->resultSetPrototype);
    }
    
        public function findLesaoOcorrencia($id){
        $id = (int) $id;
        $select = new Select;
        $select->from('lesao');
        $select->columns(array('*'));
        $select->where(array('lesao.id_ocorrencia' => $id));

        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();
       
        if (!$row)
            throw new \Exception("Não foi encontrado dados da lesão de id = {$id}");
        return $row;
    }
    
        public function addLesao(Lesao $les, $id_oco) {

        $data = array(
            'qtdeL' => $les->getQtdeL(),
            'tipo_les' => $les->getTipo_les(),
            'id_ocorrencia' => $id_oco,
            'id_crime' => 2,
        );
        $this->tableGateway->insert($data);
        return $this->tableGateway->lastInsertValue;
    }
    
        public function update(Lesao $les, $id_oco) {
            //print_r($ho);
     $data = array(
            'qtdeL' => $les->getQtdeL(),
            'tipo_les' => $les->getTipo_les(),
            'id_ocorrencia' => $id_oco,
            'id_crime' => 2,
        );

 
        if ($this->find($id_oco)) {
            $this->tableGateway->update($data, array('id_ocorrencia' => $id_oco));
        } else {
            throw new Exception("Dados da lesão da ocorrência de id = {$id_oco} não encontrada");
        }
    }
    
        public function find($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id_ocorrencia' => $id));
        $row = $rowset->current();
        if (!$row)
            throw new Exception("Dados da lesão da ocorrência de id = {$id} não encontrada");

        return $row;
    }
    
         public function isLesao($id) {
        $id = (int) $id;
        $select = new Select;
        $select->from('lesao');
        $select->columns(array('*'));
        $select->where(array('lesao.id_ocorrencia' => $id));

        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();
       
        if (!$row)
            return false;
        return true;
    }
    
  

}