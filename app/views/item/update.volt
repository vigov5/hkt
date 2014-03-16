<div class="row">
    <table width="100%">
        <tr>
            <td align="left">{{ linkTo(['item', 'Go Back']) }}</td>
        <tr>
    </table>
    {{ content() }}
    <?php $link = "item/update/$id" ?>
    {{ partial('item/_form', ['link': link, 'button': 'Save']) }}
</div>