<?php

/**
 *  @copyright Copyright @ 2022 - 2023 Diego Garcia (diego/@/envigo.net)
 */

?>

<div class="login_page">
    <div class="profile_box">
        <div class="login_status"><?= !empty($tdata['login_status']) ? $tdata['login_status'] : null; ?></div>
        <form method="POST" action="?page=login">
            <div class="profile_name">
                <input size="18" onfocus="this.value = ''" placeholder="<?= $lng['L_USERNAME'] ?>" class="login_username" type="text" name="username" value="" />
            </div>
            <div class="profile_password">
                <input size="18" onfocus="this.value = ''" placeholder="<?= $lng['L_PASSWORD'] ?>" class="login_password" type="password" name="password" value="" />
            </div>
            <input type="submit" class="login_button" name="submit" value="<?= $tdata['log_in'] ?>" />
        </form>
    </div>
</div>