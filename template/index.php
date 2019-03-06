<?php
/**
 * @package    Joomla.Site
 * @subpackage Template.foo
 *
 * @author     [AUTHOR] <[AUTHOR_EMAIL]>
 * @copyright  [COPYRIGHT]
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       [AUTHOR_URL]
 */

defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

require_once JPATH_THEMES . '/' . $this->template . '/helper.php';

tplHelperFunctions::loadCss();
tplHelperFunctions::loadJs();
tplHelperFunctions::setMetadata(false);
tplHelperFunctions::setGenerator(''); /* Remove generator tag */

?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<jdoc:include type="head" />
</head>
<body class="<?php echo tplHelperFunctions::setBodySuffix(); ?>">

<a href="#main" class="sr-only sr-only-focusable"><?php echo Text::_('TPL_FOO_SKIP_LINK_LABEL'); ?></a>

<a href="<?php echo $this->baseurl; ?>/">
    <?php echo tplHelperFunctions::getSitename(); ?>
    <?php if ($this->params->get('sitedescription')) : ?>
        <?php echo '<div class="site-description">' . htmlspecialchars($this->params->get('sitedescription'), ENT_COMPAT, 'UTF-8') . '</div>'; ?>
    <?php endif; ?>
</a>

<nav role="navigation" >
	<jdoc:include type="modules" name="position-0" style="none" />
</nav>

<main id="main">
	<jdoc:include type="message" />
	<jdoc:include type="component" />
</main>

<aside>
    <?php if ($this->countModules('position-1')) : ?>
		<jdoc:include type="modules" name="position-1" style="none" />
	<?php endif; ?>
</aside>

<footer>
	<jdoc:include type="modules" name="footer" style="none" />
	<p>
		&copy; <?php echo date('Y'); ?> <?php echo tplHelperFunctions::getSitename(); ?>
	</p>
</footer>
<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
