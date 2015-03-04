<?php

namespace Application\Model;

use //Zend\Db\Adapter\Adapter,
    //Zend\Db\ResultSet\ResultSet,
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
 public function fetchAll()
    {
        
     return $this->tableGateway->select();
    }
public function fetchPaginator($pagina = 1, $itensPagina = 10, $ordem = 'prefixo ASC', $like = null, $itensPaginacao = 5) 
{
    // preparar um select para tabela contato com uma ordem
    $select = (new Select('vtr'))->order($ordem);
    
    if (isset($like)) {
        $select
                ->where
                ->like('id_vtr', "%{$like}%")
                ->or
                ->like('prefixo', "%{$like}%")
                ->or
                ->like('id_area', "%{$like}%")
        ;
    }
    
    // criar um objeto com a estrutura desejada para armazenar valores
    $resultSet = new HydratingResultSet(new Reflection(), new Viatura());
    
    // criar um objeto adapter paginator
    $paginatorAdapter = new DbSelect(
        // nosso objeto select
        $select,
        // nosso adapter da tabela
        $this->tableGateway->getAdapter(),
        // nosso objeto base para ser populado
        $resultSet
    );
    
    // resultado da paginação
    return (new Paginator($paginatorAdapter))
            // pagina a ser buscada
            ->setCurrentPageNumber((int) $pagina)
            // quantidade de itens na página
            ->setItemCountPerPage((int) $itensPagina)
            ->setPageRange((int) $itensPaginacao);
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

    /**
     * Localizar linha especifico pelo id da tabela municipio
     * 
     * @param type $id
     * @return \Model\Policial
     * @throws \Exception
     */
    public function find($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id_vtr' => $id));
        $row = $rowset->current();
        if (!$row)
            throw new \Exception("Não foi encontrado vtr de id = {$id}");

        return $row;
    }
    
    public function salvarViatura(Viatura $vtr)
    {
        $data = array(
            'numeral'       => $policial->getNumeral(),
            'nome'          => $policial->getNome(),
            'nome_guerra'   => $policial->getNome_guerra(),
            'matricula'     => $policial->getMatricula(),
            'id_graduacao'  => $policial->getId_graduacao(),
            'data_nasc'     => $policial->getData_nasc(),
            'sexo'          => $policial->getSexo()
        );

        $id = (int)$policial->getId_policial();
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->find($id)) {
                $this->tableGateway->update($data, array('id_vtr' => $id));
            } else {
                throw new \Exception('Vtr não encontrado');
            }
        }
        
        
    }
    
    public function deleteViatura($id)
    {
        try {
            return $this->tableGateway->delete(array('id_vtr' => $id));
        
        }catch (\Exception $e) {
            return false;
        }
        
    }

}
