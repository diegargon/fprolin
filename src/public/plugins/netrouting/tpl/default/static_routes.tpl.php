<?php
//pr_dbg($tdata)
?>
<script>

$(window).on('load', function() {
        refresh_routes();
    });

function get_cells(row) {
        var cell = '';
    //From Linux/ ... /routes.h
        const flags = {
            1: 'Route usable',
            2: 'Destination is a gateway ',
            4: 'Host Entry (or net)',
            8: 'Reinstate route afer timeout',
            16: 'Create dyn (by redirect)',
            32: 'Modifyed dyn (by redirect)',
            64: 'Specific Mtu for this route',
            128: 'Per route window clamping',
            256: 'Initial round trip time',
            512: 'Reject Route',
        };
        cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['id'] + '</div>';
        cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['iface'] + '</div>';        
        cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['gateway'] + '</div>';
        if(row['destination_host']) {
            cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['destination_host'] + '</div>';
        } else {        
            cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['destination'] + '</div>';
        }
        if(row['gateway_host']) {
            cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['gateway_host'] + '</div>';
        } else {
            cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['gateway'] + '</div>';
        }
        cell  += '<div data-tooltip="'+ flags[row['flags']] +'" class="divTableCell" style="border-bottom:1px outset grey">' + row['flags'] + '</div>';

        cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['refcnt'] + '</div>';        
        cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['metric'] + '</div>'; 
        cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['mask'] + '</div>';        
        cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['mtu'] + '</div>';
        cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['window'] + '</div>';
        cell  += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['irtt'] + '</div>';        
        return cell;
    }

    function refresh_routes() {
        page = 'static_routes';

        var scroll = $(window).scrollTop();

        var request = $.get('refresher.php', {
                page: page
            })
            .done(function(data) {
                //console.log(data);
                var jsonData = JSON.parse(data);
                console.log(jsonData);
                if (jsonData.result !== "ok") {
                    if (jsonData.result === "fail") {
                        return data;
                    }
                    return false;
                }

                $("div[id^='static_routes']").empty();

                for (const element of jsonData.data) {
                    if ($.isArray(element.value)) {
                        values = element.value;
                        console.log(values);                        
                        var i = 0;
                        $.each(values, function(key, row) {                            
                            var html_row;        
                            html_row = '<div id="static_routes_row_' + i + '" class="divTableRow">';
                            row['id'] = i;
                            html_row += get_cells(row);                            
                            html_row += '</div>';
                            $('#static_routes').append(html_row);
                            i++;
                        });
                      
                    }


                }

                $("html").scrollTop(scroll);
                setTimeout(refresh_routes, 15000);
                console.log("Refreshed");
            });
    }

</script>

<div class="network_container">
    <!-- ForwardTable -->
    <div class="divTable" style="font-size:12px;float:left;">
        <span>*</span>
        <div id="static_routes" class="divTableBody">
<!--
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
-->            
        </div>
    </div>
    <!-- /ForwardTable -->
</div>