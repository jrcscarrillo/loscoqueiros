a:7:{i:0;s:143:"
<?= $this->elements->getModelosAdicional() ?>
<div class="container">
    <div class="row">

        <div class="col-md-6">
            ";s:5:"forma";a:1:{i:0;a:4:{s:4:"type";i:357;s:5:"value";s:1:" ";s:4:"file";s:59:"C:\wamp64\www\loscoqueiros/app/views/layouts/adicional.volt";s:4:"line";i:7;}}i:1;s:14:"
            ";s:8:"cabecera";a:1:{i:0;a:4:{s:4:"type";i:357;s:5:"value";s:1:" ";s:4:"file";s:59:"C:\wamp64\www\loscoqueiros/app/views/layouts/adicional.volt";s:4:"line";i:8;}}i:2;s:96:"
            <header><?php echo $this->view->descriptivo['cabecera']; ?></header>
            ";s:11:"cuerpoforma";a:1:{i:0;a:4:{s:4:"type";i:357;s:5:"value";s:1:" ";s:4:"file";s:59:"C:\wamp64\www\loscoqueiros/app/views/layouts/adicional.volt";s:4:"line";i:10;}}i:3;s:542:"
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
";}