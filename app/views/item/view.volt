<table class="table table-hover">
    <tr>
        <th class="success">{{ item.getAttributeLabel('id') }}</th>
        <td>{{ item.id }}</td>
    </tr>
    <tr>
        <th class="success">{{ item.getAttributeLabel('name') }}</th>
        <td>{{ item.name }}</td>
    </tr>
    <tr>
        <th class="success">{{ item.getAttributeLabel('price') }}</th>
        <td>{{ item.price }}</td>
    </tr>
    <tr>
        <th class="success">{{ item.getAttributeLabel('type') }}</th>
        <td>{{ item.type }}</td>
    </tr>
    <tr>
        <th class="success">{{ item.getAttributeLabel('status') }}</th>
        <td>{{ item.status }}</td>
    </tr>
    <tr>
        <th class="success">{{ item.getAttributeLabel('description') }}</th>
        <td>{{ item.description }}</td>
    </tr>
    <tr>
        <th class="success">{{ item.getAttributeLabel('img') }}</th>
        <td>{{ item.img }}</td>
    </tr>
    <tr>
        <th class="success">{{ item.getAttributeLabel('public_range') }}</th>
        <td>{{ item.public_range }}</td>
    </tr>
    <tr>
        <th class="success">{{ item.getAttributeLabel('created_by') }}</th>
        <td>{{ item.created_by }}</td>
    </tr>
    <tr>
        <th class="success">{{ item.getAttributeLabel('approved_by') }}</th>
        <td>{{ item.approved_by }}</td>
    </tr>
    <tr>
        <th class="success">{{ item.getAttributeLabel('created_at') }}</th>
        <td>{{ item.created_at }}</td>
    </tr>
    <tr>
        <th class="success">{{ item.getAttributeLabel('updated_at') }}</th>
        <td>{{ item.updated_at }}</td>
    </tr>
</table>