<?php

namespace Application\Model;

use Zend\Db\Adapter\Adapter,
    Zend\Db\ResultSet\ResultSet,
    Zend\Db\TableGateway\TableGateway,
    Zend\Db\Sql\Select,
    Zend\Paginator\Adapter\DbSelect,
    Zend\Paginator\Paginator;
use \DateTime;

class VitimaTable {

    protected $tableGateway;
    protected $adapter;
    protected $resultSetPrototype;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Vitima());
        $this->tableGateway = new TableGateway('vitima', $this->adapter, null, $this->resultSetPrototype);
    }

    public function fetchAll() {
        $dbAdapter = $this->adapter;
        $sql = 'SELECT id_vitima,nome  FROM vitima ORDER BY id_vitima ASC';
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();

        $selectData = array();

        foreach ($result as $res) {
            $selectData[$res['id_vitima']] = $res['nome'];
        }
        return $selectData;
    }

    public function fetchPaginator($pagina = 1, $itensPagina = 10, $ordem = 'nome ASC', $like = null, $itensPaginacao = 5) {
        $select = new Select;
        $select->from(array('v' => 'vitima'));
        $select->columns(array('*'));
        $select->join(array('e' => 'endereco'), "v.id_end = e.id_end", array('rua', 'numero'));
        $select->join(array('b' => 'bairro'), "e.id_bai = b.id_bai", array('id_bai','bairro'));
        $select->join(array('m' => 'municipio'), "b.id_muni = m.id_muni", array('id_muni', 'municipio'));
        $select->order($ordem);


        if (isset($like)) {
            $select
                    ->where
                    ->like('id_vitima', "%{$like}%")
                    ->or
                    ->like('nome', "%{$like}%")
                    ->or
                    ->like('telefone', "%{$like}%")
                    ->or
                    ->like('data_nasc', "%{$like}%")
                    ->or
                    ->like('sexo', "%{$like}%")
                    ->or
                    ->like('rua', "%{$like}%")
                    ->or
                    ->like('numero', "%{$like}%")
                    ->or
                    ->like('bairro', "%{$like}%")
                    ->or
                    ->like('municipio', "%{$like}%")
            ;
        }

        // criar um objeto com a estrutura desejada para armazenar valores
        // $resultSet = new HydratingResultSet(new Reflection(), new Viatura());

        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Vitima());

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

    public function save(Vitima $vitima) {

        $data = [
            'id_grad' => $vitima->getGraduacao()->getId_grad(),
            'numeral' => $vitima->getNumeral(),
            'nome' => $vitima->getNome(),
            'nome_guerra' => $vitima->getNome_guerra(),
            'matricula' => $vitima->getMatricula(),
            'data_nasc' => $this->toDateYMD($vitima->getData_nasc()),
            'data_inclu' => $this->toDateYMD($vitima->getData_inclu()),
            'numeral' => $vitima->getNumeral(),
            'sexo' => $vitima->getSexo(),
        ];
        return $this->tableGateway->insert($data);
    }

    public function find($id) {
        
  
        $id = (int) $id;

        $select = new Select;
        $select->from('vitima');
        $select->columns(array('*'));
        $select->join(array('g' => 'graduacao'), "vitima.id_grad = g.id_grad", array('id_grad', 'graduacao'));
        $select->where(array('vitima.id_vitima' => $id));

        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();

        if (!$row)
            throw new \Exception("Não foi encontrado vitima de id = {$id}");
        return $row;
    }

    public function update(Vitima $vitima) {
        $data = [
            'id_grad' => $vitima->getGraduacao()->getId_grad(),
            'numeral' => $vitima->getNumeral(),
            'nome' => $vitima->getNome(),
            'nome_guerra' => $vitima->getNome_guerra(),
            'matricula' => $vitima->getMatricula(),
            'data_nasc' => $this->toDateYMD($vitima->getData_nasc()),
            'data_inclu' => $this->toDateYMD($vitima->getData_inclu()),
            'numeral' => $vitima->getNumeral(),
            'sexo' => $vitima->getSexo(),
        ];

        $id = (int) $vitima->getId_vitima();
        //$id = 1;

        if ($this->find($id)) {
            $this->tableGateway->update($data, array('id_vitima' => $id));
        } else {
            throw new Exception("Vitima #{$id} inexistente");
        }
    }
    
    
    public function findByOcorrecia($id_ocorrencia) {
        $id = (int) $id_ocorrencia;
        
        
        $select = new \Zend\Db\Sql\Select;
        $select->from('vitima');
        $select->columns(array('*'));
        $select->join(array('op'=>'ocorrencia_vitima'), "vitima.id_vitima = op.id_vitima");
        $select->where(array('op.id_ocorrencia' => $id));
        
        return $this->tableGateway->selectWith($select);
        

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
