<?php
//pr_dbg($tdata)
?>
<script>
    $(document).ready(function() {
        refresh_network();
    });

    function get_cells(row) {        
        var cell = '';
        const conn_state = {
            1: "ESTABLISHED",
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

        cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['stype'] + '</div>';        
        cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' +  conn_state[row['state']] + '</div>';
        if(row['lhost']) {
            cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['lhost'] + '</div>';
        } else {
            cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['laddr'] + '</div>';
        }
        cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['lport'] + '</div>';
        if(row['rhost']) {
            cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['rhost'] + '</div>';
        } else {        
            cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['raddr'] + '</div>';
        }
        cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['uid'] + '</div>'; 
        cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['rport'] + '</div>';        
        cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['timeout'] + '</div>'; 
        cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['inode'] + '</div>'; 
        cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['layer'] + '</div>';        
        return cell;
    }
        
    function refresh_network() {
        page = 'localconn';

        var scroll = $(window).scrollTop();


        $.get('refresher.php', {
                page: page
            })
            .done(function(data) {
                //console.log(data);
                var jsonData = JSON.parse(data);
                //console.log(jsonData);
                if (jsonData.result !== "ok") {                    
                    if(jsonData.result === "fail") {
                        return data;
                    } 
                    return false;  
                }

                $("div[id^='netlocal_row']").empty();
                $("div[id^='netlocal_listen_row']").empty();
                $("div[id^='netlocal_established_row']").empty();
                for (const element of jsonData.data) {
                    if ($.isArray(element.value)) {

                        values = element.value;
                        //console.log(values);                        

                        $.each(values, function(key, row) {
                            //console.log(value['id']);

                            var html_row;
                            html_row = '<div id="netlocal_row_' + row['id'] + '" class="divTableRow">';
                            html_row += get_cells(row);
                            html_row += '</div>';

                            if (row['state'] == 10) {
                                $('#netlocal_listen_table').append(html_row);
                            } else if (row['state'] == 1) {
                                $('#netlocal_established_table').append(html_row);
                            } else if (row['stype'] == 'udp' && row['state'] == 7) {
                                $('#netlocal_listen_table').append(html_row);
                            } else {
                                $('#netlocal_table').append(html_row);
                            }
                            //console.log(row);
                        });
                    }


                }
                $("html").scrollTop(scroll);
                setTimeout(refresh_network, 5000);
            });
    }
</script>

<div class="network_container">
    <!-- LocalTable -->
    <div class="divTable" style="font-size:11px;float:left;">
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
    <div class="divTable" style="font-size:11px;float:left;">
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
    <div class="divTable" style="font-size:11px;float:left;">
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