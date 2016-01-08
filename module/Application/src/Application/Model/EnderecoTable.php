<?php

namespace Application\Model;

use Zend\Db\Adapter\Adapter,
    Zend\Db\ResultSet\ResultSet,
    Zend\Db\TableGateway\TableGateway,
    Zend\Db\Sql\Select,
    Zend\Paginator\Adapter\DbSelect,
    Zend\Paginator\Paginator;

class EnderecoTable {

    protected $tableGateway;
    protected $adapter;
    protected $resultSetPrototype;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Endereco());
        $this->tableGateway = new TableGateway('Endereco', $this->adapter, null, $this->resultSetPrototype);
    }

    public function fetchPaginator($pagina = 1, $itensPagina = 10, $ordem = 'id_end ASC', $like = null, $itensPaginacao = 5) {
        $select = new Select;
        $select->from('endereco');
        $select->columns(array('*'));
        $select->join(array('b' => 'bairro'), "endereco.id_bai = b.id_bai", array('bairro'));
        $select->join(array('m' => 'municipio'), "b.id_muni = m.id_muni", array('id_muni', 'municipio'));
        $select->order($ordem);


        if (isset($like)) {
            $select
                    ->where
                    ->like('id_end', "%{$like}%")
                    ->or
                    ->like('rua', "%{$like}%")
                    ->or
                    ->like('bairro', "%{$like}%")
                    ->or
                    ->like('municipio', "%{$like}%")
            ;
        }


        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Endereco());

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

    public function save(Endereco $endereco) {
        $data = [
            'id_end' => $endereco->getId_end(),
            'lat' => $this->latLngEUA($endereco->getLat()),
            'lng' => $this->latLngEUA($endereco->getLng()),
            'rua' => $endereco->getRua(),
            'numero' => $endereco->getNumero(),
            'id_bai' => $endereco->getId_bai()->getId_bai(),
        ];

        $this->tableGateway->insert($data);
        return $this->tableGateway->lastInsertValue;
    }

    public function find($id) {
        $id = (int) $id;
        $select = new Select;
        $select->from('endereco');
        $select->columns(array('*'));
        $select->join(array('b' => 'bairro'), "endereco.id_muni = b.id_muni", array('id_bai', 'bairro'));
        $select->join(array('m' => 'municipio'), "b.id_muni = m.id_muni", array('id_muni', 'municipio'));
        $select->where(array('endereco.id_end' => $id));

        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();

        if (!$row)
            throw new \Exception("Não foi encontrado bairro de id = {$id}");
        return $row;
    }

        
    public function update(Endereco $endereco) {
        $data = [
            'id_end' => $endereco->getId_end(),
            'lat' => $this->latLngEUA($endereco->getLat()),
            'lng' => $this->latLngEUA($endereco->getLng()),
            'rua' => $endereco->getRua(),
            'numero' => $endereco->getNumero(),
            'id_bai' => $endereco->getId_bai()->getId_bai(),
        ];

        $id = (int) $endereco->getId_end();
        $this->tableGateway->update($data, array('id_end' => $id));

        /*
          if ($this->find($id)) {
          $this->tableGateway->update($data, array('id_end' => $id));
          } else {
          throw new Exception("Endereco #{$id} inexistente");
          } */
    }

    public function latLngEUA($ponto) {

        if ($ponto != "") {
            list ($i,$d) = explode(',', $ponto);

            $pontoFormatado = $i.$d;
            if ($pontoFormatado != ".") {
                return $pontoFormatado;
            }
        }

        return "";
    }

}
