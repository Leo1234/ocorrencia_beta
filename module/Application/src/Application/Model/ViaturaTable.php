<?php

namespace Application\Model;

use //Zend\Db\Adapter\Adapter,
    Zend\Db\ResultSet\ResultSet,
 Zend\Db\ResultSet\HydratingResultSet,
                             Zend\Db\TableGateway\TableGateway,
                             Zend\Db\Sql\Select,
                             Zend\Stdlib\Hydrator\Reflection,
                             Zend\Paginator\Adapter\DbSelect,
                             Zend\Paginator\Paginator;

class ViaturaTable {

    protected $tableGateway;
    protected $adapter;
    protected $resultSetPrototype;

public function __construct(TableGateway $tableGateway)
{
    $this->tableGateway = $tableGateway;
}

private function getViaturaTable()
{
    return $this->getServiceLocator()->get('ModelViatura');
}


 public function fetchAll(){
     
      //return $this->tableGateway->select(); era essa instrução
     
        $select = new Select;
        $select->from(array('v' => 'vtr'));
        $select->columns(array('id_vtr', 'prefixo'));
        $select->join(array('a' => 'area'), "v.id_area = a.id_area", array('id_area', 'descricao'));
        $select->join(array('m' => 'municipio'), "a.id_muni = m.id_muni", array('id_muni', 'municipio'));
        $select->order($ordem);
        $resultSet = $this->tableGateway->selectWith($select); 
        return $resultSet;
        
        
}

    public function fetchPaginator($pagina = 1, $itensPagina = 10, $ordem = 'prefixo ASC', $like = null, $itensPaginacao = 5)          
{      
        $select = new Select;
        $select->from(array('v' => 'vtr'));
        $select->columns(array('id_vtr', 'prefixo'));
        $select->join(array('a' => 'area'), "v.id_area = a.id_area", array('id_area', 'descricao'));
        $select->join(array('m' => 'municipio'), "a.id_muni = m.id_muni", array('id_muni', 'municipio'));
        $select->order($ordem);

        //echo $select->getSqlString();
        // preparar um select para tabela vtr com uma ordem
        //$select = (new Select('vtr'))->order($ordem);

        if (isset($like)) {
        $select
                ->where
                ->like('id_vtr', "%{$like}%")
                ->or
                ->like('prefixo', "%{$like}%")
                ->or
                ->like('descricao', "%{$like}%")
        ;
    }
    
    // criar um objeto com a estrutura desejada para armazenar valores
   // $resultSet = new HydratingResultSet(new Reflection(), new Viatura());
    
     $resultSetPrototype = new ResultSet();
     $resultSetPrototype->setArrayObjectPrototype( new Viatura());
    
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



   public function save(Viatura $viatura) {
        $data = [
            'prefixo' => $viatura->getPrefixo(),
            'id_area' => $viatura->getArea()->getId_area(),
        ];

        return $this->tableGateway->insert($data);
    }
    
    public function update(Viatura $viatura) {
        $data = [
            'prefixo' => $viatura->getPrefixo(),
            'id_area' => $viatura->getArea()->getId_area(),
        ];

        $id = (int) $viatura->getId_vtr();
        //$id = 1;
        
        if ($this->find($id)) {
            $this->tableGateway->update($data, array('id_vtr' => $id));
        } else {
            throw new Exception("Viatura #{$id} inexistente");
        }
    }
    public function delete($id) {
        try {
            $this->tableGateway->delete(array('id_vtr' => (int) $id));
        } catch (\Exception $e) {
            return false;
        }
    }
       public function find($id) {
        $id = (int) $id;

        $select = new Select;
        $select->from('vtr');
        $select->columns(array('*'));
        //$select->from(array('v' => 'vtr'));
        $select->columns(array('id_vtr', 'prefixo'));
        $select->join(array('a' => 'area'), "vtr.id_area = a.id_area", array('id_area', 'descricao'));
        $select->join(array('m' => 'municipio'), "a.id_muni = m.id_muni", array('id_muni', 'municipio'));
        $select->where(array('vtr.id_vtr' => $id));
        

        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();

        if (!$row)
            throw new \Exception("Não foi encontrado vtr de id = {$id}");
        return $row;
    }

    /**
     * Recuperar todos os elementos da tabela policial
     * 
     * @return ResultSet
     
    public function fetchAll($currentPage = 0, $countPerPage = 0) {
        //return $this->tableGateway->select();
        
        $select = new \Zend\Db\Sql\Select;
        $select->from(array('v' => 'vtr'));
        $select->columns(array('id_vtr','prefixo'));
        $select->join(array('a'=>'area'), "v.id_area = a.id_area",array('id_area','descricao'));
        $select->join(array('m'=>'municipio'), "a.id_muni = m.id_muni",array('id_muni','municipio'));
        //echo $select->getSqlString();

        // create a new pagination adapter object
        $paginatorAdapter = new DbSelect(
                // our configured select object
                $select,
                // the adapter to run it against
                $this->tableGateway->getAdapter(),
                // the result set to hydrate
                $this->resultSetPrototype
        );
        $paginator = new Paginator($paginatorAdapter);
        $paginator->setItemCountPerPage($countPerPage);
        $paginator->setCurrentPageNumber($currentPage);
        
        
        return $paginator;
    }

 */
     
    


}
