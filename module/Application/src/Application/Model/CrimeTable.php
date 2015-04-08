<?php

namespace Application\Model;

use Zend\Db\Adapter\Adapter,
    Zend\Db\ResultSet\ResultSet,
    Zend\Db\TableGateway\TableGateway,
    Zend\Db\Sql\Select,
    Zend\Paginator\Adapter\DbSelect,
    Zend\Paginator\Paginator;

class CrimeTable {

    protected $tableGateway;
    protected $adapter;
    protected $resultSetPrototype;
 
    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Crime());
        $this->tableGateway = new TableGateway('crime', $adapter, null, $this->resultSetPrototype);
    }


   
    
     public function fetchPaginator($pagina = 1, $itensPagina = 10, $ordem = 'crime ASC', $like = null, $itensPaginacao = 5)          
{      
        $select = new Select;
        $select->from('crime');
        $select->order($ordem);

       
        if (isset($like)) {
        $select
                ->where
                ->like('id_cri', "%{$like}%")
                ->or
                ->like('crime', "%{$like}%")
        ;
    }
    
    // criar um objeto com a estrutura desejada para armazenar valores
   // $resultSet = new HydratingResultSet(new Reflection(), new Viatura());
    
     $resultSetPrototype = new ResultSet();
     $resultSetPrototype->setArrayObjectPrototype( new Crime());
    
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
        $rowset = $this->tableGateway->select(array('id_cri' => $id));
        $row = $rowset->current();
        if (!$row)
            throw new \Exception("Não foi encontrado crime de id = {$id}");

        return $row;
    }
      public function save(Crime $crime) {
        $data = [
            'id_cri' => $crime->getId_cri(),
            'crime' => $crime->getCrime(),
        ];

        return $this->tableGateway->insert($data);
    }
           public function update(Crime $crime) {
        $data = [
             'id_cri' => $crime->getId_cri(),
            'crime' => $crime->getCrime(),
        ];

        $id = (int) $crime->getId_cri();
        //$id = 1;
        
        if ($this->find($id)) {
            $this->tableGateway->update($data, array('id_cri' => $id));
        } else {
            throw new Exception("Crime #{$id} inexistente");
        }
    }

}
