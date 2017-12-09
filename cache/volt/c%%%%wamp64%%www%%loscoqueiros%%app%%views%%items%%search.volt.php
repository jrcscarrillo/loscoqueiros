<div class="row">
    <nav>
        <ul class="pager">
            <li class="previous"><?= $this->tag->linkTo(['items/index', 'Retroceder']) ?></li>
            <li class="next"><?= $this->tag->linkTo(['items/new', 'Agregar ']) ?></li>
        </ul>
    </nav>
</div>

<div class="page-header">
    <h1>Lista de Helados</h1>
</div>

<?= $this->getContent() ?>

<div class="row">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Numero</th>
                <th>Codigo</th>
                <th>Nombre Corto</th>
                <th>Nombre Largo</th>
                <th>Descripcion de Ventas</th>
                <th>Precio de Venta</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($page->items)) { ?>
                <?php foreach ($page->items as $item) { ?>
                    <tr>
                        <td><?= $item->id ?></td>
                        <td><?= $item->name ?></td>
                        <td><?= $item->fullname ?></td>
                        <td><?= $item->description ?></td>
                        <td><?= $item->sales_desc ?></td>
                        <td><?= $item->sales_price ?></td>
                        <td><?= $this->tag->linkTo(['items/edit/' . $item->id, 'Edit']) ?></td>
                        <td><?= $this->tag->linkTo(['items/delete/' . $item->id, 'Delete']) ?></td>
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
                <li><?= $this->tag->linkTo(['items/search', 'First']) ?></li>
                <li><?= $this->tag->linkTo(['items/search?page=' . $page->before, 'Previous']) ?></li>
                <li><?= $this->tag->linkTo(['items/search?page=' . $page->next, 'Next']) ?></li>
                <li><?= $this->tag->linkTo(['items/search?page=' . $page->last, 'Last']) ?></li>
            </ul>
        </nav>
    </div>
</div>
