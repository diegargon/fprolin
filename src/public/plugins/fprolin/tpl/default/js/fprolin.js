
$(document).ready(function(){
    $(".navbar").click(function () {
        $('.subnav:visible').add($(this).find('.subnav:first')).toggle();
    });

   // refresh();
});

function toggleNav() {
    
    if (document.getElementById("sidenav").style.width === "0px") {
        document.getElementById("sidenav").style.width = "150px";
        document.getElementById("main").style.paddingLeft = "150px";
        document.querySelector('footer').style.paddingLeft = "150px";
    } else {
        document.getElementById("sidenav").style.width = "0px";
        document.getElementById("main").style.paddingLeft = "0px";    
        document.querySelector('footer').style.paddingLeft = "0px";        
    }
}
function exec_cmd(cmd, cmd_args) {
    if (typeof cmd === 'undefined') {
        cmd = false;
    }
    if (typeof cmd_args === 'undefined') {
        cmd_args = false;
    }
    $.get('exec_cmd.php', {order: cmd, order_value: cmd_args})
            .done(function (data) {
                console.log(data);
                var jsonData = JSON.parse(data);
                //console.log(jsonData);
                if (jsonData.login === "fail") {
                    location.href = '';
                }
                if ("other_hosts" in jsonData) {
                    if ($('#other-hosts').length === 0) {
                        position = jsonData.other_hosts.cfg.place;
                        $(position).prepend(jsonData.other_hosts.data);
                    } else {
                        $('#other-hosts').remove();
                        position = jsonData.other_hosts.cfg.place;
                        $(position).prepend(jsonData.other_hosts.data);
                    }
                }

            });
}

function refresh(page) {
    if (typeof page === 'undefined') {
        return false;
    }

    $.get('refresher.php', {page: page})
            .done(function (data) {
                console.log(data);
                var jsonData = JSON.parse(data);
                //console.log(jsonData);
                if (jsonData.result !== "ok") {
                    console.log("No OK");
                    return;
                }
                
                for (const element of jsonData.data) {
                    //console.log(element);
                    //text <div id="something">?</div> 
                    if($("#" + element.id).length != 0 && element.type === 'text') { // element exists
                        $('#'+ element.id).text(element.value);
                    //value <progress id="something" value="?"></progress>                         
                    } else if ($("#" + element.id).length != 0 && element.type === 'value') {
                        $('#'+ element.id).val(element.value);
                    //text values in array    
                    } else if( $.isArray(element.value)) {
                        /* 
                            messy... is a array object,  example:
                            0: Object { id: "lo", net_device: "lo", net_bytes_sent: 341739904, … }
                            the id tag in html for update must be example : net_device_+ id   mean id="net_device_lo"
                        */
                    
                        values = element.value;
                        //console.log(values);                        
                        $.each( values, function( key, value ) {
                            //console.log(value['id']);
                            for (var tag in value) {                                
                                if($("#" + tag + '_' + value['id']).length != 0) {
                                    $('#'+ tag + '_' + value['id']).text(value[tag]);                                    
                                }                                
                            }                            
                            
                          });
                    }

                    
                }
                setTimeout(refresh, 5000, page);
            });
            //setTimeout(refresh, 5000, page);
}
