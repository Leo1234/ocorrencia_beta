<?php

namespace Application\Model;

use Zend\Db\Adapter\Adapter,
    Zend\Db\ResultSet\ResultSet,
    Zend\Db\TableGateway\TableGateway,
    Zend\Db\Sql\Select,
    Zend\Paginator\Adapter\DbSelect,
    Zend\Paginator\Paginator;
use \DateTime;

class AcusadoTable {

    protected $tableGateway;
    protected $adapter;
    protected $resultSetPrototype;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Acusado());
        $this->tableGateway = new TableGateway('acusado', $this->adapter, null, $this->resultSetPrototype);
    }

    public function fetchAll() {
        $dbAdapter = $this->adapter;
        $sql = 'SELECT id_acusado,nome  FROM acusado ORDER BY id_acusado ASC';
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();

        $selectData = array();

        foreach ($result as $res) {
            $selectData[$res['id_acusado']] = $res['nome'];
        }
        return $selectData;
    }

    public function fetchPaginator($pagina = 1, $itensPagina = 10, $ordem = 'nome ASC', $like = null, $itensPaginacao = 5) {
        $select = new Select;
        $select->from('acusado');
        $select->columns(array('*'));
        $select->join(array('e' => 'endereco'), "acusado.id_end = e.id_end", array('rua', 'numero', 'lat', 'lng'));
        $select->join(array('b' => 'bairro'), "e.id_bai = b.id_bai", array('id_bai', 'bairro'));
        $select->join(array('m' => 'municipio'), "b.id_muni = m.id_muni", array('id_muni', 'municipio'));
        $select->order($ordem);


        if (isset($like)) {
            $select
                    ->where
                    ->like('id_acusado', "%{$like}%")
                    ->or
                    ->like('nome', "%{$like}%")
                    ->or
                    ->like('telefone', "%{$like}%")
                    ->or
                    ->like('data_nasc', "%{$like}%")
                    ->or
                    ->like('sexo', "%{$like}%")
                    ->or
                    ->like('genitora', "%{$like}%")
                    ->or
                    ->like('caracteristicas', "%{$like}%")
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
        $resultSetPrototype->setArrayObjectPrototype(new Acusado());

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

    public function save(Acusado $acusado) {

        $data = [
            'nome' => $acusado->getNome(),
            'telefone' => $acusado->getTelefone(),
            'data_nasc' => $this->toDateYMD($acusado->getData_nasc()),
            'sexo' => $acusado->getSexo(),
            'genitora' => $acusado->getGenitora(),
            'caracteristicas' => $acusado->getCaracteristicas(),
            'id_end' => $acusado->getEnd(),
        ];
        return $this->tableGateway->insert($data);
    }

    public function find($id) {
        $id = (int) $id;
        $select = new Select;
        $select->from('acusado');
        $select->columns(array('*'));
        $select->join(array('e' => 'endereco'), "acusado.id_end = e.id_end", array('rua', 'numero'));
        $select->join(array('b' => 'bairro'), "e.id_bai = b.id_bai", array('id_bai', 'bairro', 'id_area'));
        $select->join(array('m' => 'municipio'), "b.id_muni = m.id_muni", array('id_muni', 'municipio'));
        $select->where(array('acusado.id_acusado' => $id));

        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();

        if (!$row)
            throw new \Exception("Não foi encontrado acusado de id = {$id}");
        return $row;
    }

    public function update(Acusado $acusado) {
        $data = [
            'nome' => $acusado->getNome(),
            'telefone' => $acusado->getTelefone(),
            'data_nasc' => $this->toDateYMD($acusado->getData_nasc()),
            'sexo' => $acusado->getSexo(),
            'genitora' => $acusado->getGenitora(),
            'caracteristicas' => $acusado->getCaracteristicas(),
            'id_end' => $acusado->getEnd(),
        ];

        $id = (int) $acusado->getId_acusado();
        $this->tableGateway->update($data, array('id_acusado' => $id));

        /*
          if ($this->find($id)) {
          $this->tableGateway->update($data, array('id_acusado' => $id));
          } else {
          throw new Exception("Acusado #{$id} inexistente");
          } */
    }

    public function acusadosOcorrencia($id_ocorrencia) {

        $select = new \Zend\Db\Sql\Select;
        $select->from('acusado');
        $select->columns(array('*'));
        $select->join(array('oc' => 'ocorrencia_acusado'), "acusado.id_acusado = oc.id_acusado", array());
        $select->where(array('id_ocorrencia' => $id_ocorrencia));
        $select->order(array('nome ASC')); // produces 'name' ASC, 'age' DESC
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
        return $paginator;
    }

    public function findByOcorrecia($id_ocorrencia) {
        $id = (int) $id_ocorrencia;


        $select = new \Zend\Db\Sql\Select;
        $select->from('acusado');
        $select->columns(array('*'));
        $select->join(array('oa' => 'ocorrencia_acusado'), "acusado.id_acusado = oa.id_acusado");
        $select->where(array('oa.id_ocorrencia' => $id));

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
