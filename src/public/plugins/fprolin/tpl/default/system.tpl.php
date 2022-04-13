<?php
//pr_dbg($tdata)
?>
<script>
    $(document).ready(function() {
        refresh('system');
    });
</script>
<div class="system_container">

    <!-- System Table -->
    <div class="divTable">
        <div class="divTableBody">
            <div class="divTableRow">
                <div class="divTableHeading"><?= $lng['L_SYSTEM'] ?></div>
                <div class="divTableHeading"></div>
            </div>            
            <div class="divTableRow">
                <div class="divTableCell"><?= $lng['L_HOSTNAME'] ?>: </div>
                <div class="divTableCell"><span id="hostname"><?= pr_field($tdata, 'hostname') ?></span></div>
            </div>
            <div class="divTableRow">
                <div class="divTableCell"><?= $lng['L_UPTIME'] ?>: </div>
                <div class="divTableCell"><span id="uptime"><?= pr_field($tdata, 'uptime') ?></span></div>
            </div>            
            <div class="divTableRow">
                <div class="divTableCell"><?= $lng['L_BOOTED'] ?>: </div>
                <div class="divTableCell"><span id="boot_day"><?= pr_field($tdata, 'boot_day') ?></span></div>
            </div>                        
            <div class="divTableRow">
                <div class="divTableCell"><?= $lng['L_OS'] ?>:</div>
                <div class="divTableCell"> <span id="plataform"><?= pr_field($tdata, 'plataform') ?></span></div>
            </div>
            <div class="divTableRow">
                <div class="divTableCell"><?= $lng['L_DISTRO'] ?>:</div>
                <div class="divTableCell"><span id="distro_fullname"><?= pr_field($tdata, 'distro_fullname') ?></span></div>
            </div>
            <div class="divTableRow">
                <div class="divTableCell"><?= $lng['L_TIME'] ?>:</div>
                <div class="divTableCell"><span id="system_time"><?= pr_field($tdata, 'system_time') ?></span></div>
            </div>
            <div class="divTableRow">
                <div class="divTableCell"><?= $lng['L_KERNEL'] ?>:</div>
                <div class="divTableCell"><span id="release"><?= pr_field($tdata, 'release') ?></span></div>
            </div>
            <div class="divTableRow">
                <div class="divTableCell"><?= $lng['L_ARCH'] ?>: </div>
                <div class="divTableCell"><span id="machine"><?= pr_field($tdata, 'machine') ?></span></div>
            </div>
            <div class="divTableRow">
                <div class="divTableCell"><?= $lng['L_NCPU'] ?>: </div>
                <div class="divTableCell"><span id="ncpu"><?= pr_field($tdata, 'ncpu') ?>(<?= pr_field($tdata, 'nthreads') ?>)</span></div>
            </div>
            <div class="divTableRow">
                <div class="divTableCell"><?= $lng['L_RAMUSAGE'] ?>:</div>
                <div class="divTableCell"><progress id="ram_usage_value" max="100" value="0"><span id="ram_usage"></span></progress></div>
            </div>
            <div class="divTableRow">
                <div class="divTableCell"><?= $lng['L_CPULOAD'] ?>:</div>
                <div class="divTableCell"><progress id="cpu_load_value" max="100" value="0"><span id="cpu_load"></span></progress></div>
            </div>
            <div class="divTableRow">
                <div class="divTableCell"><?= $lng['L_LOADAVG'] ?>:</div>
                <div class="divTableCell"><progress id="load_avg_value" max="1" value="0">
                        <<span id="load_avg"></span>/progress></div>
            </div>
        </div>
    </div>
    <!-- DiskTable -->
    <?php if (!empty($tdata['disks'])) { ?>
        <div class="divTable">
            <div class="divTableBody">
                <div class="divTableRow">
                    <div class="divTableHeading"><?= $lng['L_DISKS'] ?></div>
                    <div class="divTableHeading"><?= $lng['L_MOUNTPOINT'] ?></div>
                    <div class="divTableHeading"><?= $lng['L_FSTYPE'] ?></div>
                    <div class="divTableHeading"><?= $lng['L_USED'] ?></div>
                    <div class="divTableHeading"><?= $lng['L_TOTAL'] ?></div>
                    <div class="divTableHeading"><?= $lng['L_FREE'] ?></div>
                    <div class="divTableHeading"><?= $lng['L_USED'] ?></div>
                </div>
                <?php
                foreach ($tdata['disks'] as $disk) {
                ?>
                    <div class="divTableRow">
                        <div class="divTableCell"><?= $disk['disk_device'] ?></div>
                        <div class="divTableCell"><?= $disk['disk_mountpoint'] ?></div>
                        <div class="divTableCell"><?= $disk['disk_fstype'] ?></div>
                        <div class="divTableCell"><span id='disk_used_percent'><?= $disk['disk_used_percent'] ?>%</span></div>
                        <div class="divTableCell"><?= $disk['disk_total'] ?></div>
                        <div class="divTableCell"><?= $disk['disk_free'] ?></div>
                        <div class="divTableCell"><?= $disk['disk_used'] ?></div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    <?php } ?>
    <!-- /DiskTable -->
    <!-- NetworkTable -->
    <?php if (!empty($tdata['network'])) { ?>
        <div class="divTable">
            <div class="divTableBody">
                <div class="divTableRow">
                    <div class="divTableHeading"><?= $lng['L_NETWORK'] ?></div>
                    <div class="divTableHeading"><?= $lng['L_DATA_RECV'] ?></div>
                    <div class="divTableHeading"><?= $lng['L_DATA_SENT'] ?></div>
                    <div class="divTableHeading"><?= $lng['L_PACKETS_RECV'] ?></div>
                    <div class="divTableHeading"><?= $lng['L_PACKETS_SENT'] ?></div>
                    <div class="divTableHeading"><?= $lng['L_ERROR_IN'] ?></div>
                    <div class="divTableHeading"><?= $lng['L_ERROR_OUT'] ?></div>
                    <div class="divTableHeading"><?= $lng['L_DROP_IN'] ?></div>
                    <div class="divTableHeading"><?= $lng['L_DROP_OUT'] ?></div>
                </div>
                <?php
                foreach ($tdata['network'] as $network) {
                ?>
                    <div class="divTableRow">
                        <div class="divTableCell"><span id="net_device_<?= $network['id'] ?>"><?= $network['net_device'] ?></span></div>
                        <div class="divTableCell"><span id="net_bytes_recv_<?= $network['id'] ?>"><?= $network['net_bytes_recv'] ?></span></div>
                        <div class="divTableCell"><span id="net_bytes_sent_<?= $network['id'] ?>"><?= $network['net_bytes_sent'] ?></span></div>
                        <div class="divTableCell"><span id="net_packets_recv_<?= $network['id'] ?>"><?= $network['net_packets_recv'] ?></span></div>
                        <div class="divTableCell"><span id="net_packets_sent_<?= $network['id'] ?>"><?= $network['net_packets_sent'] ?></span></div>
                        <div class="divTableCell"><span id="net_err_in<?= $network['id'] ?>"><?= $network['net_err_in'] ?></span></div>
                        <div class="divTableCell"><span id="net_err_out<?= $network['id'] ?>"><?= $network['net_err_out'] ?></span></div>
                        <div class="divTableCell"><span id="net_drop_in<?= $network['id'] ?>"><?= $network['net_drop_in'] ?></span></div>
                        <div class="divTableCell"><span id="net_drop_out<?= $network['id'] ?>"><?= $network['net_drop_out'] ?></span></div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    <?php } ?>
    <!-- /NetworkTable -->

    <!--
<p><?= $lng['L_PARTITIONS'] ?>: <span id="partitions"></span></p>
<p><?= $lng['L_DISK_IO'] ?>: <span id="disks_io"></span></p>
    -->