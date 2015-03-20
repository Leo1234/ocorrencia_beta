<?php

namespace Application\Model;

use Zend\Db\Adapter\Adapter,
    Zend\Db\ResultSet\ResultSet,
    Zend\Db\TableGateway\TableGateway;


class MunicipioTable {

    protected $tableGateway;
    protected $adapter;
    protected $resultSetPrototype;
 
    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Municipio());

        $this->tableGateway = new TableGateway('municipio', $adapter, null, $this->resultSetPrototype);
    }


        public function fetchAll() {
        $dbAdapter = $this->adapter;
        $sql = 'SELECT id_muni, municipio  FROM municipio ORDER BY id_muni ASC';
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();

        $selectData = array();

        foreach ($result as $res) {
            $selectData[$res['id_muni']] = $res['municipio'];
        }
        return $selectData;     
    }
    /**
     * Localizar linha especifico pelo id da tabela municipio
     * 
     * @param type $id
     * @return \Model\Municipio
     * @throws \Exception
     */
    public function find($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id_muni' => $id));
        $row = $rowset->current();
        if (!$row)
            throw new \Exception("Não foi encontrado municipio de id = {$id}");

        return $row;
    }

}
