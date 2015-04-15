<?php

namespace Application\Model;

use Zend\Db\Adapter\Adapter,
    Zend\Db\ResultSet\ResultSet,
    Zend\Db\TableGateway\TableGateway,
    Zend\Db\Sql\Select,
    Zend\Paginator\Adapter\DbSelect,
    Zend\Paginator\Paginator;

class GraduacaoTable {

    protected $tableGateway;
    protected $adapter;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Graduacao());
        $this->tableGateway = new TableGateway('graduacao', $this->adapter, null, $resultSetPrototype);
    }

    public function fetchPaginator($pagina = 1, $itensPagina = 10, $ordem = 'sigla ASC', $like = null, $itensPaginacao = 5) {
        $select = new Select;
        $select->from('graduacao');
        $select->order($ordem);

        if (isset($like)) {
            $select
                    ->where
                    ->like('id_grad', "%{$like}%")
                    ->or
                    ->like('sigla', "%{$like}%")
                    ->or
                    ->like('graduacao', "%{$like}%")
            ;
        }

        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Graduacao());

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
    
      public function fetchAll() {
        $dbAdapter = $this->adapter;
        $sql = 'SELECT id_grad, graduacao  FROM graduacao ORDER BY id_grad ASC';
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();

        $selectData = array();

        foreach ($result as $res) {
            $selectData[$res['id_grad']] = $res['graduacao'];
        }
        return $selectData;     
    }

    public function find($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id_grad' => $id));
        $row = $rowset->current();
        if (!$row)
            throw new \Exception("Não foi encontrado graduação de id = {$id}");

        return $row;
    }

    public function save(Graduacao $graduacao) {
        $data = [
            'sigla' => $graduacao->getSigla(),
            'graduacao' => $graduacao->getGraduacao(),
        ];

        return $this->tableGateway->insert($data);
    }
           public function update(Graduacao $graduacao) {
        $data = [
              'sigla' => $graduacao->getSigla(),
            'graduacao' => $graduacao->getGraduacao(),
        ];

        $id = (int) $graduacao->getId_grad();
        //$id = 1;
        
        if ($this->find($id)) {
            $this->tableGateway->update($data, array('id_grad' => $id));
        } else {
            throw new Exception("Graduação #{$id} inexistente");
        }
    }

}
