<?php foreach ($documents as $document): ?>
    <tr>
        <td><?= $document->organization_name ?></td>
        <td><?= $document['created_at'] ?></td>
        <td><?= $document['delivery_date'] == null ? $document['issued_at'] : $document['delivery_date'] ?></td>
        <td><?= $document['total_price'] ?></td>
        
        <td>
            <?= $document->getBankRow() ? 'paired' : '' ?>
            <a href="/document/<?= $document['id'] ?>">View</a>
        </td>
    </tr>
<?php endforeach; ?>