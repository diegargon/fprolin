<div id="topnav" class="topnav">
    <div class="opensidenav" onclick="toggleNav()">☰</div>
    <?php foreach ($tdata['topnav'] as $topnav_element) { ?>
        <a href="<?= $topnav_element['href'] ?>"><?= $topnav_element['caption'] ?></a>
    <?php } ?>
</div>
<div id="sidenav" class="sidenav">
    <?php
    foreach ($tdata['sidebar'] as $side_element) { ?>
        <div class="navbar">
            <a href="<?= $side_element['href'] ?>"><?= $side_element['caption'] ?></a>
            <?php if (!empty($side_element['submenu'])) { ?>
                <div class="subnav">
                    <?php
                    foreach ($side_element['submenu'] as $submenu) {
                    ?>
                        <a href="<?= $submenu['href'] ?>"><?= $submenu['caption'] ?></a>
                    <?php
                    }
                    ?>
                </div>
            <?php
            }
            ?>
        </div>

    <?php
    }
    ?>
</div>
<div id="main">
    <?php
    // pr($tdata) 
    ?>
    <div><?= !empty($tdata['msg']) ? $tdata['msg']  : null;  ?></div>
    <div><?= !empty($tdata['main']) ? $tdata['main']  : null; ?></div>
</div>