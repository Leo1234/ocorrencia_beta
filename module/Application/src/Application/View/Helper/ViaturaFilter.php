<?php
 
namespace Application\View\Helper;
 
use Zend\View\Helper\AbstractHelper;
use Application\Model\Viatura;
 
class ViaturaFilter extends AbstractHelper
{
 
    protected $viatura;
 
    public function __invoke(Viatura $viatura)
    {
        $this->viatura = $viatura;
 
        return $this;
    }
 
    public function id_vtr()
    {
        $result = $this->viatura->id_vtr;
 
        return $this->view->escapeHtml($result);
    }
    public function prefixo()
    {
        $result = $this->viatura->prefixo;
 
        return $this->view->escapeHtml($result);
    }
       public function id_area()
    {
        $result = $this->viatura->id_area;
 
        return $this->view->escapeHtml($result);
    }
 
}