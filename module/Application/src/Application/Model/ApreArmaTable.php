<?php

namespace Application\Model;

use Zend\Db\Adapter\Adapter,
    Zend\Db\ResultSet\ResultSet,
    Zend\Db\TableGateway\TableGateway,
    Zend\Db\Sql\Select,
    Zend\Paginator\Adapter\DbSelect,
    Zend\Paginator\Paginator;

class ApreArmaTable {

    protected $tableGateway;
    protected $adapter;
    protected $resultSetPrototype;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new ApreArma());
        $this->tableGateway = new TableGateway('ap_arma', $this->adapter, null, $this->resultSetPrototype);
    }

    public function findArmaOcorrencia($id) {
        $id = (int) $id;
        $select = new Select;
        $select->from('ap_arma');
        $select->columns(array('*'));
        $select->where(array('ap_arma.id_ocorrencia' => $id));

        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();

        if (!$row)
            throw new \Exception("Dados não encontrados de ocorrência de id = {$id}");
        return $row;
    }

    public function addArma(ApreArma $arma, $id_oco) {

        $data = array(
            'qtdeA' => $arma->getQtdeA(),
            'descricaoA' => $arma->getDescricaoA(),
            'id_ocorrencia' => $id_oco,
            'id_crime' => 13,
        );
        $this->tableGateway->insert($data);
        return $this->tableGateway->lastInsertValue;
    }

    public function update(ApreArma $arma, $id_oco) {
        //print_r($ho);
        $data = array(
            'qtdeA' => $arma->getQtdeA(),
            'descricaoA' => $arma->getDescricaoA(),
            'id_ocorrencia' => $id_oco,
            'id_crime' => 13,
        );


        if ($this->find($id_oco)) {
            $this->tableGateway->update($data, array('id_ocorrencia' => $id_oco));
        } else {
            throw new Exception("Dados da ocorrência de id = {$id_oco} não encontrado");
        }
    }

    public function find($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id_ocorrencia' => $id));
        $row = $rowset->current();
        if (!$row)
            throw new Exception("Dados da ocorrência de id = {$id_oco} não encontrado");

        return $row;
    }

    public function isArma($id) {
        $id = (int) $id;
        $select = new Select;
        $select->from('ap_arma');
        $select->columns(array('*'));
        $select->where(array('ap_arma.id_ocorrencia' => $id));

        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();

        if (!$row)
            return false;
        return true;
    }

}
