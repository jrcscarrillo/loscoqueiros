<div class="row">
    <nav>
        <ul class="pager">
            <li class="previous"><?= $this->tag->linkTo(['contribuyente/index', 'Regresar']) ?></li>
            <li class="next"><?= $this->tag->linkTo(['contribuyente/new', 'Adicionar ']) ?></li>
        </ul>
    </nav>
</div>

<?= $this->getContent() ?>
<div class="row bg-blue">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Id</th>
            <th>Ruc</th>
            <th>Razon</th>
            <th>NombreComercial</th>
            <th>DirMatriz</th>
            <th>DirEmisor</th>
            <th>CodEmisor</th>
            <th>Punto</th>
            <th>Resolucion</th>
            <th>LlevaContabilidad</th>
            <th>Ambiente</th>
            <th>Emision</th>
            <th>Contingencia</th>

                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php if (isset($page->items)) { ?>
        <?php foreach ($page->items as $contribuyente) { ?>
            <tr>
                <td><?= $contribuyente->Id ?></td>
            <td><?= $contribuyente->Ruc ?></td>
            <td><?= $contribuyente->Razon ?></td>
            <td><?= $contribuyente->NombreComercial ?></td>
            <td><?= $contribuyente->DirMatriz ?></td>
            <td><?= $contribuyente->DirEmisor ?></td>
            <td><?= $contribuyente->CodEmisor ?></td>
            <td><?= $contribuyente->Punto ?></td>
            <td><?= $contribuyente->Resolucion ?></td>
            <td><?= $contribuyente->LlevaContabilidad ?></td>
            <td><?= $contribuyente->Ambiente ?></td>
            <td><?= $contribuyente->Emision ?></td>
            <td><?= $contribuyente->Contingencia ?></td>

                <td><?= $this->tag->linkTo(['contribuyente/edit/' . $contribuyente->Id, '<i class="glyphicon glyphicon-edit"></i>', 'class' => 'btn btn-default']) ?></td>
                <td><?= $this->tag->linkTo(['contribuyente/delete/' . $contribuyente->Id, '<i class="glyphicon glyphicon-remove"></i>', 'class' => 'btn btn-default']) ?></td>
                <td><?= $this->tag->linkTo(['contribuyente/select/' . $contribuyente->Id, '<i class="glyphicon glyphicon-check"></i>', 'class' => 'btn btn-default']) ?></td>
            </tr>
        <?php } ?>
        <?php } ?>
        </tbody>
    </table>
</div>

<div class="row">
    <div class="col-sm-1">
        <p class="pagination" style="line-height: 1.42857;padding: 6px 12px;">
            <?= $page->current . '/' . $page->total_pages ?>
        </p>
    </div>
    <div class="col-sm-11">
        <nav>
            <ul class="pagination">
                <li><?= $this->tag->linkTo(['contribuyente/search', 'First']) ?></li>
                <li><?= $this->tag->linkTo(['contribuyente/search?page=' . $page->before, 'Previous']) ?></li>
                <li><?= $this->tag->linkTo(['contribuyente/search?page=' . $page->next, 'Next']) ?></li>
                <li><?= $this->tag->linkTo(['contribuyente/search?page=' . $page->last, 'Last']) ?></li>
            </ul>
        </nav>
    </div>
</div>
