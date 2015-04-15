<?php

namespace Application\Model;

use Zend\Db\Adapter\Adapter,
    Zend\Db\ResultSet\ResultSet,
    Zend\Db\TableGateway\TableGateway,
    Zend\Db\Sql\Select,
    Zend\Paginator\Adapter\DbSelect,
    Zend\Paginator\Paginator;


class PolicialTable {

    protected $tableGateway;
    protected $adapter;
    protected $resultSetPrototype;
 
    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Policial());
        $this->tableGateway = new TableGateway('policial', $this->adapter, null, $this->resultSetPrototype);
    }

    public function fetchAll() {
        $dbAdapter = $this->adapter;
        $sql = 'SELECT id_policial,nome  FROM policial ORDER BY id_policial ASC';
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();

        $selectData = array();

        foreach ($result as $res) {
            $selectData[$res['id_policial']] = $res['nome'];
        }
        return $selectData;
        
    
    }
    
 public function fetchPaginator($pagina = 1, $itensPagina = 10, $ordem = 'nome ASC', $like = null, $itensPaginacao = 5)          
{      
        $select = new Select;
        $select->from(array('p' => 'policial'));
        $select->columns(array('id_policial','numeral', 'nome','nome_guerra','matricula','data_nasc','data_inclu','sexo' ));
        $select->join(array('g' => 'graduacao'), "p.id_graduacao = g.id_grad", array('id_grad', 'graduacao'));
        $select->order($ordem);

       
        if (isset($like)) {
        $select
                ->where
                ->like('id_policial', "%{$like}%")
                ->or
                ->like('graduacao', "%{$like}%")
                ->or
                ->like('numeral', "%{$like}%")
                ->or
                ->like('nome', "%{$like}%")
                ->or
                ->like('nome_guerra', "%{$like}%")
                ->or
                ->like('matricula', "%{$like}%")
                ->or
                ->like('data_nasc', "%{$like}%")
                ->or
                ->like('data_inclu', "%{$like}%")
                ->or
                ->like('sexo', "%{$like}%")
        ;
    }
    
    // criar um objeto com a estrutura desejada para armazenar valores
   // $resultSet = new HydratingResultSet(new Reflection(), new Viatura());
    
     $resultSetPrototype = new ResultSet();
     $resultSetPrototype->setArrayObjectPrototype( new Policial());
    
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


   public function save(Policial $policial) {
        $data = [
            'nome' => $policial->getDescricao(),
            'id_muni' => $policial->getMunicipio()->getId_muni(),
        ];

        return $this->tableGateway->insert($data);
    }
    
          public function find($id) {
        $id = (int) $id;

        $select = new Select;
        $select->from('policial');
        $select->columns(array('*'));
        $select->join(array('m' => 'municipio'), "policial.id_muni = m.id_muni", array('id_muni', 'municipio'));
        $select->where(array('policial.id_policial' => $id));
        
        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();

        if (!$row)
            throw new \Exception("Não foi encontrado área de id = {$id}");
        return $row;
    }
    
       public function update(Policial $policial) {
        $data = [
            'nome' => $policial->getDescricao(),
            'id_muni' => $policial->getMunicipio()->getId_muni(),
        ];

        $id = (int) $policial->getId_policial();
        //$id = 1;
        
        if ($this->find($id)) {
            $this->tableGateway->update($data, array('id_policial' => $id));
        } else {
            throw new Exception("Área #{$id} inexistente");
        }
    }
    
}
