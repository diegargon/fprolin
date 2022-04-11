<script>
    $(document).ready(function() {
        refresh('system');
    });
</script>

<h1><?= $lng['L_SYSTEM'] ?></h1>
<p><?= $lng['L_HOSTNAME'] ?>: <span id="hostname"></span></p>
<p><?= $lng['L_OS'] ?>: <span id="plataform"></span></p>
<p><?= $lng['L_OS'] ?>: <span id="distro_name"></span></p>
<p><?= $lng['L_OS'] ?>: <span id="distro_fullname"></span></p>
<p><?= $lng['L_TIME'] ?>: <span id="system_time"></span></p>
<p><?= $lng['L_KERNEL'] ?>: <span id="release"></span></p>
<p><?= $lng['L_PROCESSADOR'] ?>: <span id="processor"></span></p>
<p><?= $lng['L_ARCH'] ?>: <span id="machine"></span></p>
<p><?= $lng['L_CPULOAD'] ?>: <span id="cpu_load"></span></p>
<p><?= $lng['L_LOADAVG'] ?>: <span id="load_avg"></span></p>
<p><?= $lng['L_RAMUSAGE'] ?>: <span id="ram_usage"></span></p>
<p><?= $lng['L_NCPU'] ?>: <span id="ncpu"></span></p>
<p><?= $lng['L_NTHREADS'] ?>: <span id="nthreads"></span></p>
<p><?= $lng['L_PARTITIONS'] ?>: <span id="partitions"></span></p>
<p><?= $lng['L_DISK_USAGE'] ?>: <span id="disk_usage"></span></p>
<p><?= $lng['L_DISK_IO'] ?>: <span id="disks_io"></span></p>