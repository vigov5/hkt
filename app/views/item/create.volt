<div class="row">
    <table width="100%">
        <tr>
            <td align="left">{{ linkTo(['item', 'Go Back']) }}</td>
        <tr>
    </table>
    {{ content() }}
    {{ partial('item/_form', ['link': 'item/create', 'button': 'Create']) }}
</div>