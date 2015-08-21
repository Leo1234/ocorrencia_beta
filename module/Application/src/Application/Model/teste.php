
<?php

class teste {

    public function atualizaDadosCrimesExtra($crimes,$id) {

        $isHomicidio = $this->getHomicidioTable()->isHomicidio($id);


        ///////////////////////crimes, mas sem homicidios//////////////////
        if (!$this->isPostHomicidio($crimes)) {
            if ($isHomicidio) {
                $this->getOcorrenciaTable()->delHomicidioOcorrencia($id);
                $this->getOcorrenciaTable()->delCrimesOcorrencia($id);
                foreach ($crimes as $cri) {
                    $this->getOcorrenciaTable()->addCrimeOcorrencia($id, $cri);
                }
            }

            $this->getOcorrenciaTable()->delCrimesOcorrencia($id);
            foreach ($crimes as $cri) {
                $this->getOcorrenciaTable()->addCrimeOcorrencia($id, $cri);
            }
            ///////////////////////crimes com homicídios já existentes//////////////////
        } else if ($this->isPostHomicidio($crimes) && $isHomicidio) {
            $Modelho = $this->getHomicidioTable()->findHomicidioOcorrencia($id);
            $this->getOcorrenciaTable()->delHomicidioOcorrencia($id);
            $this->getOcorrenciaTable()->delCrimesOcorrencia($id);
            foreach ($crimes as $cri) {
                $this->getOcorrenciaTable()->addCrimeOcorrencia($id, $cri);
            }
            foreach ($crimes as $cri) {
                if ($cri == 1) {
                    $this->getHomicidioTable()->addHomicidio($Modelho, $id);
                    break;
                }
            }
            if ($isHomicidio) {
                $x = $id;
                return $this->redirect()->toRoute('ocorrencia', array('action' => 'editarhomicidio', 'id' => $x));
            }
        } else {///////////////////////crimes com homicídios pela primeira vez//////////////////
            $this->getOcorrenciaTable()->delCrimesOcorrencia($id);
            foreach ($crimes as $cri) {
                $this->getOcorrenciaTable()->addCrimeOcorrencia($id, $cri);
            }
            $x = $id;
            return $this->redirect()->toRoute('ocorrencia', array('action' => 'novohomicidio', 'id' => $x));
        }
    }

}
