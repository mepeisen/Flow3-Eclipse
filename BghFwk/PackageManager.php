<?php
declare(ENCODING = 'utf-8');
namespace F3\BghFwk\Package;

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
 * Package manager that allowes additional locations pf package directories
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class PackageManager extends \F3\FLOW3\Package\PackageManager
{

	/**
	 * Scans all directories in the packages directories for available packages.
	 * For each package a \F3\FLOW3\Package\ object is created and returned as
	 * an array.
	 * 
	 * some of the code was taken from flow3
	 *
	 * @return void
	 */
	protected function scanAvailablePackages()
	{
	    $this->packages = array('FLOW3' => $this->objectManager->create('F3\FLOW3\Package\Package', 'FLOW3', FLOW3_PATH_FLOW3));
		
		$this->scanPathForPackages(FLOW3_PATH_PACKAGES);
		if (defined('FLOW3_PATH_PACKAGES_ADDITIONAL'))
		{
		    foreach (explode(';', FLOW3_PATH_PACKAGES_ADDITIONAL) as $path)
		    {
		        $this->scanPathForPackages($path);
		    }
		}
		
		foreach (array_keys($this->packages) as $upperCamelCasedPackageKey)
		{
			$this->packageKeys[strtolower($upperCamelCasedPackageKey)] = $upperCamelCasedPackageKey;
		}
	}
	
	/**
	 * Scans a directory for packages.
	 * 
	 * some of the code was taken from flow3
	 * 
	 * @param string $path
	 * 
	 * @throws \F3\FLOW3\Package\Exception\DuplicatePackageException
	 */
	protected function scanPathForPackages($path)
	{
	    foreach (new \DirectoryIterator($path) as $parentFileInfo)
		{
			$parentFilename = $parentFileInfo->getFilename();
			if ($parentFilename[0] === '.' || !$parentFileInfo->isDir()) continue;

			foreach (new \DirectoryIterator($parentFileInfo->getPathname()) as $childFileInfo)
			{
				$childFilename = $childFileInfo->getFilename();
				if ($childFilename[0] !== '.' && $childFilename !== 'FLOW3')
				{
					$packagePath = \F3\FLOW3\Utility\Files::getUnixStylePath($childFileInfo->getPathName()) . '/';
					if (isset($this->packages[$childFilename]))
					{
						throw new \F3\FLOW3\Package\Exception\DuplicatePackageException('Detected a duplicate package, remove either "' . $this->packages[$childFilename]->getPackagePath() . '" or "' . $packagePath . '".', 1253716811);
					}
					$this->packages[$childFilename] = $this->objectManager->create('F3\FLOW3\Package\Package', $childFilename, $packagePath);
				}
			}
		}
	}
	
}
