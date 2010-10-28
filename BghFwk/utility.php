<?php
declare(ENCODING = 'utf-8');

/*                                                                        *
 * This script belongs to the FLOW3 package "BghFwk"                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License as published by the Free   *
 * Software Foundation, either version 3 of the License, or (at your      *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        *
 * You should have received a copy of the GNU General Public License      *
 * along with the script.                                                 *
 * If not, see http://www.gnu.org/licenses/gpl.html                       *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * special autoloader to hack the package manager
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
/*function bghfwk_autoload($clazz)
{
    if ($clazz == 'BghFwk\Package\PackageManager')
    {
        require_once(__DIR__.'/PackageManager.php');
        return true;
    }
    return false;
}

spl_autoload_register('bghfwk_autoload');*/

$wsroot = getenv('FLOW3_WORKSPACE_ROOT');
$prjname = getenv('FLOW3_PROJECT_NAME');
$prjdeps = getenv('FLOW3_PROJECT_DEPENDENCIES');

$pkgdirs = array("$wsroot/$prjname/Packages");
foreach (explode(";", $prjdeps) as $prjdep)
{
    if (!empty($prjdep)) $pkgdirs[] = "$wsroot/$prjdep/Packages";
}

define('FLOW3_PATH_CONFIGURATION', "$wsroot/$prjname/Configuration/");
define('FLOW3_PATH_DATA', "$wsroot/$prjname/Data/");
define('FLOW3_PATH_PACKAGES_ADDITIONAL', implode(';', $pkgdirs));

// fixes a problem that BaseTestClass does not find vfs...
set_include_path(
    dirname(__DIR__)."/Packages/Framework/PHPUnit/Resources/PHP".
    PATH_SEPARATOR. get_include_path());

unset($wsroot);
unset($prjname);
unset($prjdeps);
unset($pkgdirs);
