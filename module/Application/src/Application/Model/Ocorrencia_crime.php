<?php

namespace Application\Model;

class Ocorrencia_crime {

    public $id_ocorrenciaC;
    public $id_crime;

    function __construct($id_ocorrencia = 0, $id_crime = 0) {
        $this->id_ocorrenciaC = $id_ocorrencia;
        $this->nid_crime = $id_crime;
      
    }
    
    public function getId_ocorrencia() {
        return $this->id_ocorrenciaC;
    }

    public function getId_crime() {
        return $this->id_crime;
    }

    public function setId_ocorrencia($id_ocorrencia) {
        $this->id_ocorrenciaC = $id_ocorrencia;
    }

    public function setId_crime($id_crime) {
        $this->id_crime = $id_crime;
    }


}
