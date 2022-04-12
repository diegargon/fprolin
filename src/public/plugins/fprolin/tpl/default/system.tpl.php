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
                <div class="divTableCell"><?= $lng['L_OS'] ?>:</div>
                <div class="divTableCell"> <span id="plataform"><?= pr_field($tdata, 'plataform') ?></span></div>
            </div>
            <div class="divTableRow">
                <div class="divTableCell"><?= $lng['L_OS'] ?>:</div>
                <div class="divTableCell"><span id="distro_name"><?= pr_field($tdata, 'distro_name') ?></span></div>
            </div>
            <div class="divTableRow">
                <div class="divTableCell"><?= $lng['L_OS'] ?>:</div>
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
                <div class="divTableCell"><progress id="load_avg_value" max="1" value="0"><<span id="load_avg"></span>/progress></div>
            </div>                        
        </div>
    </div>
    <!-- DiskTable -->
    <div class="divTable">
        <div class="divTableBody">
            <div class="divTableRow">
                <div class="divTableHeading"><?= $lng['L_DISK_USAGE'] ?></div>
                <div class="divTableHeading"></div>
                <div class="divTableHeading"></div>
            </div>
        <?php
        foreach ($tdata['disks'] as $disk) {
        ?>
            <div class="divTableRow">
                <div class="divTableCell"><?= $disk['disk_device'] ?></div>
                <div class="divTableCell"><?= $disk['disk_mountpoint'] ?></div>
                <div class="divTableCell"><span id='disk_used_percent'><?= $disk['disk_used_percent'] ?>%</span></div>
            </div>
        <?php 
        }    
        ?>
        </div>
    </div>    
    <!-- /DiskTable -->
    <!--
<p><?= $lng['L_PARTITIONS'] ?>: <span id="partitions"></span></p>
<p><?= $lng['L_DISK_USAGE'] ?>: <span id="disk_usage"></span></p>
<p><?= $lng['L_DISK_IO'] ?>: <span id="disks_io"></span></p>
    -->