<?php
/**
 * @version		$Id$
 * @author		NooTheme
 * @package		Joomla.Site
 * @subpackage	mod_noo_login
 * @copyright	Copyright (C) 2013 NooTheme. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<div class="noologin <?php echo $params->get('moduleclass_sfx'); ?>">
	<ul class="noologin-fieldset">
		<?php if ($type == 'logout'): ?>
			<li>
				<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form" class="form-vertical">
					<?php if ($params->get('greeting')) : ?>
						<div class="noologin-greeting">
							<?php
							if ($params->get('name') == 0) :
								echo JText::sprintf('MOD_NOO_LOGIN_HINAME', $user->get('name'));
							else :
								echo JText::sprintf('MOD_NOO_LOGIN_HINAME', $user->get('username'));
							endif;
							?>
						</div>
	<?php endif; ?>
					<div class="noologout-button">
						<input type="submit" class="btn btn-primary" value="<?php echo JText::_('MOD_NOO_LOGIN_LOGOUT'); ?>" />
					</div>
					<div>
						<input type="hidden" name="option" value="com_users" />
						<input type="hidden" name="task" value="user.logout" />
						<input type="hidden" name="return" value="<?php echo $returnURL; ?>" />
	<?php echo JHtml::_('form.token'); ?>
					</div>
				</form>
			</li>
<?php else : ?>
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_users&view=login'); ?>" onclick="nooShowBox('#nologin_login'); return false;" title="<?php echo JText::_('MOD_NOO_LOGIN_LOGIN'); ?>"><?php echo JText::_('MOD_NOO_LOGIN_LOGIN'); ?></a>
				<div class="noologin-form" id="nologin_login">
					<form method="post" action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>">
	<?php if ($params->get('pretext')): ?>
							<div class="noologin-pretext">
								<p><?php echo $params->get('pretext'); ?></p>
							</div>
	<?php endif; ?>
						<div id="form-login-username" class="control-group">
							<label for="modlgn-username"><?php echo JText::_('MOD_NOO_LOGIN_VALUE_USERNAME') ?></label>
							<input id="modlgn-username" type="text" name="username" class="input-small" tabindex="0" size="18" placeholder="<?php echo JText::_('MOD_NOO_LOGIN_VALUE_USERNAME') ?>" />
						</div>
						<div id="form-login-password" class="control-group">
							<label for="modlgn-passwd"><?php echo JText::_('JGLOBAL_PASSWORD') ?></label>
							<input id="modlgn-passwd" type="password" name="password" class="input-small" tabindex="0" size="18" placeholder="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" />	
						</div>
	<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
							<div id="form-login-remember" class="control-group checkbox">
								<label for="modlgn-remember" class="control-label"><?php echo JText::_('MOD_NOO_LOGIN_REMEMBER_ME') ?>
									<input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="yes"/>
								</label>
							</div>
	<?php endif; ?>
						<div class="control-group">
							<div class="controls">
								<button class="btn btn-primary" tabindex="0" type="submit"><?php echo JText::_('MOD_NOO_LOGIN_LOGIN') ?></button>
							</div>
						</div>
	<?php if ($params->get('posttext')) : ?>
							<div class="noologin-posttext">
								<p><?php echo $params->get('posttext'); ?></p>
							</div>
						<?php endif; ?>
						<?php
						$usersConfig = JComponentHelper::getParams('com_users');
						if ($usersConfig->get('allowUserRegistration')) :
							?>
							<ol>
								<li>
									<a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">
		<?php echo JText::_('MOD_NOO_LOGIN_REGISTER'); ?></a>
								</li>
								<li>
									<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
		<?php echo JText::_('MOD_NOO_LOGIN_FORGOT_YOUR_USERNAME'); ?></a>
								</li>
								<li>
									<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>"><?php echo JText::_('MOD_NOO_LOGIN_FORGOT_YOUR_PASSWORD'); ?></a>
								</li>

							</ol>
	<?php endif; ?>
						<div>
							<input type="hidden" name="option" value="com_users" />
							<input type="hidden" name="task" value="user.login" />
							<input type="hidden" name="return" value="<?php echo $returnURL; ?>" />
	<?php echo JHTML::_('form.token'); ?>
						</div>
					</form>
				</div>
			</li>
			<?php
			$usersConfig = JComponentHelper::getParams('com_users');
			if ($usersConfig->get('allowUserRegistration')) :
				?>
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>" onclick="nooShowBox('#noologin_register'); return false;" title="<?php echo JText::_('MOD_NOO_LOGIN_REGISTER') ?>"><span><?php echo JText::_('MOD_NOO_LOGIN_REGISTER') ?></span></a>
					<div class="noologin-form" id="noologin_register">
						<form method="post" action="<?php echo JRoute::_('index.php', true, false); ?>">
							<div class="control-group">
								<label for="jform_name"><?php echo JText::_('MOD_NOO_LOGIN_VALUE_NAME') ?>: <span class="star"> *</span></label>
								<input id="jform_name" class="required " type="text" aria-required="true" required="required" size="30" value="" name="jform[name]" aria-="true">
							</div>
							<div class="control-group">
								<label for="jform_username"><?php echo JText::_('MOD_NOO_LOGIN_VALUE_USERNAME') ?>: <span class="star"> *</span></label>
								<input id="jform_username" class="required " type="text" aria-required="true" required="required" size="30" value="" name="jform[username]" aria-="true">
							</div>
							<div class="control-group">
								<label for="jform_password1"><?php echo JText::_('MOD_NOO_LOGIN_VALUE_PASSWORD') ?>: <span class="star"> *</span></label>
								<input id="jform_password1" class="validate-password required " type="password" aria-required="true" required="required" size="30" autocomplete="off" value="" name="jform[password1]" aria-="true">
							</div>
							<div class="control-group">
								<label for="jform_username"><?php echo JText::_('MOD_NOO_LOGIN_VALUE_REPASSWORD') ?>: <span class="star"> *</span></label>
								<input id="jform_password2" class="validate-password required " type="password" aria-required="true" required="required" size="30" autocomplete="off" value="" name="jform[password2]" aria-="true">
							</div>
							<div class="control-group">
								<label for="jform_email1"><?php echo JText::_('MOD_NOO_LOGIN_VALUE_EMAIL') ?>: <span class="star"> *</span></label>
								<input id="jform_email1" class="validate-email required " type="email" aria-required="true" required="required" size="30" value="" name="jform[email1]" aria-="true">
							</div>
							<div class="control-group">
								<label for="jform_email2"><?php echo JText::_('MOD_NOO_LOGIN_VALUE_REEMAIL') ?>: <span class="star"> *</span></label>
								<input id="jform_email2" class="validate-email required" type="email" aria-required="true" required="required" size="30" value="" name="jform[email2]">
							</div>
		<?php if (!empty($noocaptcha)): ?>
								<div class="control-group">
									<label for="jform_email2"><?php echo JText::_('MOD_NOO_LOGIN_VALUE_CAPTCHA') ?>: <span class="star"> *</span></label>
								<?php echo $noocaptcha ?>
								</div>
		<?php endif; ?>
							<div class="">
								<em><?php echo JText::_("MOD_NOO_LOGIN_REQUIREMENT_DESC"); ?></em>
								<br>
								<br>
							</div>
							<div class="control-group">
								<div class="controls">
									<button class="btn btn-primary" tabindex="0" type="submit"><?php echo JText::_('MOD_NOO_LOGIN_REGISTER') ?></button>
								</div>
							</div>
							<div>
								<input type="hidden" name="option" value="com_users" />
								<input type="hidden" name="task" value="registration.register" />
								<?php echo JHTML::_('form.token'); ?>
							</div>
						</form>
					</div>
				</li>
			<?php endif; ?>
<?php endif; ?>
	</ul>
</div>