<?php
//pr_dbg($tdata)
?>
<script>
    var request;
    var sources = [];
    var show_domain_packets = true;

    /* TIMER */
    var timeOut = null;

    function start() {
        if (timeOut !== null) {
            clearTimeout(timeOut);
            timeOut = null;
        } else {
            timer();
        }
    }

    function toggle_domain_packets() {
        show_domain_packets = !show_domain_packets;
        if (show_domain_packets) {
            document.querySelector('#toggle_domain').innerText = '!UDP';
        } else {
            document.querySelector('#toggle_domain').innerText = 'UDP';
        }
    }

    function reload() {
        refresh_network();
    }

    function timer() {
        timeOut = setTimeout(function() {
            refresh_network();
            //timer();
        }, 5000);
    }
    /* END TIMER */

    $(window).on('load', function() {
        var set_timer = document.getElementById("set_timer");
        set_timer.addEventListener("click", start, false);
        var set_reload = document.getElementById("reload");
        set_reload.addEventListener("click", reload, false);
        refresh_network();
    });

    function build_cells(row) {
        var cell = '';
        if (row['state'] == '') {
            row['state'] = "STATELESS";
        }
        cell += '<div class="divTableRow">';
        //cell += '<div class="divTableCell" style="border-bottom:1px outset grey">' + row['id'] + '</div>';
        cell += '<div class="divTableCell" style="width:5px;max-width:5px;">' + row['stype'] + '</div>';
        cell += '<div class="divTableCell" style="width:25px;max-width:25px;">' + row['state'] + '</div>';
        if (row['shost']) {
            cell += '<div data-tooltip="' + row['saddr'] + '"' + ' class="divTableCell" style="width:40px;max-width:40px;">';
            if (row['shost'].length > 28) {
                domain = row['shost'].split(".");
                domain = domain.slice(domain.length - 2, domain.length);
                domain = domain.join('.');
                //cell += '*.'+  row['shost'].substr(row['shost'].length - 25);
                cell += '*.' + domain;
            } else {
                cell += row['shost'];
            }
            cell += '</div>';
        } else {
            cell += '<div class="divTableCell" style="width:40px;max-width:40px;">' + row['saddr'] + '</div>';
        }
        cell += '<div class="divTableCell" style="width:10px;max-width:10px;">' + row['sport'] + '</div>';
        if (row['dhost']) {
            cell += '<div data-tooltip="' + row['daddr'] + '"' + ' class="divTableCell" style="width:40px;max-width:40px;">';
            if (row['dhost'].length > 28) {
                domain = row['dhost'].split(".");
                domain = domain.slice(domain.length - 2, domain.length);
                domain = domain.join('.');
                //cell += '*.'+  row['dhost'].substr(row['dhost'].length - 25);
                cell += '*.' + domain;
            } else {
                cell += row['dhost'];
            }
            cell += '</div>';
        } else {
            cell += '<div class="divTableCell" style="width:40;max-width:40px;">' + row['daddr'] + '</div>';
        }
        cell += '<div class="divTableCell" style="width:10px;max-width:10px;">' + row['dport'] + '</div>';
        cell += '<div class="divTableCell" style="width:10px;max-width:10px;">' + row['timeout'] + '</div>';
        cell += '<div class="divTableCell" style="width:8px;max-width:8px;">' + row['layer'] + '</div>';
        cell += '</div>';
        return cell;
    }

    function build_table(saddr, cells, title = '') {
        var table = '';
        if (title) {
            table += '<div class="tableTitle">' + title + '</div>';
        } else {
            table += '<div class="tableTitle">' + saddr + '</div>';
        }
        table += '<div id="' + saddr + '" class="divTable">';

        table += '<div id="net_forward_table" class="divTableBody">'
        table += cells;
        table += '</div></div>';

        return table;
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
                var sources_tables = {};

                //console.log(jsonData);
                if (jsonData.result !== "ok") {
                    if (jsonData.result === "fail") {
                        return data;
                    }
                    return false;
                }
                
                for (const element of jsonData.data) {
                    if ($.isArray(element.value)) {
                        for (const row of element.value) {
                            tag = row['saddr'];
                            sources_tables[tag] = [];
                        }
                        sources_tables['*'] = [];

                        //i = 1;
                        for (const row of element.value) {
                            var matches = 0;
                            tag = row['saddr'];
                            if (show_domain_packets === false && row['dport'] == '53') {
                                continue;
                            }

                            var dports_filter = document.getElementById("dport_filter").value;

                            if (dports_filter) {
                                dports_filter = dports_filter.split(",")
                                if (dports_filter instanceof Array) {
                                    found = false;
                                    for (dport of dports_filter) {
                                        if (dport == row['dport']) {
                                            found = true;
                                            break;
                                        }
                                    }
                                    if (found) {
                                        continue;
                                    }
                                } else {
                                    if (dports_filter == row['dport']) {
                                        continue;
                                    }
                                }

                            }

                            for (const row_check of element.value) {
                                if (row_check['saddr'] == tag) {
                                    matches += 1;
                                }
                            }
                            if (matches > 1) {
                                sources_tables[tag][row['id']] = row;
                                //sources_tables[tag][i] = row;
                            } else {
                                sources_tables['*'][row['id']] = row;
                                //sources_tables['*'][i] = row;
                            }
                            //i++;
                        }


                    }

                }

                //console.log(sources_tables);
                var html_table = '';

                for (const [source_address, data_info] of Object.entries(sources_tables)) {
                    var html_row = '';
                    var title = '';
                    if (data_info.length < 1) {
                        continue;
                    }
                    for (const [key, row] of Object.entries(data_info)) {
                        if (row['shost']) {
                            title = row['shost'];
                        }
                        html_row += build_cells(row);
                    }
                    if (source_address == '*') {
                        title = '*';
                    }
                    html_table += build_table(source_address, html_row, title);
                    //sources.push(source_address);
                }
                $("html").scrollTop(scroll);
                $("div[id^='net_forwardbrief']").empty();
                $('#net_forwardbrief').append(html_table);
                //console.log(sources_tables);
                //console.log(sources); 
                if (timeOut !== null) {
                    timer();
                }
                console.log("Reloaded");
            });

    }
</script>

<div class="network_container">
    <button id="reload">&#8635;</button>
    <button id="set_timer">&#9202;</button>
    <button id="toggle_domain" onclick="toggle_domain_packets()">!UDP</button>
    <input id="dport_filter" type="text" placeholder="dport,"></input>
    <div id="net_forwardbrief"></div>
</div>