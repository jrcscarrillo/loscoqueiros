<div class="row">
    <nav>
        <ul class="pager">
            <li class="previous">{{ link_to("aplicaciones", "Go Back") }}</li>
        </ul>
    </nav>
</div>

<div class="page-header">
    <h1>
        Create aplicaciones
    </h1>
</div>

{{ content() }}

{{ form("aplicaciones/create", "method":"post", "autocomplete" : "off", "class" : "form-horizontal") }}

<div class="form-group">
    <label for="fieldNombre" class="col-sm-2 control-label">Nombre</label>
    <div class="col-sm-10">
        {{ text_field("nombre", "size" : 30, "class" : "form-control", "id" : "fieldNombre") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldDescripcion" class="col-sm-2 control-label">Descripcion</label>
    <div class="col-sm-10">
        {{ text_field("descripcion", "size" : 30, "class" : "form-control", "id" : "fieldDescripcion") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldUrl" class="col-sm-2 control-label">Url</label>
    <div class="col-sm-10">
        {{ text_field("url", "size" : 30, "class" : "form-control", "id" : "fieldUrl") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldSoporte" class="col-sm-2 control-label">Soporte</label>
    <div class="col-sm-10">
        {{ text_field("soporte", "size" : 30, "class" : "form-control", "id" : "fieldSoporte") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldUltimousuario" class="col-sm-2 control-label">UltimoUsuario</label>
    <div class="col-sm-10">
        {{ text_field("ultimoUsuario", "size" : 30, "class" : "form-control", "id" : "fieldUltimousuario") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldFechacreacion" class="col-sm-2 control-label">FechaCreacion</label>
    <div class="col-sm-10">
        {{ text_field("fechaCreacion", "type" : "date", "class" : "form-control", "id" : "fieldFechacreacion") }}
    </div>
</div>


<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {{ submit_button('Save', 'class': 'btn btn-default') }}
    </div>
</div>

</form>
