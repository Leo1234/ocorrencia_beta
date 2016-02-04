<?php

namespace Application\Model;


class Itinerario  {

   public $id_muni;
    public $id_crime;
    public $datai;
    public $dataf;

    public function exchangeArray($data) {
        $this->$id_muni = (!empty($data['id_muni'])) ? $data['id_muni'] : null;
        $this->$id_crime = (!empty($data['id_crime'])) ? $data['id_crime'] : null;
        $this->$datai = (!empty($data['datai'])) ? $data['datai'] : null;
        $this->$dataf = (!empty($data['dataf'])) ? $data['dataf'] : null;
    }
   

    public function getId_muni() {
        return $this->id_muni;
    }

    public function getId_crime() {
        return $this->id_crime;
    }

    public function getDatai() {
        return $this->datai;
    }

    public function getDataf() {
        return $this->dataf;
    }

    public function setId_muni($id_muni) {
        $this->id_muni = $id_muni;
    }

    public function setId_crime($id_crime) {
        $this->id_crime = $id_crime;
    }

    public function setDatai($datai) {
        $this->datai = $datai;
    }

    public function setDataf($dataf) {
        $this->dataf = $dataf;
    }


}
