<div class="row">
    <nav>
        <ul class="pager">
            <li class="previous">{{ link_to("aplicaciones/index", "Go Back") }}</li>
            <li class="next">{{ link_to("aplicaciones/new", "Create ") }}</li>
        </ul>
    </nav>
</div>

<div class="page-header">
    <h1>Search result</h1>
</div>

{{ content() }}

<div class="row">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>AppID</th>
            <th>Nombre</th>
            <th>Descripcion</th>
            <th>Url</th>
            <th>Soporte</th>
            <th>UltimoUsuario</th>
            <th>FechaCreacion</th>

                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        {% if page.items is defined %}
        {% for aplicacione in page.items %}
            <tr>
                <td>{{ aplicacione.appID }}</td>
            <td>{{ aplicacione.nombre }}</td>
            <td>{{ aplicacione.descripcion }}</td>
            <td>{{ aplicacione.url }}</td>
            <td>{{ aplicacione.soporte }}</td>
            <td>{{ aplicacione.ultimoUsuario }}</td>
            <td>{{ aplicacione.fechaCreacion }}</td>

                <td>{{ link_to("aplicaciones/edit/"~aplicacione.appID, "Edit") }}</td>
                <td>{{ link_to("aplicaciones/delete/"~aplicacione.appID, "Delete") }}</td>
            </tr>
        {% endfor %}
        {% endif %}
        </tbody>
    </table>
</div>

<div class="row">
    <div class="col-sm-1">
        <p class="pagination" style="line-height: 1.42857;padding: 6px 12px;">
            {{ page.current~"/"~page.total_pages }}
        </p>
    </div>
    <div class="col-sm-11">
        <nav>
            <ul class="pagination">
                <li>{{ link_to("aplicaciones/search", "First") }}</li>
                <li>{{ link_to("aplicaciones/search?page="~page.before, "Previous") }}</li>
                <li>{{ link_to("aplicaciones/search?page="~page.next, "Next") }}</li>
                <li>{{ link_to("aplicaciones/search?page="~page.last, "Last") }}</li>
            </ul>
        </nav>
    </div>
</div>
