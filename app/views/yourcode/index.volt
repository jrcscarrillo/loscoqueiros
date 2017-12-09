{% extends "layouts/adicional.volt" %}
{% block forma %}
    {{ content() }}

    <div class="body bg-cyan">
    {% endblock %}
    {% block cabecera %}
        <div class="bg-orange sky-form">
    {% endblock %}
    {% block cuerpoforma %}
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
{% endblock %}