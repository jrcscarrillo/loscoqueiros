
<?= $this->elements->getModelosAdicional() ?>
<div class="container">
    <div class="row">

        <div class="col-md-6">
            
    <?= $this->getContent() ?>
    <div align="right">
        <?= $this->tag->linkTo(['items/new', 'Aumente un Helado', 'class' => 'btn btn-primary']) ?>
    </div>
    <div class="body bg-blue">
    
            
        <?= $this->tag->form(['items/search', 'class' => 'sky-form']) ?>
    
            <header><?php echo $this->view->descriptivo['cabecera']; ?></header>
            
        <fieldset>

            <?php foreach ($form as $element) { ?>
                <?php if (is_a($element, 'Phalcon\Forms\Element\Hidden')) { ?>
                    <?= $element ?>
                <?php } else { ?>
                    <section>
                        <div class="row">
                            <?= $element->label(['class' => 'label col col-4']) ?>
                            <div class="col col-8">
                                <label class="input">
                                    <i class="icon-append fa fa-user"></i>
                                    <?= $element ?>
                                </label>
                            </div>
                        </div>
                    </section>
                <?php } ?>
            <?php } ?>

        </fieldset>
        <footer>
            <?= $this->tag->submitButton(['Buscar', 'class' => 'btn btn-primary']) ?>
            <p class="help-block">Buscar, le permite revisar todo o parte de los helados.</p>
        </footer>
    </form>
</div>

        </div>

        <div class="col-md-6">

            <div class="page-header">
                <h2><?php echo $this->view->descriptivo['titulo']; ?></h2>
            </div>

            <p><?php echo $this->view->descriptivo['subtitulo']; ?></p>
            <ul><?php 
                $i = 0;
                foreach ($this->view->descriptivo['lineas'] as $caption) {
                echo '<li>' . $caption . '</li>';
                $i++;
                } ?>
            </ul>

        </div>
    </div>
</div>
