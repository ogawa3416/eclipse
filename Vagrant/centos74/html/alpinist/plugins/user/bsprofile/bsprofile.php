<?php
/**
 * @version		$Id: bsprofile.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.5.0 $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

require_once( JPATH_ROOT.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_bscore'.DIRECTORY_SEPARATOR.'helper'.DIRECTORY_SEPARATOR.'bscore.helper.php' );
/**
 * An example custom profile plugin.
 *
 * @package		Joomla.Plugin
 * @subpackage	User.bsprofile
 * @version		1.6
 */
class plgUserBsprofile extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.5
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * @param	string	$context	The context for the data
	 * @param	int		$data		The user id
	 * @param	object
	 *
	 * @return	boolean
	 * @since	1.6
	 */
	function onContentPrepareData($context, $data)
	{
		// Check we are manipulating a valid form.
		if (!in_array($context, array('com_users.profile','com_users.user', 'com_users.registration', 'com_admin.profile'))) {
			return true;
		}
		if (is_object($data))
		{
			$userId = isset($data->id) ? $data->id : 0;

			if (!isset($data->bsprofile) and $userId > 0) {
				// Load the profile data from the database.
				$db = JFactory::getDbo();
				$db->setQuery(
					'SELECT profile_key, profile_value FROM #__user_profiles' .
					' WHERE user_id = '.(int) $userId." AND profile_key LIKE 'bsprofile.%'" .
					' ORDER BY ordering'
				);
				$results = $db->loadRowList();

				// Check for a database error.
				if ($db->getErrorNum())
				{
					$this->_subject->setError($db->getErrorMsg());
					return false;
				}

				// Merge the profile data.
				$data->bsprofile = array();

				foreach ($results as $v)
				{
					$k = str_replace('bsprofile.', '', $v[0]);
					$data->bsprofile[$k] = $v[1];
				}
			}

			if (!JHtml::isRegistered('users.url')) {
				JHtml::register('users.url', array(__CLASS__, 'url'));
			}
			if (!JHtml::isRegistered('users.divcode')) {
				JHtml::register('users.divcode', array(__CLASS__, 'divcode'));
			}
			if (!JHtml::isRegistered('users.isbusiness')) {
				JHtml::register('users.isbusiness', array(__CLASS__, 'isbusiness'));
			}
		}

		return true;
	}

	public static function url($value)
	{
		if (empty($value))
		{
			return JHtml::_('users.value', $value);
		}
		else
		{
			$value = htmlspecialchars($value);
			if(substr ($value, 0, 4) == "http") {
				return '<a href="'.$value.'">'.$value.'</a>';
			}
			else {
				return '<a href="http://'.$value.'">'.$value.'</a>';
			}
		}
	}

	public static function divcode($value)
	{
		if( $value ) {
			$db = JFactory::getDbo();
			$query = 'SELECT divcode, divname_s'
			. ' FROM #__bs_division'
			. ' WHERE div_stat = 1 and divcode = '.$db->Quote($value)
			;
			$db->setQuery( $query );
			$_divname = $db->loadObject();
			if( $_divname ) {
				return $_divname->divname_s;
			} else {
				return sprintf(JText::_('PLG_USER_BSPROFILE_MSG_UNDEF_DIVCODE'),$value);
			}
		} else {
			return JHtml::_('users.value', $value);
		}
	}

	public static function isbusiness($value)
	{
		if ($value=='1') {
			return JText::_('PLG_USER_BSPROFILE_OPTION_CORP_USER');
		}
		else {
			return JText::_('PLG_USER_BSPROFILE_OPTION_PUBLIC_USER');
		}
	}

	/**
	 * @param	JForm	$form	The form to be altered.
	 * @param	array	$data	The associated data for the form.
	 *
	 * @return	boolean
	 * @since	1.6
	 */
	function onContentPrepareForm($form, $data)
	{

		if (!($form instanceof JForm))
		{
			$this->_subject->setError('JERROR_NOT_A_FORM');
			return false;
		}

		// Check we are manipulating a valid form.
		if (!in_array($form->getName(), array('com_admin.profile','com_users.user', 'com_users.registration','com_users.profile'))) {
			return true;
		}

		// Add the registration fields to the form.
		JForm::addFormPath(dirname(__FILE__).'/profiles');
		$form->loadFile('bsprofile', false);

		$app	= JFactory::getApplication();
		$input = $app->input;
		if( $app->isAdmin() || (!$app->isAdmin() &&  $input->get('view','') == 'registration')) {
/** admin view & registration view **/
			// Toggle whether the isbusiness field is required.
			if ($this->params->get('register-require_isbusiness', 1) > 0) {
				$form->setFieldAttribute('isbusiness', 'required', $this->params->get('register-require_isbusiness') == 2, 'bsprofile');
			}
			else {
				$form->removeField('isbusiness', 'bsprofile');
			}

			// Toggle whether the name1 field is required.
			if ($this->params->get('register-require_name1', 1) > 0) {
				$form->setFieldAttribute('name1', 'required', $this->params->get('register-require_name1') == 2, 'bsprofile');
			}
			else {
				$form->removeField('name1', 'bsprofile');
			}

			// Toggle whether the name2 field is required.
			if ($this->params->get('register-require_name2', 1) > 0) {
				$form->setFieldAttribute('name2', 'required', $this->params->get('register-require_name2') == 2, 'bsprofile');
			}
			else {
			$form->removeField('name2', 'bsprofile');
			}

			// Toggle whether the company field is required.
			if ($this->params->get('register-require_company', 1) > 0) {
				$form->setFieldAttribute('company', 'required', $this->params->get('register-require_company') == 2, 'bsprofile');
			}
			else {
				$form->removeField('company', 'bsprofile');
			}

			// Toggle whether the section field is required.
			if ($this->params->get('register-require_divcode', 1) > 0) {
			$form->setFieldAttribute('divcode', 'required', $this->params->get('register-require_divcode') == 2, 'bsprofile');
			}
			else {
				$form->removeField('divcode', 'bsprofile');
			}

			// Toggle whether the employeeno field is required.
			if ($this->params->get('register-require_employeeno', 1) > 0) {
				$form->setFieldAttribute('employeeno', 'required', $this->params->get('register-require_employeeno') == 2, 'bsprofile');
			}
			else {
				$form->removeField('employeeno', 'bsprofile');
			}

			// Toggle whether the address1 field is required.
			if ($this->params->get('register-require_address', 1) > 0) {
				$form->setFieldAttribute('address', 'required', $this->params->get('register-require_address') == 2, 'bsprofile');
			}
			else {
				$form->removeField('address', 'bsprofile');
			}

			// Toggle whether the state field is required.
			if ($this->params->get('register-require_state', 1) > 0) {
				$form->setFieldAttribute('state', 'required', $this->params->get('register-require_state') == 2, 'bsprofile');
			}
			else {
				$form->removeField('state', 'bsprofile');
			}
			
			// Toggle whether the city field is required.
			if ($this->params->get('register-require_city', 1) > 0) {
				$form->setFieldAttribute('city', 'required', $this->params->get('register-require_city') == 2, 'bsprofile');
			}
			else {
				$form->removeField('city', 'bsprofile');
			}

			// Toggle whether the country field is required.
			if ($this->params->get('register-require_country', 1) > 0) {
				$form->setFieldAttribute('country', 'required', $this->params->get('register-require_country') == 2, 'bsprofile');
			}
			else {
				$form->removeField('country', 'bsprofile');
			}

			// Toggle whether the postal code field is required.
			if ($this->params->get('register-require_zipcode', 1) > 0) {
				$form->setFieldAttribute('zipcode', 'required', $this->params->get('register-require_zipcode') == 2, 'bsprofile');
			}
			else {
				$form->removeField('zipcode', 'bsprofile');
			}

			// Toggle whether the phone field is required.
			if ($this->params->get('register-require_teleno', 1) > 0) {
				$form->setFieldAttribute('teleno', 'required', $this->params->get('register-require_teleno') == 2, 'bsprofile');
			}
			else {
				$form->removeField('teleno', 'bsprofile');
			}

			// Toggle whether the website field is required.
			if ($this->params->get('register-require_company_hp', 1) > 0) {
				$form->setFieldAttribute('company_hp', 'required', $this->params->get('register-require_company_hp') == 2, 'bsprofile');
			}
			else {
				$form->removeField('company_hp', 'bsprofile');
			}

			// Toggle whether the note field is required.
			if ($this->params->get('register-require_note', 1) > 0) {
				$form->setFieldAttribute('note', 'required', $this->params->get('register-require_note') == 2, 'bsprofile');
			}
			else {
				$form->removeField('note', 'bsprofile');
			}
		} else {
/** front view **/
			// Toggle whether the isbusiness field is required.
			if ($this->params->get('profile-require_isbusiness', 1) == 3) {
				$form->setFieldAttribute('isbusiness', 'readonly', 'true', 'bsprofile');
			}
			if ($this->params->get('profile-require_isbusiness', 1) > 0) {
				$form->setFieldAttribute('isbusiness', 'required', $this->params->get('profile-require_isbusiness') == 2, 'bsprofile');
			}
			else {
				$form->removeField('isbusiness', 'bsprofile');
			}

			// Toggle whether the name1 field is required.
			if ($this->params->get('profile-require_name1', 1) == 3) {
				$form->setFieldAttribute('name1', 'readonly', 'true', 'bsprofile');
			}
			if ($this->params->get('profile-require_name1', 1) > 0) {
				$form->setFieldAttribute('name1', 'required', $this->params->get('profile-require_name1') == 2, 'bsprofile');
			}
			else {
				$form->removeField('name1', 'bsprofile');
			}

			// Toggle whether the name2 field is required.
			if ($this->params->get('profile-require_name2', 1) == 3) {
				$form->setFieldAttribute('name2', 'readonly', 'true', 'bsprofile');
			}
			if ($this->params->get('profile-require_name2', 1) > 0) {
				$form->setFieldAttribute('name2', 'required', $this->params->get('profile-require_name2') == 2, 'bsprofile');
			}
			else {
				$form->removeField('name2', 'bsprofile');
			}

			// Toggle whether the company field is required.
			if ($this->params->get('profile-require_company', 1) == 3) {
				$form->setFieldAttribute('company', 'readonly', 'true', 'bsprofile');
			}
			if ($this->params->get('profile-require_company', 1) > 0) {
				$form->setFieldAttribute('company', 'required', $this->params->get('profile-require_company') == 2, 'bsprofile');
			}
			else {
				$form->removeField('company', 'bsprofile');
			}

			// Toggle whether the section field is required.
			if ($this->params->get('profile-require_divcode', 1) == 3) {
				$form->setFieldAttribute('divcode', 'readonly', 'true', 'bsprofile');
			}
			if ($this->params->get('profile-require_divcode', 1) > 0) {
				$form->setFieldAttribute('divcode', 'required', $this->params->get('profile-require_divcode') == 2, 'bsprofile');
			}
			else {
				$form->removeField('divcode', 'bsprofile');
			}

			// Toggle whether the employeeno field is required.
			if ($this->params->get('profile-require_employeeno', 1) == 3) {
				$form->setFieldAttribute('employeeno', 'readonly', 'true', 'bsprofile');
			}
			if ($this->params->get('profile-require_employeeno', 1) > 0) {
				$form->setFieldAttribute('employeeno', 'required', $this->params->get('profile-require_employeeno') == 2, 'bsprofile');
			}
			else {
				$form->removeField('employeeno', 'bsprofile');
			}

			// Toggle whether the address1 field is required.
			if ($this->params->get('profile-require_address', 1) == 3) {
				$form->setFieldAttribute('address', 'readonly', 'true', 'bsprofile');
			}
			if ($this->params->get('profile-require_address', 1) > 0) {
				$form->setFieldAttribute('address', 'required', $this->params->get('profile-require_address') == 2, 'bsprofile');
			}
			else {
				$form->removeField('address', 'bsprofile');
			}

			// Toggle whether the state field is required.
			if ($this->params->get('profile-require_state', 1) == 3) {
				$form->setFieldAttribute('state', 'readonly', 'true', 'bsprofile');
			}
			if ($this->params->get('profile-require_state', 1) > 0) {
				$form->setFieldAttribute('state', 'required', $this->params->get('profile-require_state') == 2, 'bsprofile');
			}
			else {
				$form->removeField('state', 'bsprofile');
			}
			// Toggle whether the city field is required.
			if ($this->params->get('profile-require_city', 1) == 3) {
				$form->setFieldAttribute('city', 'readonly', 'true', 'bsprofile');
			}
			if ($this->params->get('profile-require_city', 1) > 0) {
				$form->setFieldAttribute('city', 'required', $this->params->get('profile-require_city') == 2, 'bsprofile');
			}
			else {
				$form->removeField('city', 'bsprofile');
			}

			// Toggle whether the country field is required.
			if ($this->params->get('profile-require_country', 1) == 3) {
				$form->setFieldAttribute('country', 'readonly', 'true', 'bsprofile');
			}
			if ($this->params->get('profile-require_country', 1) > 0) {
				$form->setFieldAttribute('country', 'required', $this->params->get('profile-require_country') == 2, 'bsprofile');
			}
			else {
				$form->removeField('country', 'bsprofile');
			}

			// Toggle whether the postal code field is required.
			if ($this->params->get('profile-require_zipcode', 1) == 3) {
				$form->setFieldAttribute('zipcode', 'readonly', 'true', 'bsprofile');
			}
			if ($this->params->get('profile-require_zipcode', 1) > 0) {
				$form->setFieldAttribute('zipcode', 'required', $this->params->get('profile-require_zipcode') == 2, 'bsprofile');
			}
			else {
				$form->removeField('zipcode', 'bsprofile');
			}

			// Toggle whether the phone field is required.
			if ($this->params->get('profile-require_teleno', 1) == 3) {
				$form->setFieldAttribute('teleno', 'readonly', 'true', 'bsprofile');
			}
			if ($this->params->get('profile-require_teleno', 1) > 0) {
				$form->setFieldAttribute('teleno', 'required', $this->params->get('profile-require_teleno') == 2, 'bsprofile');
			}
			else {
				$form->removeField('teleno', 'bsprofile');
			}

			// Toggle whether the website field is required.
			if ($this->params->get('profile-require_company_hp', 1) == 3) {
				$form->setFieldAttribute('company_hp', 'readonly', 'true', 'bsprofile');
			}
			if ($this->params->get('profile-require_company_hp', 1) > 0) {
				$form->setFieldAttribute('company_hp', 'required', $this->params->get('profile-require_company_hp') == 2, 'bsprofile');
			}
			else {
				$form->removeField('company_hp', 'bsprofile');
			}

			// Toggle whether the note field is required.
			if ($this->params->get('profile-require_note', 1) == 3) {
				$form->setFieldAttribute('note', 'readonly', 'true', 'bsprofile');
			}
			if ($this->params->get('profile-require_note', 1) > 0) {
				$form->setFieldAttribute('note', 'required', $this->params->get('profile-require_note') == 2, 'bsprofile');
			}
			else {
				$form->removeField('note', 'bsprofile');
			}
		}

		return true;
	}

	function onUserAfterSave($data, $isNew, $result, $error)
	{
		$userId	= JArrayHelper::getValue($data, 'id', 0, 'int');

		if ($userId && $result && isset($data['bsprofile']) && (count($data['bsprofile'])))
		{
			try
			{
				$db = JFactory::getDbo();
				$db->setQuery(
					'DELETE FROM #__user_profiles WHERE user_id = '.$userId .
					" AND profile_key LIKE 'bsprofile.%'"
				);

				if (!$db->query()) {
					throw new Exception($db->getErrorMsg());
				}

				$tuples = array();
				$order	= 1;

				foreach ($data['bsprofile'] as $k => $v)
				{
					$tuples[] = '('.$userId.', '.$db->quote('bsprofile.'.$k).', '.$db->quote($v).', '.$order++.')';
				}

				$db->setQuery('INSERT INTO #__user_profiles VALUES '.implode(', ', $tuples));

				if (!$db->query()) {
					throw new Exception($db->getErrorMsg());
				}

			}
			catch (JException $e)
			{
				$this->_subject->setError($e->getMessage());
				return false;
			}
		}

		return true;
	}

	/**
	 * Remove all user profile information for the given user ID
	 *
	 * Method is called after user data is deleted from the database
	 *
	 * @param	array		$user		Holds the user data
	 * @param	boolean		$success	True if user was succesfully stored in the database
	 * @param	string		$msg		Message
	 */
	function onUserAfterDelete($user, $success, $msg)
	{
		if (!$success) {
			return false;
		}

		$userId	= JArrayHelper::getValue($user, 'id', 0, 'int');

		if ($userId)
		{
			try
			{
				$db = JFactory::getDbo();
				$db->setQuery(
					'DELETE FROM #__user_profiles WHERE user_id = '.$userId .
					" AND profile_key LIKE 'bsprofile.%'"
				);

				if (!$db->query()) {
					throw new Exception($db->getErrorMsg());
				}
			}
			catch (JException $e)
			{
				$this->_subject->setError($e->getMessage());
				return false;
			}
		}

		return true;
	}
	function onLdapUserAfterSave($data, $isNew, $result, $error)
	{
		return self::onUserAfterSave($data, $isNew, $result, $error);
	}
}
