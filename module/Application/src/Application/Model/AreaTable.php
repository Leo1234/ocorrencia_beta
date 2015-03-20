<?php

namespace Application\Model;

use Zend\Db\Adapter\Adapter,
    Zend\Db\ResultSet\ResultSet,
    Zend\Db\TableGateway\TableGateway,
    Zend\Db\Sql\Select,
    Zend\Paginator\Adapter\DbSelect,
    Zend\Paginator\Paginator;


class AreaTable {

    protected $tableGateway;
    protected $adapter;
    protected $resultSetPrototype;
 
    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Area());
        $this->tableGateway = new TableGateway('area', $this->adapter, null, $this->resultSetPrototype);
    }

    public function fetchAll() {
        $dbAdapter = $this->adapter;
        $sql = 'SELECT id_area,descricao  FROM area ORDER BY id_area ASC';
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();

        $selectData = array();

        foreach ($result as $res) {
            $selectData[$res['id_area']] = $res['descricao'];
        }
        return $selectData;
        
    
    }
 public function fetchPaginator($pagina = 1, $itensPagina = 10, $ordem = 'descricao ASC', $like = null, $itensPaginacao = 5)          
{      
        $select = new Select;
        $select->from(array('a' => 'area'));
        $select->columns(array('id_area', 'descricao'));
        $select->join(array('m' => 'municipio'), "a.id_muni = m.id_muni", array('id_muni', 'municipio'));
        $select->order($ordem);

       
        if (isset($like)) {
        $select
                ->where
                ->like('id_area', "%{$like}%")
                ->or
                ->like('descricao', "%{$like}%")
                ->or
                ->like('municipio', "%{$like}%")
        ;
    }
    
    // criar um objeto com a estrutura desejada para armazenar valores
   // $resultSet = new HydratingResultSet(new Reflection(), new Viatura());
    
     $resultSetPrototype = new ResultSet();
     $resultSetPrototype->setArrayObjectPrototype( new Area());
    
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


   public function save(Area $area) {
        $data = [
            'descricao' => $area->getDescricao(),
            'id_muni' => $area->getMunicipio()->getId_muni(),
        ];

        return $this->tableGateway->insert($data);
    }
    
          public function find($id) {
        $id = (int) $id;

        $select = new Select;
        $select->from('area');
        $select->columns(array('*'));
        $select->join(array('m' => 'municipio'), "area.id_muni = m.id_muni", array('id_muni', 'municipio'));
        $select->where(array('area.id_area' => $id));
        
        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();

        if (!$row)
            throw new \Exception("Não foi encontrado área de id = {$id}");
        return $row;
    }
    
       public function update(Area $area) {
        $data = [
            'descricao' => $area->getDescricao(),
            'id_muni' => $area->getMunicipio()->getId_muni(),
        ];

        $id = (int) $area->getId_area();
        //$id = 1;
        
        if ($this->find($id)) {
            $this->tableGateway->update($data, array('id_area' => $id));
        } else {
            throw new Exception("Área #{$id} inexistente");
        }
    }
    
}
