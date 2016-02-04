<?php

namespace Application\Model;

use Zend\Db\Adapter\Adapter,
    Zend\Db\ResultSet\ResultSet,
    Zend\Db\TableGateway\TableGateway,
    Zend\Db\Sql\Select,
    Zend\Paginator\Adapter\DbSelect,
    Zend\Paginator\Paginator;

class ItinerarioTable {

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
    
       public function crimesOcorrencia($id){
        $dbAdapter = $this->adapter;
        //$sql = 'SELECT id_cri,crime FROM crime ORDER BY id_cri ASC';
        $sql = 'SELECT id_crime FROM ocorrencia As o LEFT JOIN ocorrencia_crime AS oc ON o.id_ocorrencia = oc.id_ocorrencia  LEFT JOIN crime AS c ON oc.id_crime = c.id_cri WHERE oc.id_ocorrencia =' . $id;
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $selectData = array();

        foreach ($result as $res) {
            $selectData[] = $res['id_crime'];
        }
        return $selectData;
    }
    
  

}