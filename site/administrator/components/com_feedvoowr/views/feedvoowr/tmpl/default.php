<?php
$path = JURI::base().'components'.DS.'com_feedvoowr'.DS.'extjs';
$extpath = "/extjs/ext-4.0-pr3";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Feed Viewer</title>
    <link rel="stylesheet" type="text/css" href="<?php echo $extpath; ?>/resources/css/ext-all.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $path; ?>/Feed-Viewer.css">
<style type="text/css">
.x-menu-item img.preview-right, button.preview-right {
    background-image: url(<?php echo $path; ?>/images/preview-right.gif);
}
.x-menu-item img.preview-bottom, button.preview-bottom {
    background-image: url(<?php echo $path; ?>/images/preview-bottom.gif);
}
.x-menu-item img.preview-hide, button.preview-hide {
    background-image: url(<?php echo $path; ?>/images/preview-hide.gif);
}

#reading-menu .x-menu-item-checked {
    border: 1px dotted #a3bae9 !important;
    background: #DFE8F6;
    padding: 0;
    margin: 0;
}
</style>
    <script type="text/javascript" src="<?php echo $extpath; ?>/bootstrap.js"></script>
    <script type="text/javascript" src="<?php echo $path; ?>/viewer/FeedPost.js"></script>
    <script type="text/javascript" src="<?php echo $path; ?>/viewer/FeedDetail.js"></script>
    <script type="text/javascript" src="<?php echo $path; ?>/viewer/FeedGrid.js"></script>
    <script type="text/javascript" src="<?php echo $path; ?>/viewer/FeedInfo.js"></script>
    <script type="text/javascript" src="<?php echo $path; ?>/viewer/FeedPanel.js"></script>
    <script type="text/javascript" src="<?php echo $path; ?>/viewer/FeedViewer.js"></script>
    <script type="text/javascript" src="<?php echo $path; ?>/viewer/FeedWindow.js"></script>
    <script type="text/javascript">
        Ext.Loader.setConfig({enabled: true});
        Ext.Loader.setPath('Ext.ux', '<?php echo $extpath;?>/examples/ux/');
        Ext.require([
            'Ext.grid.*',
            'Ext.data.*',
            'Ext.util.*',
            'Ext.Action',
            'Ext.tab.*',
            'Ext.button.*',
            'Ext.form.*',
            'Ext.layout.container.Card',
            'Ext.layout.container.Border',
            'Ext.ux.PreviewPlugin'
        ]);
        Ext.onReady(function(){
            var app = new FeedViewer.App();
        });
    </script>
</head>
<body>
</body>
</html>
