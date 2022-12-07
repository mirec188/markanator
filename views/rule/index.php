<!-- table of tags -->
<div class="bd-content row" style="margin-bottom:10px">
    <div class="col-12">
        <table id="example" class="display" style="width:100%">
            <thead>
                <tr>
                    <th> name </th>
                    <th> rule type </th>
                    <th> list of all payment infos</th>
                </tr>
            </thead>
            <tbody id="payment-rows">
                <!-- tags -->
                <?php foreach ($tags as $tag): ?>
                    <tr>
                        <td><?= $tag->name ?></td>
                        <td>
                            <select>
                                <option>
                                    equals
                                </option>
                                <option>
                                    equals and sum is (0,50) 
                                </option>
                                <option>
                                    equals and sum (50, 1000000) 
                                </option>
                            </select>
                        </td>
                        <td>
                            <select>
                                <?php $infos = \Yii::$app->db->createCommand("SELECT distinct additional_info 
                                    FROM bank_row WHERE id IN (
                                        SELECT payment_id FROM tag WHERE name='{$tag->name}'    
                                    )
                                    ")->queryAll(); ?>
                                <?php foreach ($infos as $info): ?>
                                    <option>
                                        <?= $info['additional_info'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>