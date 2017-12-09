
<?= $this->elements->getModelosAdicional() ?>
<div class="container">
    <div class="row">

        <div class="col-md-6">
            
    <?= $this->getContent() ?>
    <div class="body bg-blue">
    
            
        <?= $this->tag->form(['contact/send', 'class' => 'sky-form']) ?>
    
            <header><?php echo $this->view->descriptivo['cabecera']; ?></header>
            

    <fieldset>
            <section>
                <div class="row">
                    <label class="label col col-4">Your Name</label>
                    <div class="col col-8">
                        <label class="input">
                            <i class="icon-append fa fa-user"></i>
                            <?= $form->render('name', ['class' => 'form-control']) ?>
                        </label>
                    </div>
                </div>
            </section>        
            <section>
                <div class="row">
                    <label class="label col col-4">Email</label>
                    <div class="col col-8">
                        <label class="input">
                            <i class="icon-append fa fa-user"></i>
                            <?= $form->render('email', ['class' => 'form-control']) ?>
                        </label>
                    </div>
                </div>
            </section>        
            <section>
                <div class="row">
                    <label class="label col col-4">Comments</label>
                    <div class="col col-8">
                        <label class="input">
                            <i class="icon-append fa fa-user"></i>
                            <?= $form->render('comments', ['class' => 'form-control']) ?>
                        </label>
                    </div>
                </div>
            </section>        
    </fieldset>
        <footer>
            <?= $this->tag->submitButton(['Send', 'class' => 'btn btn-primary']) ?>
            <p class="help-block">By sending this request, you are accepting to receive our newsletter.</p>
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
