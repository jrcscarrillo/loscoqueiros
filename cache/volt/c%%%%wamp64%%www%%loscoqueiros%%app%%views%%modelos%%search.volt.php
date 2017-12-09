<?= $this->getContent() ?>

<ul class="pager">
    <li class="previous pull-left">
        <?= $this->tag->linkTo(['modelos/index', '&larr; Go Back']) ?>
    </li>
    <li class="pull-right">
        <?= $this->tag->linkTo(['modelos/new', 'Add a model to our database']) ?>
    </li>
</ul>

<?php $v42602015451iterated = false; ?><?php $v42602015451iterator = $page->items; $v42602015451incr = 0; $v42602015451loop = new stdClass(); $v42602015451loop->self = &$v42602015451loop; $v42602015451loop->length = count($v42602015451iterator); $v42602015451loop->index = 1; $v42602015451loop->index0 = 1; $v42602015451loop->revindex = $v42602015451loop->length; $v42602015451loop->revindex0 = $v42602015451loop->length - 1; ?><?php foreach ($v42602015451iterator as $miscodigos) { ?><?php $v42602015451loop->first = ($v42602015451incr == 0); $v42602015451loop->index = $v42602015451incr + 1; $v42602015451loop->index0 = $v42602015451incr; $v42602015451loop->revindex = $v42602015451loop->length - $v42602015451incr; $v42602015451loop->revindex0 = $v42602015451loop->length - ($v42602015451incr + 1); $v42602015451loop->last = ($v42602015451incr == ($v42602015451loop->length - 1)); ?><?php $v42602015451iterated = true; ?>
<?php if ($v42602015451loop->first) { ?>
<table class="table table-bordered table-striped" align="center">
    <thead>
        <tr>
            <th>Model Name</th>
            <th>Action Name</th>
            <th>Model Type</th>
            <th>Model Description</th>
        </tr>
    </thead>
<?php } ?>
    <tbody>
        <tr>
            <td><?= $miscodigos->modelName ?></td>
            <td><?= $miscodigos->actionName ?></td>
            <td><?= $miscodigos->modelType ?></td>
            <td><?= $miscodigos->modelDes ?></td>
            <td width="2%"><?= $this->tag->linkTo(['modelos/edit/' . $miscodigos->id, '<i class="glyphicon glyphicon-edit"></i>', 'class' => 'btn btn-default']) ?></td>
            <td width="2%"><?= $this->tag->linkTo(['modelos/delete/' . $miscodigos->id, '<i class="glyphicon glyphicon-remove"></i>', 'class' => 'btn btn-default']) ?></td>
        </tr>
    </tbody>
<?php if ($v42602015451loop->last) { ?>
    <tbody>
        <tr>
            <td colspan="7" align="right">
                <div class="btn-group">
                    <?= $this->tag->linkTo(['modelos/search', '<i class="icon-fast-backward"></i> First', 'class' => 'btn btn-default']) ?>
                    <?= $this->tag->linkTo(['modelos/search?page=' . $page->before, '<i class="icon-step-backward"></i> Previous', 'class' => 'btn btn-default']) ?>
                    <?= $this->tag->linkTo(['modelos/search?page=' . $page->next, '<i class="icon-step-forward"></i> Next', 'class' => 'btn btn-default']) ?>
                    <?= $this->tag->linkTo(['modelos/search?page=' . $page->last, '<i class="icon-fast-forward"></i> Last', 'class' => 'btn btn-default']) ?>
                    <span class="help-inline"><?= $page->current ?>/<?= $page->total_pages ?></span>
                </div>
            </td>
        </tr>
    <tbody>
</table>
<?php } ?>
<?php $v42602015451incr++; } if (!$v42602015451iterated) { ?>
    No models were found in our database
<?php } ?>
