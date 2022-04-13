<?php
//pr_dbg($tdata)
?>
<script>
    $(document).ready(function() {
        refresh_network();
    });

    function refresh_network() {
        page = 'localconn';

        const conn_state = {
            1: "ESTABLIS",
            2: "SYNC_SENT",
            3: "SYNC_RECV",
            4: "FIN_WAIT1",
            5: "FIN_WAIT2",
            6: "FIN_WAIT",
            7: "CLOSE",
            8: "CLOSE_WAIT",
            9: "LAST_ACK",
            10: "LISTEN",
            11: "CLOSING",
            12: "NEW_SYNC_RECV",
            13: "MAX_STATES",
        };

        $.get('refresher.php', {
                page: page
            })
            .done(function(data) {
                //console.log(data);
                var jsonData = JSON.parse(data);
                //console.log(jsonData);
                if (jsonData.result !== "ok") {
                    console.log("No OK");
                    return false;
                }

                $("div[id^='netlocal_row']").empty();
                $("div[id^='netlocal_listen_row']").empty();
                for (const element of jsonData.data) {
                    if ($.isArray(element.value)) {

                        values = element.value;
                        //console.log(values);                        

                        $.each(values, function(key, value) {
                            //console.log(value['id']);

                            var row;
                            row = '<div id="netlocal_row_' + value['id'] + '" class="divTableRow">';
                            for (var tag in value) {
                                if (tag == "id" || tag == "seq") {
                                    continue;
                                }
                                row = row + '<div class="divTableCell" style="border-bottom:1px outset grey"><span id="' + tag + '_' + value['id'] + '">';
                                if (tag == 'state') {
                                    if (value['stype'] == 'udp' && value['state'] == 7) {
                                        row = row + conn_state[10];
                                    } else {
                                        row = row + conn_state[value[tag]];
                                    }
                                } else {
                                    row = row + value[tag];
                                }
                                row = row + '</span></div>';
                            }
                            row = row + '</div>';
                            if (value['state'] == 10) {
                                $('#netlocal_listen_table').append(row);
                            } else if (value['state'] == 1) {
                                $('#netlocal_established_table').append(row);
                            } else if (value['stype'] == 'udp' && value['state'] == 7) {
                                $('#netlocal_listen_table').append(row);
                            } else {
                                $('#netlocal_table').append(row);
                            }
                            //console.log(row);
                        });
                    }


                }
                setTimeout(refresh_network, 5000);
            });
    }
</script>

<div class="network_container">
    <!-- LocalTable -->
    <div class="divTable" style="font-size:12px;float:left;">
        <span><?= $lng['L_OTHER'] ?></span>
        <div id="netlocal_table" class="divTableBody">

            <div class="divTableRow">
                <div class="divTableHeading"><?= $lng['L_TYPE'] ?></div>
                <div class="divTableHeading"><?= $lng['L_STATE'] ?></div>
                <div class="divTableHeading"><?= $lng['L_SOURCE_ADDR'] ?></div>
                <div class="divTableHeading"><?= $lng['L_SOURCE_PORT'] ?></div>
                <div class="divTableHeading"><?= $lng['L_REMOTE_ADDR'] ?></div>
                <div class="divTableHeading"><?= $lng['L_REMOTE_PORT'] ?></div>
                <div class="divTableHeading"><?= $lng['L_UID'] ?></div>
                <div class="divTableHeading"><?= $lng['L_TIMEOUT'] ?></div>
                <div class="divTableHeading"><?= $lng['L_INODE'] ?></div>
                <div class="divTableHeading"><?= $lng['L_NETLAYER'] ?></div>
            </div>
            <div id="netlocal_row_<?= $lconn['id'] ?>" class="divTableRow">
            </div>

        </div>
    </div>
    <!-- /LocalTable -->
    <!-- LocalEstablishedTable -->
    <div class="divTable" style="font-size:12px;float:left;">
        <span><?= $lng['L_ESTABLISHED'] ?></span>
        <div id="netlocal_established_table" class="divTableBody">
            <div class="divTableRow">
                <div class="divTableHeading"><?= $lng['L_TYPE'] ?></div>
                <div class="divTableHeading"><?= $lng['L_STATE'] ?></div>
                <div class="divTableHeading"><?= $lng['L_SOURCE_ADDR'] ?></div>
                <div class="divTableHeading"><?= $lng['L_SOURCE_PORT'] ?></div>
                <div class="divTableHeading"><?= $lng['L_REMOTE_ADDR'] ?></div>
                <div class="divTableHeading"><?= $lng['L_REMOTE_PORT'] ?></div>
                <div class="divTableHeading"><?= $lng['L_UID'] ?></div>
                <div class="divTableHeading"><?= $lng['L_TIMEOUT'] ?></div>
                <div class="divTableHeading"><?= $lng['L_INODE'] ?></div>
                <div class="divTableHeading"><?= $lng['L_NETLAYER'] ?></div>
            </div>
            <div id="netlocal_established_row_<?= $lconn['id'] ?>" class="divTableRow">
            </div>

        </div>
    </div>
    <!-- /LocalEsablishedTable -->
    <!-- LocalListenTable -->
    <div class="divTable" style="font-size:12px;float:left;">
        <span><?= $lng['L_LISTEN'] ?></span>
        <div id="netlocal_listen_table" class="divTableBody">
            <div class="divTableRow">
                <div class="divTableHeading"><?= $lng['L_TYPE'] ?></div>
                <div class="divTableHeading"><?= $lng['L_STATE'] ?></div>
                <div class="divTableHeading"><?= $lng['L_SOURCE_ADDR'] ?></div>
                <div class="divTableHeading"><?= $lng['L_SOURCE_PORT'] ?></div>
                <div class="divTableHeading"><?= $lng['L_REMOTE_ADDR'] ?></div>
                <div class="divTableHeading"><?= $lng['L_REMOTE_PORT'] ?></div>
                <div class="divTableHeading"><?= $lng['L_UID'] ?></div>
                <div class="divTableHeading"><?= $lng['L_TIMEOUT'] ?></div>
                <div class="divTableHeading"><?= $lng['L_INODE'] ?></div>
                <div class="divTableHeading"><?= $lng['L_NETLAYER'] ?></div>
            </div>
            <div id="netlocal_listen_row_<?= $lconn['id'] ?>" class="divTableRow">
            </div>

        </div>
    </div>
    <!-- /LocalListenTable -->
</div>