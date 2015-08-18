<?php

namespace Application\Model;


class OcorrenciaCrime  {

   public $id_ocorrencia;
   public $id_crime;
 

 
    
    public function exchangeArray($data) {
        $this->$id_ocorrencia = (!empty($data['id_oco'])) ? $data['id_oco'] : null;
        $this->$id_crime = (!empty($data['id_crime'])) ? $data['id_crime'] : null;
    }
   
    public function getId_ocorrencia() {
        return $this->id_ocorrencia;
    }

    public function getId_crime() {
        return $this->id_crime;
    }

    public function setId_ocorrencia($id_ocorrencia) {
        $this->id_ocorrencia = $id_ocorrencia;
    }

    public function setId_crime($id_crime) {
        $this->id_crime = $id_crime;
    }

}
