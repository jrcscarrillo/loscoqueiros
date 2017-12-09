
<?= $this->elements->getModelosAdicional() ?>
<div class="container">
    <div class="row">

        <div class="col-md-8">
            
    <?= $this->getContent() ?>
    <div class="body bg-blue">
    
            
        <?= $this->tag->form(['contribuyente/save', 'id' => 'contribuyenteForm', 'class' => 'sky-form']) ?>
    
            <header><?php echo $this->view->descriptivo['cabecera']; ?></header>
            
        <fieldset>
            <section>
                <div class="row">
                    <div class="col col-8">
                        <label class="hidden">
                            <i class="icon-append fa fa-user"></i>
                            <?= $form->render('Id') ?>
                        </label>
                    </div>
                </div>
            </section>
            <section>
                <div class="row">
                    <label class="label col col-4">RUC</label>
                    <div class="col col-8">
                        <label class="input">
                            <i class="icon-append fa fa-user"></i>
                            <?= $form->render('Ruc', ['class' => 'form-control']) ?>
                        </label>
                    </div>
                </div>
            </section>
            <section>
                <div class="row">
                    <label class="label col col-4">Razon Social</label>
                    <div class="col col-8">
                        <label class="input">
                            <i class="icon-append fa fa-user"></i>
                            <?= $form->render('Razon', ['class' => 'form-control']) ?>
                        </label>
                    </div>
                </div>
            </section>
            <section>
                <div class="row">
                    <label class="label col col-4">Nombre Comercial</label>
                    <div class="col col-8">
                        <label class="input">
                            <i class="icon-append fa fa-user"></i>
                            <?= $form->render('NombreComercial', ['class' => 'form-control']) ?>
                        </label>
                    </div>
                </div>
            </section>
        </fieldset>
        <fieldset>
            <section>
                <div class="row">
                    <label class="label col col-4">Direccion Matriz</label>
                    <div class="col col-8">
                        <label class="input">
                            <i class="icon-append fa fa-user"></i>
                            <?= $form->render('DirMatriz', ['class' => 'form-control']) ?>
                        </label>
                    </div>
                </div>
            </section>
            <section>
                <div class="row">
                    <label class="label col col-4">Direccion Establecimiento</label>
                    <div class="col col-8">
                        <label class="input">
                            <i class="icon-append fa fa-user"></i>
                            <?= $form->render('DirEmisor', ['class' => 'form-control']) ?>
                        </label>
                    </div>
                </div>
            </section>
        </fieldset>
        <fieldset>
            <section>
                <div class="row">
                    <label class="label col col-2">Establecimiento</label>
                    <div class="col col-4">
                        <label class="input">
                            <i class="icon-append fa fa-user"></i>
                            <?= $form->render('CodEmisor', ['class' => 'form-control']) ?>
                        </label>
                    </div>
                    <label class="label col col-2">Punto Emision</label>
                    <div class="col col-4">
                        <label class="input">
                            <i class="icon-append fa fa-user"></i>
                            <?= $form->render('Punto', ['class' => 'form-control']) ?>
                        </label>
                    </div>
                </div>
            </section>
        </fieldset>
        <fieldset>
            <section>
                <div class="row">
                    <label class="label col col-2">Resolucion</label>
                    <div class="col col-4">
                        <label class="input">
                            <i class="icon-append fa fa-user"></i>
                            <?= $form->render('Resolucion', ['class' => 'form-control']) ?>
                        </label>
                    </div>
                    <label class="label col col-2">Lleva Contabilidad</label>
                    <div class="col col-4">
                        <label class="input">
                            <i class="icon-append fa fa-user"></i>
                            <?= $form->render('LlevaContabilidad', ['class' => 'form-control']) ?>
                        </label>
                    </div>
                </div>
            </section>
        </fieldset>
        <footer>
            <?= $this->tag->submitButton(['Submit', 'class' => 'btn btn-primary']) ?>
            <p class="help-block">Usted esta genenrando un nuevo contribuyente.</p>
        </footer>
    </form>
</div>

        </div>

        <div class="col-md-4">

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
