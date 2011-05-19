<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo  $this->language; ?>" lang="<?php echo  $this->language; ?>" dir="<?php echo  $this->direction; ?>">
<head>
<jdoc:include type="head" />

<link rel="stylesheet" href="templates/system/css/system.css" type="text/css" />
<link href="templates/<?php echo  $this->template ?>/css/template.css" rel="stylesheet" type="text/css" />
<link href="templates/<?php echo  $this->template ?>/css/960_fluid.css" rel="stylesheet" type="text/css" media="screen and (min-width:1025px)" />
<link href="templates/tablet/css/960_fluid.css" rel="stylesheet" type="text/css" media="screen and (max-width: 1024px)" />

<?php if($this->direction == 'rtl') : ?>
	<link href="templates/<?php echo  $this->template ?>/css/template_rtl.css" rel="stylesheet" type="text/css" />
<?php endif; ?>

<script type="text/javascript" src="templates/<?php echo  $this->template ?>/js/chromatable.js"></script>

<?php if(JModuleHelper::isEnabled('menu')) : ?>
	<script type="text/javascript" src="templates/<?php echo  $this->template ?>/js/menu.js"></script>
	<script type="text/javascript" src="templates/<?php echo  $this->template ?>/js/index.js"></script>
<?php endif; ?>

</head>
<body id="minwidth-body" class="<?php echo JRequest::getVar('option', 'cmd'); ?>" >
	<div id="container" class="-koowa-box -koowa-box-vertical">
		<div id="header-box">
			<div id="module-status">
				<jdoc:include type="modules" name="status"  />
			</div>
			<div id="module-menu">
				<jdoc:include type="modules" name="menu" />
			</div>
		</div>
		<div id="tabs-box">
			<jdoc:include type="modules" name="submenu" style="rounded" id="submenu-box" />
		</div>
		<div id="toolbar-box">
			<jdoc:include type="modules" name="toolbar" />
			<jdoc:include type="modules" name="title" />
		</div>
		<div id="message-box">
			<jdoc:include type="message" />
		</div>
		<div id="content-box" class="container_12 <?php echo (JRequest::getInt('hidemainmenu')) ? 'form' : 'default' ?>">
			<jdoc:include type="component" />
		</div>
	</div>	 
</body>
</html>