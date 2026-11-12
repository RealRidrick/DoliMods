<?php
/* Copyright (C) 2026 Laurent Destailleur <eldy@users.sourceforge.net>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

/**
 * \file    autoblog/lib/autoblog.lib.php
 * \ingroup autoblog
 * \brief   Library files with common functions for AutoBlog
 */


/**
 * Prepare admin pages header
 *
 * @return	array<array{0:string,1:string,2:string}>	Array of tabs
 */
function autoblogAdminPrepareHead()
{
	global $langs, $conf;

	$langs->load("autoblog@autoblog");

	$h = 0;
	$head = array();

	$head[$h][0] = dol_buildpath("/autoblog/admin/setup.php", 1);
	$head[$h][1] = $langs->trans("Settings");
	$head[$h][2] = 'settings';
	$h++;

	$head[$h][0] = dol_buildpath("/autoblog/admin/about.php", 1);
	$head[$h][1] = $langs->trans("About");
	$head[$h][2] = 'about';
	$h++;

	complete_head_from_modules($conf, $langs, null, $head, $h, 'autoblog@autoblog');
	complete_head_from_modules($conf, $langs, null, $head, $h, 'autoblog@autoblog', 'remove');

	return $head;
}

/**
 * Prepare array of tabs for BlogTopic
 *
 * @param	BlogTopic	$object		BlogTopic
 * @return	array<array{0:string,1:string,2:string}>	Array of tabs
 */
function blogtopicPrepareHead($object)
{
	global $langs, $conf;

	$langs->load("autoblog@autoblog");

	$h = 0;
	$head = array();

	$head[$h][0] = dol_buildpath("/autoblog/blogtopic_card.php", 1).'?id='.((int) $object->id);
	$head[$h][1] = $langs->trans("Card");
	$head[$h][2] = 'card';
	$h++;

	complete_head_from_modules($conf, $langs, $object, $head, $h, 'blogtopic@autoblog');
	complete_head_from_modules($conf, $langs, $object, $head, $h, 'blogtopic@autoblog', 'remove');

	return $head;
}

/**
 * Fill the list of choices of the fields fk_website and lang of a BlogTopic with the websites and their languages
 *
 * @param	BlogTopic				$object		BlogTopic whose fields definition is completed
 * @param	array<int,Website>		$websites	Websites, as returned by Website::fetchAll()
 * @return	void
 */
function autoblogCompleteTopicFields($object, $websites)
{
	global $langs;

	$langs->load("languages");

	$websiteoptions = array();
	$langoptions = array();
	foreach ($websites as $website) {
		$websiteoptions[$website->id] = $website->ref;
		$websitelangs = array_merge(array((string) $website->lang), explode(',', (string) $website->otherlang));
		foreach ($websitelangs as $langcode) {
			$langcode = trim($langcode);
			if ($langcode === '' || isset($langoptions[$langcode])) {
				continue;
			}
			$langlabel = $langs->trans('Language_'.$langcode);
			$langoptions[$langcode] = ($langlabel != 'Language_'.$langcode ? $langlabel.' ('.$langcode.')' : $langcode);
		}
	}

	$object->fields['fk_website']['arrayofkeyval'] = $websiteoptions;
	$object->fields['lang']['arrayofkeyval'] = $langoptions;
}
