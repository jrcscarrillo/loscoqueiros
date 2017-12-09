
<?= $this->elements->getModelosAdicional() ?>
<div class="container">
    <div class="row">

        <div class="col-md-6">
            
    <?= $this->getContent() ?>

    <div class="body bg-cyan">
    
            
        <div class="bg-orange sky-form">
    
            <header><?php echo $this->view->descriptivo['cabecera']; ?></header>
            
        <fieldset>
            <section>
                <div class="row">
                    <label class="col col-4"></label>
                    <div class="col col-8">
                        <p>Aqui le damos la bienvennida</p>
                    </div>
                </div>
            </section>
        </fieldset>
        <footer>
        </footer>
    </form>
</div>
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
