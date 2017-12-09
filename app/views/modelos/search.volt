{{ content() }}

<ul class="pager">
    <li class="previous pull-left">
        {{ link_to("modelos/index", "&larr; Go Back") }}
    </li>
    <li class="pull-right">
        {{ link_to("modelos/new", "Add a model to our database") }}
    </li>
</ul>

{% for miscodigos in page.items %}
{% if loop.first %}
<table class="table table-bordered table-striped" align="center">
    <thead>
        <tr>
            <th>Model Name</th>
            <th>Action Name</th>
            <th>Model Type</th>
            <th>Model Description</th>
        </tr>
    </thead>
{% endif %}
    <tbody>
        <tr>
            <td>{{ miscodigos.modelName }}</td>
            <td>{{ miscodigos.actionName }}</td>
            <td>{{ miscodigos.modelType }}</td>
            <td>{{ miscodigos.modelDes }}</td>
            <td width="2%">{{ link_to("modelos/edit/" ~ miscodigos.id, '<i class="glyphicon glyphicon-edit"></i>', "class": "btn btn-default") }}</td>
            <td width="2%">{{ link_to("modelos/delete/" ~ miscodigos.id, '<i class="glyphicon glyphicon-remove"></i>', "class": "btn btn-default") }}</td>
        </tr>
    </tbody>
{% if loop.last %}
    <tbody>
        <tr>
            <td colspan="7" align="right">
                <div class="btn-group">
                    {{ link_to("modelos/search", '<i class="icon-fast-backward"></i> First', "class": "btn btn-default") }}
                    {{ link_to("modelos/search?page=" ~ page.before, '<i class="icon-step-backward"></i> Previous', "class": "btn btn-default") }}
                    {{ link_to("modelos/search?page=" ~ page.next, '<i class="icon-step-forward"></i> Next', "class": "btn btn-default") }}
                    {{ link_to("modelos/search?page=" ~ page.last, '<i class="icon-fast-forward"></i> Last', "class": "btn btn-default") }}
                    <span class="help-inline">{{ page.current }}/{{ page.total_pages }}</span>
                </div>
            </td>
        </tr>
    <tbody>
</table>
{% endif %}
{% else %}
    No models were found in our database
{% endfor %}
