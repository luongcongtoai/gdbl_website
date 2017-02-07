<?php
/**
 * @package        Joomla.Site
 * @copyright    Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
if (!isset($this->error)) {
    $this->error = JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
    $this->debug = false;
}
//get language and direction
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;
?>
<?php 
$app = & JFactory::getApplication();
$siteName = $app->getCfg('sitename');
$theme = JFactory::getApplication()->getTemplate(true)->params->get('theme', '');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <title><?php echo $this->error->getCode(); ?> - <?php echo $this->title; ?></title>
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/error.css" type="text/css" />
    <?php if($theme && is_file(T3_TEMPLATE_PATH . '/css/themes/' . $theme . '/error.css')):?>
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/themes/<?php echo $theme ?>/error.css" type="text/css" />
    <?php endif; ?>
    <link href='<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/fonts/capulus/stylesheet.css' rel='stylesheet' type='text/css'>
</head>
<body>
    <div class="error">
            <h1>4<span>0</span>4</h1>
			<div id="main-error">	
				<div id="tittle">
					<h2>Error!</h2>
				</div>
				<div id="caption">
					<p>Please try one of the flollowing page:<a href="<?php echo $this->baseurl; ?>/index.php">Home</a></p>
				</div>
			</div>
    </div>
</body>
</html>
