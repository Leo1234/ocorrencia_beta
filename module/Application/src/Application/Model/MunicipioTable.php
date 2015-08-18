<?php

namespace Application\Model;

use Zend\Db\Adapter\Adapter,
    Zend\Db\ResultSet\ResultSet,
    Zend\Db\TableGateway\TableGateway,
    Zend\Db\Sql\Select,
    Zend\Paginator\Adapter\DbSelect,
    Zend\Paginator\Paginator;

class MunicipioTable {

    protected $tableGateway;
    protected $adapter;
    protected $resultSetPrototype;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Municipio());
        $this->tableGateway = new TableGateway('municipio', $adapter, null, $this->resultSetPrototype);
    }

    public function fetchAll() {
        $dbAdapter = $this->adapter;
        $sql = 'SELECT id_muni, municipio  FROM municipio ORDER BY id_muni ASC';
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();

        $selectData = array();

        foreach ($result as $res) {
            $selectData[$res['id_muni']] = $res['municipio'];
        }
        return $selectData;
    }

    public function fetchPaginator($pagina = 1, $itensPagina = 10, $ordem = 'municipio ASC', $like = null, $itensPaginacao = 5) {
        $select = new Select;
        $select->from('municipio');
        $select->order($ordem);


        if (isset($like)) {
            $select
                    ->where
                    ->like('id_muni', "%{$like}%")
                    ->or
                    ->like('municipio', "%{$like}%")
            ;
        }

        // criar um objeto com a estrutura desejada para armazenar valores
        // $resultSet = new HydratingResultSet(new Reflection(), new Viatura());

        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Municipio());

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
        $rowset = $this->tableGateway->select(array('id_muni' => $id));
        $row = $rowset->current();
        if (!$row)
            throw new \Exception("Não foi encontrado municipio de id = {$id}");

        return $row;
    }

    public function save(Municipio $municipio) {
        $data = [
            'municipio' => $municipio->getMunicipio(),
            'id_muni' => $municipio->getId_muni(),
        ];

        return $this->tableGateway->insert($data);
    }

    public function update(Municipio $municipio) {
        $data = [
            'municipio' => $municipio->getMunicipio(),
            'id_muni' => $municipio->getId_muni()
        ];

        $id = (int) $municipio->getId_muni();
        //$id = 1;

        if ($this->find($id)) {
            $this->tableGateway->update($data, array('id_muni' => $id));
        } else {
            throw new Exception("Município #{$id} inexistente");
        }
    }

}
