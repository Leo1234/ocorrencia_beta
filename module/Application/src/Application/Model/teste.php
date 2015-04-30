<div class="row">

    <div class="col-lg-12">

        <h1>Ocorrencias</h1>
        <ol class="breadcrumb">
            <li class="active"><i class="fa fa-dashboard"></i>Controle das OcorrÃªncias</li>
        </ol>

    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="topo-table">
            <a href="<?php echo $this->url('ocorrencia', array('action' => 'adicionar')) ?>" class="btn btn-success" title="Novo"><span class="glyphicon glyphicon-plus"></span></a>

            <div class="btn-group" title="Quantidades por PÃ¡gina">
                <button type="button" class="btn btn-default">005</button>
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu pull-right" style="min-width: 75px" role="menu">
                    <li class="active"><a href="#">005</a></li>
                    <li><a href="#">010</a></li>
                    <li><a href="#">025</a></li>
                    <li><a href="#">050</a></li>
                    <li><a href="#">100</a></li>
                </ul>
            </div>

            <form class="form-inline pull-right" role="form">
                <div class="form-group">
                    <label class="sr-only" for="localizar">Buscar...</label>
                    <input type="search" class="form-control" id="localizar" placeholder="Buscar...">
                </div>
                <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
            </form>
        </div>

        <br />

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-desktop"></i> OcorrÃªncias</h3>
            </div>

            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped tablesorter">
                        <thead>
                            <tr>
                                <th class="header" style="width: 5%;">ID <i class="fa fa-sort"></i></th>
                                <th class="header">Endereco <i class="fa fa-sort"></i></th>
                                <th class="header" style="width: 10%;">Viatura <i class="fa fa-sort"></i></th>
                                <th class="header" style="width: 20%;">Area <i class="fa fa-sort"></i></th>
                                <th class="header" style="width: 10%;">Data <i class="fa fa-sort"></i></th>
                                <th class="header" style="width: 10%;">HorÃ¡rio <i class="fa fa-sort"></i></th>
                                <th class="header" style="width: 10%;text-align: center">AÃ§Ã£o <i class="fa "></i></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php 
                                foreach ($this->ocorrencias as $i=>$oc): ?>
                                
                                <tr>
                                    <td><?php echo $oc->getId_ocorrencia(); ?></td>
                                    <td><?php echo $oc->getId_end(); ?></td>
                                    <td><?php echo $oc->getVtr()->getPrefixo(); ?></td>
                                    <td><?php echo $oc->getArea()->getDescricao(); ?></td>
                                    <td><?php echo $this->util()->toDateDMY($oc->getData()) ; ?></td>
                                    <td><?php echo $oc->getHorario(); ?></td>
                                    <td>
                                        <a class="btn btn-xs btn-info" title="Visualizar" href="<?php echo $this->url('ocorrencia', array('action' => 'detalhes', 'id' => $oc->getId_ocorrencia())); ?>"><span class="glyphicon glyphicon-new-window"></span></a>
                                        <a class="btn btn-xs btn-warning" title="Editar" href="<?php echo $this->url('ocorrencia', array("action" => "editar", "id" => $oc->getId_ocorrencia())); ?>"><span class="glyphicon glyphicon-edit"></span></a>
                                        <a class="btn btn-xs btn-danger" title="Deletar" href="<?php echo $this->url('ocorrencia', array("action" => "deletar", "id" => $oc->getId_ocorrencia(), 'confirm' => 0)); ?>"><span class="glyphicon glyphicon-floppy-remove"></span></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    
                    <?php echo $this->paginationControl($this->ocorrencias, 'Sliding', 'application/partials/paginator.phtml', array('route' => 'ocorrencia/paginator'));?>
                </div>
            </div>
        </div>
    </div>
</div>

