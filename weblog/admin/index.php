<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author     XOOPS Development Team
 * @version    $Id $
 */


require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/include/cp_header.php';
include_once dirname(__FILE__) . '/admin_header.php';

xoops_cp_header();

	$indexAdmin = new ModuleAdmin();

//------ check directories ---------------
include_once dirname(dirname(__FILE__)) . '/include/directorychecker.php';
$indexAdmin->addConfigBoxLine('');
$redirectFile = $_SERVER['PHP_SELF'];

$languageConstants = array(_AM_WEBLOG_AVAILABLE2,_AM_WEBLOG_NOTAVAILABLE2, _AM_WEBLOG_CREATETHEDIR2, _AM_WEBLOG_NOTWRITABLE2, _AM_WEBLOG_SETMPERM2, _AM_WEBLOG_DIRCREATED2,_AM_WEBLOG_DIRNOTCREATED2,_AM_WEBLOG_PERMSET2,_AM_WEBLOG_PERMNOTSET2);

//        foreach (array_keys($folder) as $i) {
//            $indexAdmin->addConfigBoxLine(DirectoryChecker::getDirectoryStatus($folder[$i],0755,$languageConstants,$redirectFile));
//      		}



$path =  XOOPS_ROOT_PATH . $xoopsModuleConfig['weblog_myalbum_photospath'] . '/';
$indexAdmin->addConfigBoxLine(DirectoryChecker::getDirectoryStatus($path ,0777,$languageConstants,$redirectFile));

$path = XOOPS_ROOT_PATH . $xoopsModuleConfig['weblog_myalbum_thumbspath'] . '/';
$indexAdmin->addConfigBoxLine(DirectoryChecker::getDirectoryStatus($path ,0777,$languageConstants,$redirectFile));


/*
//$path = XOOPS_ROOT_PATH . '/' . $xoopsModuleConfig['catimage'] . '/';
$indexAdmin->addConfigBoxLine(DirectoryChecker::getDirectoryStatus(WEBLOG_PICTURES_PATH,0777,$languageConstants,$redirectFile));


//$path = XOOPS_ROOT_PATH . '/' . $xoopsModuleConfig['mainimagedir'] . '/';
$indexAdmin->addConfigBoxLine(DirectoryChecker::getDirectoryStatus(WEBLOG_CSV_PATH,0775,$languageConstants,$redirectFile));

//$path = XOOPS_ROOT_PATH . '/' . $xoopsModuleConfig['catimage'] . '/';
$indexAdmin->addConfigBoxLine(DirectoryChecker::getDirectoryStatus(WEBLOG_CACHE_PATH,0777,$languageConstants,$redirectFile));


//$path = XOOPS_ROOT_PATH . '/' . $xoopsModuleConfig['mainimagedir'] . '/';
$indexAdmin->addConfigBoxLine(DirectoryChecker::getDirectoryStatus(WEBLOG_TEXT_PATH,0777,$languageConstants,$redirectFile));

//echo $indexAdmin->addNavigation('index.php');
//echo $indexAdmin->renderIndex();
//echo wfd_serverstats();
//---------------------------
*/


		echo $indexAdmin->addNavigation('index.php');
		echo $indexAdmin->renderIndex();



include "admin_footer.php";
