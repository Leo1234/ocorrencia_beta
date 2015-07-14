<?php

namespace Application\Model;

use Zend\Db\Adapter\Adapter,
    Zend\Db\ResultSet\ResultSet,
    Zend\Db\TableGateway\TableGateway,
    Zend\Db\Sql\Select,
    Zend\Paginator\Adapter\DbSelect,
    Zend\Paginator\Paginator;
use Zend\Db\Sql\Sql;

class OcorrenciaTable {

    protected $tableGateway;
    protected $adapter;
    protected $resultSetPrototype;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Ocorrencia());
        $this->tableGateway = new TableGateway('ocorrencia', $this->adapter, null, $this->resultSetPrototype);
    }

    public function fetchPaginator($pagina = 1, $itensPagina = 10, $ordem = 'datai ASC', $like = null, $itensPaginacao = 5) {
        $select = new \Zend\Db\Sql\Select;
       $select->from(array('o' => 'ocorrencia'));
         $select->columns(array('*'));
        $select->join(array('e' => 'endereco'), "o.id_end = e.id_end", array('rua', 'numero'));
        $select->join(array('b' => 'bairro'), "e.id_bai = b.id_bai", array('id_bai', 'bairro'));
        $select->join(array('m' => 'municipio'), "b.id_muni = m.id_muni", array('id_muni', 'municipio'));
        $select->join(array('v' => 'vtr'), "o.id_vtr = v.id_vtr", array('prefixo'));
        $select->order($ordem);

        if (isset($like)) {
            $select
                    ->where
                    ->like('id_ocorrencia', "%{$like}%")
                    ->or
                    ->like('rua', "%{$like}%")
                    ->or
                    ->like('prefixo', "%{$like}%")
                    ->or
                    ->like('ciops', "%{$like}%")
                    ->or
                    ->like('datai', "%{$like}%")
                    ->or
                    ->like('dataf', "%{$like}%")
                    ->or
                    ->like('narracao', "%{$like}%")

            ;
        }

        // criar um objeto com a estrutura desejada para armazenar valores
        // $resultSet = new HydratingResultSet(new Reflection(), new Viatura());

        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Ocorrencia());

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

    public function save(Ocorrencia $oco) {

        $data = [
            'id_ocorrencia' => $oco->getId_oco(),
            'id_end' => $oco->getEnd(),
            'id_vtr' => $oco->getVtr()->getId_vtr(),
            'ciops' => $oco->getCiops(),
            'id_usuario' => $oco->getUsuario(),
            'datai' => $this->toDateYMD($oco->getDatai()),
            'dataf' => $this->toDateYMD($oco->getDataf()),
            'narracao' => $oco->getNarracao(),
        ];
         $this->tableGateway->insert($data);
        return $this->tableGateway->lastInsertValue;
    }

    
    public function find($id) {
      $id = (int) $id;
      $select = new \Zend\Db\Sql\Select;
      $select->from('ocorrencia');
      $select->columns(array('*'));
      $select->join(array('e' => 'endereco'), "ocorrencia.id_end = e.id_endereco", array('rua','numero'));
     
      $select->join(array('v' => 'vtr'), "ocorrencia.id_vtr = v.id_vtr", array('prefixo'));
      $select->where(array('ocorrencia.id_oco' => $id));
      $select->order(array('data ASC')); 
      //echo $select->getSqlString();exit;
      $rowset = $this->tableGateway->selectWith($select);
      $row = $rowset->current();


      if (!$row)
      throw new \Exception("Não foi encontrado a ocorrencia  de id = {$id}");

      return $row;
      }

      public function addPolicialOcorrencia($id_ocorrencia, $id_policial) {
      $sql = new Sql($this->adapter);
      $insert = $sql->insert('ocorrencia_policial');
      $newData = array(
      'id_ocorrencia' => $id_ocorrencia,
      'id_policial' => $id_policial,
      );
      $insert->values($newData);
      $selectString = $sql->getSqlStringForSqlObject($insert);
      $results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
      return $results->count();
      }

      public function addCrimeOcorrencia($id_ocorrencia, $id_crime) {
      $sql = new Sql($this->adapter);
      $insert = $sql->insert('ocorrencia_crime');
      $newData = array(
      'id_ocorrencia' => $id_ocorrencia,
      'id_crime' => $id_crime,
      );
      $insert->values($newData);
      $selectString = $sql->getSqlStringForSqlObject($insert);
      $results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
      return $results->count();
      }

      public function addProcedimentoOcorrencia($id_ocorrencia, $id_pro) {
      $sql = new Sql($this->adapter);
      $insert = $sql->insert('ocorrencia_procedimento');
      $newData = array(
      'id_oco' => $id_ocorrencia,
      'id_pro' => $id_pro,
      );
      $insert->values($newData);
      $selectString = $sql->getSqlStringForSqlObject($insert);
      $results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
      return $results->count();
      }

      public function delPoliciaisOcorrencia($id_ocorrencia) {
      $sql = new Sql($this->adapter);
      $delete = $sql->delete('ocorrencia_policial')->where(array('id_ocorrencia' => $id_ocorrencia));

      $selectString = $sql->getSqlStringForSqlObject($delete);
      $results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
      return $results->count();
      }

      public function deleteOcorrencia($id) {
      try {
      return $this->tableGateway->delete(array('id_ocorrencia' => $id));
      } catch (\Exception $e) {
      return false;
      }
      }

      public function totalVitimasOcorrencia($id_ocorrencia) {
      $sql = new Sql($this->adapter);
      $select = $sql->select('ocorrencia_vitima')->where(array('id_ocorrencia' => $id_ocorrencia));
      $selectString = $sql->getSqlStringForSqlObject($select);
      $results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
      return $results->count();
      }

      public function totalAcusadosOcorrencia($id_ocorrencia) {
      $sql = new Sql($this->adapter);
      $select = $sql->select('ocorrencia_acusado')->where(array('id_ocorrencia' => $id_ocorrencia));
      $selectString = $sql->getSqlStringForSqlObject($select);
      $results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
      return $results->count();
      }

      public function totalCrimesOcorrencia($id_ocorrencia) {
      $sql = new Sql($this->adapter);
      $select = $sql->select('ocorrencia_crime')->where(array('id_ocorrencia' => $id_ocorrencia));
      $selectString = $sql->getSqlStringForSqlObject($select);
      $results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
      return $results->count();
      }
    

    public function toDateYMD($date) {
        if ($date != "") {
            list ($d, $m, $y) = explode('/', $date);
            list ($a1, $h) = explode(' ', $y);
            $dataformatada = "$d-$m-$y";
            if ($dataformatada != "--") {
                return "$a1-$m-$d $h";
            }
        }

        return "";
    }

    public function toDateDMY($date) {

        if ($date != "") {
            list ($y, $m, $d) = explode('-', $date);
            list ($d1, $h) = explode(' ', $d);
            $dataformatada = "$d/$m/$y";
            if ($dataformatada != "//") {
                return "$d1/$m/$y $h";
            }
        }

        return "";
    }

}
