<?php

namespace Application\Model;

use Zend\Db\Adapter\Adapter,
    Zend\Db\ResultSet\ResultSet,
    Zend\Db\TableGateway\TableGateway,
    Zend\Db\Sql\Select,
    Zend\Paginator\Adapter\DbSelect,
    Zend\Paginator\Paginator;

class ArmaTable {

    protected $tableGateway;
    protected $adapter;
    protected $resultSetPrototype;
 
    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Arma());
        $this->tableGateway = new TableGateway('arma', $adapter, null, $this->resultSetPrototype);
    }


        public function fetchAll() {
        $dbAdapter = $this->adapter;
        $sql = 'SELECT id_arma, arma  FROM arma ORDER BY id_arma ASC';
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();

        $selectData = array();

        foreach ($result as $res) {
            $selectData[$res['id_arma']] = $res['arma'];
        }
        return $selectData;     
    }
    
     public function fetchPaginator($pagina = 1, $itensPagina = 10, $ordem = 'arma ASC', $like = null, $itensPaginacao = 5)          
{      
        $select = new Select;
        $select->from('arma');
        $select->order($ordem);

       
        if (isset($like)) {
        $select
                ->where
                ->like('id_arma', "%{$like}%")
                ->or
                ->like('arma', "%{$like}%")
        ;
    }
    
    // criar um objeto com a estrutura desejada para armazenar valores
   // $resultSet = new HydratingResultSet(new Reflection(), new Viatura());
    
     $resultSetPrototype = new ResultSet();
     $resultSetPrototype->setArrayObjectPrototype( new Arma());
    
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
        $rowset = $this->tableGateway->select(array('id_arma' => $id));
        $row = $rowset->current();
        if (!$row)
            throw new \Exception("Não foi encontrada arma de id = {$id}");

        return $row;
    }
      public function save(Arma $arma) {
        $data = [
            'arma' => $arma->getArma(),
            'id_arma' => $arma->getId_arma(),
        ];

        return $this->tableGateway->insert($data);
    }
           public function update(Arma $arma) {
        $data = [
             'arma' => $arma->getArma(),
            'id_arma' => $arma->getId_arma()
        ];

        $id = (int) $arma->getId_arma();
        //$id = 1;
        
        if ($this->find($id)) {
            $this->tableGateway->update($data, array('id_arma' => $id));
        } else {
            throw new Exception("Arma #{$id} inexistente");
        }
    }

}
