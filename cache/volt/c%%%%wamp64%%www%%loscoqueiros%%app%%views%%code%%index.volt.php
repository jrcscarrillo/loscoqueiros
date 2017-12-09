
<?= $this->elements->getModelosAdicional() ?>
<div class="container">
    <div class="row">

        <div class="col-md-6">
            
    <?= $this->getContent() ?>
    <div align="right">
        <?= $this->tag->linkTo(['code/new', 'Add a code to our database', 'class' => 'btn btn-primary']) ?>
    </div>
    <div class="body bg-blue">
    
            
        <?= $this->tag->form(['code/search', 'class' => 'sky-form']) ?>
    
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
            <?= $this->tag->submitButton(['Search', 'class' => 'btn btn-primary']) ?>
            <p class="help-block">By searching, you can check all or part of database developers.</p>
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
