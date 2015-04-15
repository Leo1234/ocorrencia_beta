<?php

namespace Application\Model;

use Zend\Db\Adapter\Adapter,
    Zend\Db\ResultSet\ResultSet,
    Zend\Db\TableGateway\TableGateway,
    Zend\Db\Sql\Select,
    Zend\Paginator\Adapter\DbSelect,
    Zend\Paginator\Paginator;

class DrogaTable {

    protected $tableGateway;
    protected $adapter;
    protected $resultSetPrototype;
 
    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Droga());
        $this->tableGateway = new TableGateway('droga', $adapter, null, $this->resultSetPrototype);
    }


        public function fetchAll() {
        $dbAdapter = $this->adapter;
        $sql = 'SELECT id_droga, droga  FROM droga ORDER BY id_droga ASC';
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();

        $selectData = array();

        foreach ($result as $res) {
            $selectData[$res['id_droga']] = $res['droga'];
        }
        return $selectData;     
    }
    
     public function fetchPaginator($pagina = 1, $itensPagina = 10, $ordem = 'droga ASC', $like = null, $itensPaginacao = 5)          
{      
        $select = new Select;
        $select->from('droga');
        $select->order($ordem);

       
        if (isset($like)) {
        $select
                ->where
                ->like('id_droga', "%{$like}%")
                ->or
                ->like('droga', "%{$like}%")
        ;
    }
    
    // criar um objeto com a estrutura desejada para armazenar valores
   // $resultSet = new HydratingResultSet(new Reflection(), new Viatura());
    
     $resultSetPrototype = new ResultSet();
     $resultSetPrototype->setArrayObjectPrototype( new Droga());
    
    // criar um objeto adapter paginator
    $paginatorAdapter = new DbSelect(
        // nosso objeto select
        $select,
        // nosso adapter da tabela
        $this->tableGateway->getAdapter(),
        // nosso objeto base para ser populado
        //$resultSet
        $resultSetPrototype
    );
   
    
    // resultado da paginação
    return (new Paginator($paginatorAdapter))
            // pagina a ser buscada
            ->setCurrentPageNumber((int) $pagina)
            // quantidade de itens na página
            ->setItemCountPerPage((int) $itensPagina)
            ->setPageRange((int) $itensPaginacao);
}
 
    public function find($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id_droga' => $id));
        $row = $rowset->current();
        if (!$row)
            throw new \Exception("Não foi encontrada droga de id = {$id}");

        return $row;
    }
      public function save(Droga $droga) {
        $data = [
            'droga' => $droga->getDroga(),
            'id_droga' => $droga->getId_droga(),
        ];

        return $this->tableGateway->insert($data);
    }
           public function update(Droga $droga) {
        $data = [
             'droga' => $droga->getDroga(),
            'id_droga' => $droga->getId_droga()
        ];

        $id = (int) $droga->getId_droga();
        //$id = 1;
        
        if ($this->find($id)) {
            $this->tableGateway->update($data, array('id_droga' => $id));
        } else {
            throw new Exception("Droga #{$id} inexistente");
        }
    }

}
