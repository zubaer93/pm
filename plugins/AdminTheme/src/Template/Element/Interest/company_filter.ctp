<tbody>

    <?php foreach ($interests as $interesting):?>
        <tr>
            <td>
                <?= $interesting->ipo_company['name']; ?>
            </td>
            <td>
                <?= $interesting->app_user['first_name']; ?>
            </td>
            <td>
                <?= $interesting->app_user['last_name']; ?>
            </td>
            <td>
                <?= $interesting->app_user['username']; ?>
            </td>
            <td>
                <?= $interesting->app_user['email']; ?>
            </td>
            <td>
                <?= $interesting->app_user['active']; ?>
            </td>
            <td>
                <?=(!is_null($interesting->app_user['experince_id']))? \App\Model\Service\Core::$experience[$interesting->app_user['experince_id']]:''; ?>
            </td>
        </tr>
    <?php endforeach ?>

</tbody>