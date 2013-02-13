<?php
$path = JURI::base().'components/com_desktop/ext4';
$extpath = "/extjs/ext-4.0-pr3";
?>
    <link rel="stylesheet" type="text/css" href="http://dev.sencha.com/deploy/dev/resources/css/ext-all.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $path; ?>/css/desktop.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $extpath; ?>/resources/css/ext-sandbox.css">
    <link rel="stylesheet" type="text/css" href="/extjs/ext-3.3.1/examples/view/chooser.css">

    <style>
        #chart-win-shortcut img {
            width:48px;
            height:48px;
            background-image: url(<?php echo $path; ?>/images/chart48x48.png);
            filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $path; ?>/images/chart48x48.png', sizingMethod='scale');
        }
    </style>
    <script type="text/javascript" src="http://dev.sencha.com/deploy/dev/adapter/ext/ext-base.js"></script><!-- ENDLIBS -->

    <script type="text/javascript" src="http://dev.sencha.com/deploy/dev/ext-all-debug.js"></script><!-- DESKTOP -->

    <script type="text/javascript" src="index.php?option=com_desktop&view=desktop&controller=startmenu&format=raw&layout=startmenu"></script>
    <script type="text/javascript" src="<?php echo $path; ?>/js/TaskBar.js"></script>
    <script type="text/javascript" src="<?php echo $path; ?>/js/Desktop.js"></script>
    <script type="text/javascript" src="<?php echo $path; ?>/js/App.js"></script>
    <script type="text/javascript" src="<?php echo $path; ?>/js/Module.js"></script>
    <script type="text/javascript" src='index.php?option=com_desktop&view=desktop&controller=startmenu&layout=config&format=raw'></script>
    <script type="text/javascript" src='index.php?option=com_desktop&view=desktop&controller=startmenu&layout=feedviewer&format=raw'></script>
    <script type="text/javascript" src='index.php?option=com_desktop&view=desktop&controller=startmenu&layout=scheduler&format=raw'></script>
    <script type="text/javascript" src='index.php?option=com_desktop&view=desktop&controller=startmenu&layout=chooser&format=raw'></script>

    <script type="text/javascript" src="<?php echo $extpath; ?>/ext-core-sandbox-debug.js"></script>
    <script type="text/javascript" src="<?php echo $extpath; ?>/ext-all-sandbox-debug.js"></script>
    <script type="text/javascript" src="<?php echo $path; ?>/sandbox.js"></script>
    <script type="text/javascript" src="index.php?option=com_desktop&task=stats&view=system&format=raw"></script>
<script>
        // Hide the Admin Menu under Joomla! 1.5
		Ext.fly('toolbar').hide();
                Ext.fly('ap-content').setStyle('background','#3d71b8 url(<?php echo $path; ?>/wallpapers/desktop.jpg) no-repeat left top');

</script>



    <div id="x-desktop">
        <a href="http://extjs.com" target="_blank" style="margin:5px; float:right;"><img src="<?php echo $path; ?>/images/powered.gif" /></a>

        <dl id="x-shortcuts">
            <dt id="grid-win-shortcut">
                <a href="#"><img src="<?php echo $path; ?>/images/s.gif" />
                <div>Grid Window</div></a>
            </dt>
            <dt id="acc-win-shortcut">
                <a href="#"><img src="<?php echo $path; ?>/images/s.gif" />
                <div>Accordion Window</div></a>
            </dt>
            <dt id="chart-win-shortcut">
                <a href="#"><img src="<?php echo $path; ?>/images/s.gif" />
                <div>Charts</div></a>
            </dt>
        </dl>
    </div>

    <div id="ux-taskbar">
        <div id="ux-taskbar-start"></div>
        <div id="ux-taskbuttons-panel"></div>
        <div class="x-clear"></div>
    </div>
