<?php

namespace Application\Model;

class Viatura {
      private $id_vtr;
      private $prefixo;
      private$id_area;

    function __construct($id_vtr=0, $prefixo=null, $id_area = 0) {
        $this->id_vtr = $id_vtr;
        $this->prefixo = $prefixo;
        $this->id_area = $id_area;
    }
    
    public function exchangeArray($data) {
        $this->id_vtr    = (!empty($data['id_vtr'])) ? $data['id_vtr'] : null;
        $this->prefixo   = (!empty($data['prefixo'])) ? $data['prefixo'] : null;
        $this->id_area = (!empty($data['id_area'])) ? $data['id_area'] : null;
        //$this->area      = (!empty($data['descricao'])) ? new Area($data['id_vtr'], $data['descricao'], new Municipio($data['id_vtr'],$data['municipio'])) : null;
       
    }
   
    public function getId_vtr() {
        return $this->id_vtr;
    }

    public function getPrefixo() {
        return $this->prefixo;
    }

    public function getArea() {
        return $this->id_area;
    }

    public function setId_vtr($id_vtr) {
        $this->id_vtr = $id_vtr;
    }

    public function setPrefixo($prefixo) {
        $this->prefixo = $prefixo;
    }

    public function setArea($area) {
        $this->area = $area;
    }


}
