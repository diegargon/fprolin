<?php
//pr_dbg($tdata)
?>

<div class="network_container">
    <!-- InterfacesTable -->
    <div class="divTable" style="font-size:12px;float:left;">
        <div id="interfaces" class="divTableBody">            
            <div class="divTableRow">
                <div class="divTableHeading"><?= $lng['L_TYPE'] ?></div>
                <div class="divTableHeading"><?= $lng['L_STATUS'] ?></div>
                <div class="divTableHeading"><?= $lng['L_NAME'] ?></div>                
                <div class="divTableHeading"><?= $lng['L_MEMBERS'] ?></div>
                <div class="divTableHeading"><?= $lng['L_IP'] . '/' . $lng['L_NETMASK'] ?></div>

            </div>

            <?php

            foreach ($tdata['ifaces'] ?? [] as $iface) {
            ?>                
                <a class="row_link" href="?page=interface&iface=<?= $iface['iface'] ?>">
                    <div class="divTableRow" style="width:25px;max-width:25px;">
                        <div class="divTableCell">
                        <?= $iface['devtype'] ?>
                        </div>
                        <div class="divTableCell">
                            <div class="ethernet_container">
                                <div class="ethernet_status_container">
                                    <?php
                                    if (!empty($iface['up'])) {
                                    ?>
                                        <img class="ethernet_status" src="<?= $tdata['img_path'] ?>/green.png" />
                                        <?php
                                        if (!empty($iface['speed']) && $iface['speed'] === 1000) {
                                        ?>
                                            <img class="ethernet_status" src="<?= $tdata['img_path'] ?>/yellow.png" />
                                        <?php
                                        } else {
                                        ?>
                                            <img class="ethernet_status" src="<?= $tdata['img_path'] ?>/black.png" />
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <img class="ethernet_status" src="<?= $tdata['img_path'] ?>/black.png" />
                                        <img class="ethernet_status" src="<?= $tdata['img_path'] ?>/black.png" />
                                    <?php } ?>
                                </div>
                                <div class="ethernet_img_container">
                                    <img class="ethernet_img <?= !empty($iface['up']) ? 'eth_bg_on' : 'eth_bg_off' ?>" src="<?= $tdata['img_path'] ?>/rj45.png" />
                                </div>
                            </div>
                        </div>
                        <div class="divTableCell"><?= $iface['iface'] ?></div>
                        <div class="divTableCell">
                        </div>
                        <div class="divTableCell">
                            <?php
                            if (valid_array($iface['address'])) {
                                foreach ($iface['address'] as $address) {
                                    echo '<p>' . $address['family_h'] . ': ' . $address['address'];
                                    if (!empty($address['netmask'])) {
                                        echo '/' . $address['netmask'];
                                    }
                                    echo '</p>';
                                }
                            }
                            ?>

                        </div>

                    </div>
                </a>
            <?php } ?>
        </div>
        <!-- /InterfacesTable -->

    </div>
</div>