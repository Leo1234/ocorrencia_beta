<?php

namespace Application\Model;

use Zend\Db\Adapter\Adapter,
    Zend\Db\ResultSet\ResultSet,
    Zend\Db\TableGateway\TableGateway,
    Zend\Db\Sql\Select,
    Zend\Paginator\Adapter\DbSelect,
    Zend\Paginator\Paginator;

class ProcedimentoTable {

    protected $tableGateway;
    protected $adapter;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Procedimento());
        $this->tableGateway = new TableGateway('procedimento', $this->adapter, null, $resultSetPrototype);
    }

    public function fetchPaginator($pagina = 1, $itensPagina = 10, $ordem = 'procedimento ASC', $like = null, $itensPaginacao = 5) {
        $select = new Select;
        $select->from('procedimento');
        $select->order($ordem);

        if (isset($like)) {
            $select
                    ->where
                    ->like('id_pro', "%{$like}%")
                    ->or
                    ->like('procedimento', "%{$like}%")
                    ->or
                    ->like('peso', "%{$like}%")
            ;
        }

        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Procedimento());

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
        $rowset = $this->tableGateway->select(array('id_pro' => $id));
        $row = $rowset->current();
        if (!$row)
            throw new \Exception("Não foi encontrado procedimento de id = {$id}");

        return $row;
    }

    public function save(Procedimento $procedimento) {
        $data = [
            'procedimento' => $procedimento->getProcedimento(),
            'peso' => $procedimento->getPeso(),
        ];

        return $this->tableGateway->insert($data);
    }
           public function update(Procedimento $procedimento) {
        $data = [
              'procedimento' => $procedimento->getProcedimento(),
            'peso' => $procedimento->getPeso(),
        ];

        $id = (int) $procedimento->getId_pro();
        //$id = 1;
        
        if ($this->find($id)) {
            $this->tableGateway->update($data, array('id_pro' => $id));
        } else {
            throw new Exception("Procedimento #{$id} inexistente");
        }
    }

}
