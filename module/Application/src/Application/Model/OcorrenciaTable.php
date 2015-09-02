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
        // $select->join(array('oc' => 'ocorrencia_crime'), "o.id_ocorrencia = oc.id_ocorrencia", array('id_crime'),'left');
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

    public function update(Ocorrencia $oco) {
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

        $id = (int) $oco->getId_oco();
        $this->tableGateway->update($data, array('id_ocorrencia' => $id));
    }

    public function find($id) {
        $id = (int) $id;
        $select = new Select;
        $select->from('ocorrencia');
        $select->columns(array('*'));
        $select->join(array('e' => 'endereco'), "ocorrencia.id_end = e.id_end", array('id_end','rua', 'numero'), 'left');
        $select->join(array('b' => 'bairro'), "e.id_bai = b.id_bai", array('id_bai', 'bairro'), 'left');
        $select->join(array('m' => 'municipio'), "b.id_muni = m.id_muni", array('id_muni', 'municipio'), 'left');
        $select->join(array('v' => 'vtr'), "ocorrencia.id_vtr = v.id_vtr", array('id_vtr', 'prefixo'), 'left');
        $select->join(array('oc' => 'ocorrencia_crime'), "ocorrencia.id_ocorrencia = oc.id_ocorrencia", array('id_crime'), 'left');
        $select->join(array('op' => 'ocorrencia_policial'), "ocorrencia.id_ocorrencia = op.id_ocorrencia", array('id_policial'), 'left');
        $select->join(array('ocpr' => 'ocorrencia_procedimento'), "ocorrencia.id_ocorrencia = ocpr.id_oco", array('id_pro'), 'left');
        $select->where(array('ocorrencia.id_ocorrencia' => $id));
        $select->order(array('datai ASC'));
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

    public function delCrimesOcorrencia($id_ocorrencia) {   
        $dbAdapter = $this->adapter;
        $sql = 'DELETE FROM ocorrencia_crime WHERE id_ocorrencia='.$id_ocorrencia ;
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
    }
      public function delCrimeHomicidioOcorrencia($id_ocorrencia,$id_crime) {
        
        $dbAdapter = $this->adapter;
        $sql = 'DELETE FROM ocorrencia_crime WHERE id_ocorrencia='.$id_ocorrencia.' && id_crime ='.$id_crime;
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
    }
    
     public function delProcedimentosOcorrencia($id_ocorrencia) {
         $dbAdapter = $this->adapter;
        $sql = 'DELETE FROM ocorrencia_procedimento WHERE id_oco='.$id_ocorrencia;
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
  
    }
       public function delHomicidioOcorrencia($id_ocorrencia) {
         $dbAdapter = $this->adapter;
        $sql = 'DELETE FROM homicidio WHERE id_ocorrencia='.$id_ocorrencia;
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
  
    }
    
        public function delLesaoOcorrencia($id_ocorrencia) {
         $dbAdapter = $this->adapter;
        $sql = 'DELETE FROM lesao WHERE id_ocorrencia='.$id_ocorrencia;
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
  
    }
         public function delArmaOcorrencia($id_ocorrencia) {
         $dbAdapter = $this->adapter;
        $sql = 'DELETE FROM ap_arma WHERE id_ocorrencia='.$id_ocorrencia;
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
  
    }
         public function delVeiculoOcorrencia($id_ocorrencia) {
         $dbAdapter = $this->adapter;
        $sql = 'DELETE FROM ap_veiculo WHERE id_ocorrencia='.$id_ocorrencia;
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
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

    public function crimesOcorrencia($id) {
        $dbAdapter = $this->adapter;
        //$sql = 'SELECT id_cri,crime FROM crime ORDER BY id_cri ASC';
        $sql = 'SELECT id_cri,crime FROM ocorrencia As o LEFT JOIN ocorrencia_crime AS oc ON o.id_ocorrencia = oc.id_ocorrencia  LEFT JOIN crime AS c ON oc.id_crime = c.id_cri WHERE oc.id_ocorrencia =' . $id;
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $selectData = array();

        foreach ($result as $res) {
            $selectData[$res['id_cri']] = $res['crime'];
        }
        return $selectData;
    }

    public function policiaisOcorrencia($id) {
        $dbAdapter = $this->adapter;
        $sql = 'SELECT p.id_policial,nome FROM ocorrencia As o LEFT JOIN ocorrencia_policial AS po ON o.id_ocorrencia = po.id_ocorrencia  LEFT JOIN policial AS p ON po.id_policial = p.id_policial WHERE po.id_ocorrencia =' . $id;
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $selectData = array();

        foreach ($result as $res) {
            $selectData[$res['id_policial']] = $res['nome'];
        }
        return $selectData;
    }

    public function procedimentosOcorrencia($id) {
        $dbAdapter = $this->adapter;
        $sql = 'SELECT op.id_pro,procedimento FROM ocorrencia As o LEFT JOIN ocorrencia_procedimento AS op ON o.id_ocorrencia = op.id_oco  LEFT JOIN procedimento AS p ON op.id_pro = p.id_pro WHERE op.id_oco =' . $id;
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $selectData = array();

        foreach ($result as $res) {
            $selectData[$res['id_pro']] = $res['procedimento'];
        }
        return $selectData;
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
