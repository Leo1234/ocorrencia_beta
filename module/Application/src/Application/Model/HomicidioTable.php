<?php

namespace Application\Model;

use Zend\Db\Adapter\Adapter,
    Zend\Db\ResultSet\ResultSet,
    Zend\Db\TableGateway\TableGateway,
    Zend\Db\Sql\Select,
    Zend\Paginator\Adapter\DbSelect,
    Zend\Paginator\Paginator;

class HomicidioTable {

    protected $tableGateway;
    protected $adapter;
    protected $resultSetPrototype;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Homicidio());
        $this->tableGateway = new TableGateway('homicidio', $this->adapter, null, $this->resultSetPrototype);
    }
    
        public function findHomicidioOcorrencia($id) {
        $id = (int) $id;
        $select = new Select;
        $select->from('homicidio');
        $select->columns(array('*'));
        $select->where(array('homicidio.id_ocorrencia' => $id));

        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();
       
        if (!$row)
            throw new \Exception("Não foi encontrado homicidio de id = {$id}");
        return $row;
    }
    
        public function addHomicidio(Homicidio $ho, $id_oco) {

        $data = array(
            'qtde' => $ho->getQtde(),
            'tipo_homi' => $ho->getTipo_homi(),
            'presidio' => $ho->getPresidio(),
            'id_ocorrencia' => $id_oco,
            'id_crime' => 1,
        );
        $this->tableGateway->insert($data);
        return $this->tableGateway->lastInsertValue;
    }
    
        public function update(Homicidio $ho,$id) {
            //print_r($ho);
        $data = [
            'qtde' => $ho->getQtde(),
            'tipo_homi' => $ho->getTipo_homi(),
            'presidio' => $ho->getPresidio(),
            'id_ocorrencia' => $id,
            'id_crime' => 1,
        ];

 
        if ($this->find($id)) {
            $this->tableGateway->update($data, array('id_ocorrencia' => $id));
        } else {
            throw new Exception("Dados do homicídio da ocorrência de id = {$id} não encontrado");
        }
    }
    
        public function find($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id_ocorrencia' => $id));
        $row = $rowset->current();
        if (!$row)
            throw new \Exception("Dados do homicídio da ocorrência de id = {$id} não encontrado");

        return $row;
    }
    
         public function isHomicidio($id) {
        $id = (int) $id;
        $select = new Select;
        $select->from('homicidio');
        $select->columns(array('*'));
        $select->where(array('homicidio.id_ocorrencia' => $id));

        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();
       
        if (!$row)
            return false;
        return true;
    }
    
  

}