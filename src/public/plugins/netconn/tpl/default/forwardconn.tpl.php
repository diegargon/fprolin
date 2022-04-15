<?php
//pr_dbg($tdata)
?>
<script>
    var request;

    $(window).on('load', function() {
        refresh_network();
    });

    function get_cells(row) {
        var cell = '';
        cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['id'] + '</div>';
        cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['stype'] + '</div>';        
        cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['state'] + '</div>';
        if(row['shost']) {
            cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['shost'] + '</div>';
        } else {
            cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['saddr'] + '</div>';
        }
        cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['sport'] + '</div>';
        if(row['dhost']) {
            cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['dhost'] + '</div>';
        } else {        
            cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['daddr'] + '</div>';
        }
        cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['dport'] + '</div>';        
        cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['timeout'] + '</div>'; 
        cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['layer'] + '</div>';        
        return cell;
    }

    function refresh_network() {
        page = 'forwardconn';

        var scroll = $(window).scrollTop();

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

        var request = $.get('refresher.php', {
                page: page
            })
            .done(function(data) {
                //console.log(data);
                var jsonData = JSON.parse(data);
                //console.log(jsonData);
                if (jsonData.result !== "ok") {
                    if (jsonData.result === "fail") {
                        return data;
                    }
                    return false;
                }

                $("div[id^='net_forward_row']").empty();

                for (const element of jsonData.data) {
                    if ($.isArray(element.value)) {
                        values = element.value;
                        //console.log(values);                        

                        $.each(values, function(key, row) {
                            var html_row;
                            /*
                            if(row['stype'] == 'tcp' && row['state'] != 'ESTABLISHED') {
                                return true;
                            }            
                            if(row['stype'] == 'udp') {
                                return true;
                            } 
                            */               
                            html_row = '<div id="net_forward_row_' + row['id'] + '" class="divTableRow">';
                            html_row += get_cells(row);                            
                            html_row += '</div>';
                            $('#net_forward_table').append(html_row);
                        });
                      
                    }


                }
                $("html").scrollTop(scroll);
                setTimeout(refresh_network, 5000);
            });
    }
</script>

<div class="network_container">
    <!-- ForwardTable -->
    <div class="divTable" style="font-size:12px;float:left;">
        <span><?= $lng['L_OTHER'] ?></span>
        <div id="net_forward_table" class="divTableBody">

            <div class="divTableRow">
                <div class="divTableHeading"><?= $lng['L_ID'] ?></div>
                <div class="divTableHeading"><?= $lng['L_TYPE'] ?></div>
                <div class="divTableHeading"><?= $lng['L_STATE'] ?></div>
                <div class="divTableHeading"><?= $lng['L_SOURCE_ADDR'] ?></div>
                <div class="divTableHeading"><?= $lng['L_SOURCE_PORT'] ?></div>
                <div class="divTableHeading"><?= $lng['L_REMOTE_ADDR'] ?></div>
                <div class="divTableHeading"><?= $lng['L_REMOTE_PORT'] ?></div>
                <div class="divTableHeading"><?= $lng['L_TIMEOUT'] ?></div>
                <div class="divTableHeading"><?= $lng['L_NETLAYER'] ?></div>
            </div>
        </div>
    </div>
    <!-- /ForwardTable -->


</div>