{{ form.start(link) }}
{{ form.textField('name') }}
{{ form.numericField('price') }}
{{ form.numericField('type') }}
{{ form.textField('img') }}
{{ form.numericField('public_range') }}
{{ form.textArea('description') }}
{{ form.selectStatic('status', ["A": "Active", "I": "Inactive"]) }}
<?php echo $form->selectStatic(['created_by', Item::find(), 'using' => ['id', 'name']]); ?>
{% if id is defined %}
    {{ form.hiddenField('id', ['value': id]) }}
{% endif %}
{{ form.submitButton(button) }}
{{ form.end() }}
