<?php

namespace Application\Model;

use Zend\Db\Adapter\Adapter,
    Zend\Db\ResultSet\ResultSet,
    Zend\Db\TableGateway\TableGateway,
    Zend\Db\Sql\Select,
    Zend\Paginator\Adapter\DbSelect,
    Zend\Paginator\Paginator;


class BairroTable {

    protected $tableGateway;
    protected $adapter;
    protected $resultSetPrototype;
 
    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Bairro());
        $this->tableGateway = new TableGateway('bairro', $this->adapter, null, $this->resultSetPrototype);
    }
    
public function search($id_muni)
{
    // preparar objeto SQL
    $adapter = $this->tableGateway->getAdapter();
    $sql     = new \Zend\Db\Sql\Sql($adapter);
 
    // montagem do select com where, like e limit para tabela contatos
    $select = (new Select('area'))->limit(8);
    $select
            ->columns(array('id_area', 'descricao'))
            ->where(array('area.id_muni' => $id_muni));
    ;
 
    // executar select
    $statement = $sql->getSqlStringForSqlObject($select);
    $results   = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
 
    return $results;
}
 
 public function fetchPaginator($pagina = 1, $itensPagina = 10, $ordem = 'bairro ASC', $like = null, $itensPaginacao = 5)          
{      
        $select = new Select;
        $select->from(array('b' => 'bairro'));
        $select->columns(array('id_bai', 'bairro'));
        $select->join(array('a' => 'area'), "b.id_area = a.id_area", array('id_area', 'descricao'));
        $select->join(array('m' => 'municipio'), "a.id_muni = m.id_muni", array('id_muni', 'municipio'));
        $select->order($ordem);

       
        if (isset($like)) {
        $select
                ->where
                ->like('id_bai', "%{$like}%")
                ->or
                ->like('bairro', "%{$like}%")
                ->or
                ->like('descricao', "%{$like}%")
                 ->or
                ->like('municipio', "%{$like}%")
        ;
    }
    
    // criar um objeto com a estrutura desejada para armazenar valores
   // $resultSet = new HydratingResultSet(new Reflection(), new Viatura());
    
     $resultSetPrototype = new ResultSet();
     $resultSetPrototype->setArrayObjectPrototype( new Bairro());
    
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
