<?php
/**
 * @package   Mr.Cat.
 * @author    Marcos Rego https://marcosrego.com
 * @copyright Copyright (C) 2019
 * @license   GNU/GPLv2 and later
 *
 * http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die;
/**
 * Mr.Cat. package installer script.
 */
class Pkg_MrCatInstallerScript
{
    /**
     * List of supported versions. Newest version first!
     * @var array
     */
    protected $versions = array(
        'PHP' => array (
            '5.4' => '5.4.0',
            '0' => '7.0.32' // Preferred version
        ),
        'Joomla!' => array (
            '3.7' => '3.7.1',
            '0' => '3.8.13' // Preferred version
        )
    );
    /**
     * List of required PHP extensions.
     * @var array
     */
    protected $extensions = array ('pcre');
    public function install($parent)
    {
        return true;
    }
    public function discover_install($parent)
    {
        return self::install($parent);
    }
    public function update($parent)
    {
        return self::install($parent);
    }
    public function uninstall($parent)
    {
        $manifestFile = JPATH_MANIFESTS . '/packages/pkg_mrcat.xml';
        if (is_file($manifestFile)) {
            $manifest = simplexml_load_file($manifestFile);
            $this->prepareExtensions($manifest, 0);
        }
        return true;
    }
    public function preflight($type, $parent)
    {
        /** @var JInstallerAdapter $parent */
        $manifest = $parent->getManifest();
        // Prevent installation if requirements are not met.
        $errors = $this->checkRequirements($manifest->version);
        if ($errors) {
            $app = JFactory::getApplication();
            foreach ($errors as $error) {
                $app->enqueueMessage($error, 'error');
            }
            return false;
        }
        // Disable and unlock existing extensions to prevent fatal errors (in the site).
        $this->prepareExtensions($manifest, 0);
        return true;
    }
    public function postflight($type, $parent)
    {
        // Clear Joomla system cache.
        /** @var JCache|JCacheController $cache */
        $cache = JFactory::getCache();
        $cache->clean('_system');
        // Make sure that PHP has the latest data of the files.
        clearstatcache();
        // Remove all compiled files from opcode cache.
        if (function_exists('opcache_reset')) {
            @opcache_reset();
        } elseif (function_exists('apc_clear_cache')) {
            @apc_clear_cache();
        }
        if ($type === 'uninstall') {
            return true;
        }
        /** @var JInstallerAdapter $parent */
        $manifest = $parent->getManifest();
        // Enable and lock extensions to prevent uninstalling them individually.
        $this->prepareExtensions($manifest, 1);

        $rootPath = JPATH_ROOT;
				$dir_name = $rootPath.'/templates/mrdev';
				$xmlfile = $dir_name.'/index.xml';
				$xmlcontents = '<?xml version="1.0" encoding="UTF-8"?>
				<!-- To begin your first theme start by changing all "-1" below with your theme name. Then create a folder with the same name under "templates/mrdev/" and a "index.php" file inside it. Check Mr.Dev. documentation to know more. -->
								<form>
									<field 
										name="customtheme"
										type="folderlist"
										default="-1"
										label="Custom theme"
										directory="templates/mrdev"
										exclude=""
										stripext=""
										hide_default="true"
										description="The custom themes are folders located in /templates/mrdev/ with index.php files inside.  Feel free to create and edit them. Check Mr.Dev. documentation to know more."
									/>
									<field name="-1Layout" type="list" class="mrwid-layouts" label="Layout" default="None"  filter="safehtml" showon="customtheme:-1">
											<option value="None" id="None">None</option>
											<option value="Custom" id="Custom">Custom</option>
									</field>
									<field showon="customtheme:-1[AND]-1Layout:Custom" name="-1LayoutOptions" type="groupedlist" class="mrwid-checkbox" label="Customize" multiple="true" filter="safehtml">
											<group label="None">
												<option value="None">None</option>
											</group>
									</field>
									<field showon="customtheme:-1[AND]-1Layout:None" name="option1overriden" type="note" class="alert  alert-info" label="Option overriden" description="You can describe in here if the current theme / layout forces another option. Customize or change layout if you want your changes on that option to take effect." />
								</form>';
				if (!is_dir($dir_name)) {
					mkdir($dir_name);
					file_put_contents($xmlfile, $xmlcontents);
				} else if(!is_file($xmlfile)){
					file_put_contents($xmlfile, $xmlcontents);
				}
        return true;
    }
    // Internal functions
    protected function prepareExtensions($manifest, $state = 1)
    {
        foreach ($manifest->files->children() as $file) {
            $attributes = $file->attributes();
            $search = array('type' => (string) $attributes->type, 'element' => (string) $attributes->id);
            $clientName = (string) $attributes->client;
            if (!empty($clientName)) {
                $client = JApplicationHelper::getClientInfo($clientName, true);
                $search +=  array('client_id' => $client->id);
            }
            $group = (string) $attributes->group;
            if (!empty($group)) {
                $search +=  array('folder' => $group);
            }
            $extension = JTable::getInstance('extension');
            if (!$extension->load($search)) {
                continue;
            }
            // Joomla 3.7 added a new package protection feature: only use individual protection in older versions.
            $extension->protected = version_compare(JVERSION, '3.7', '<') ? $state : 0;
            if (isset($attributes->enabled)) {
                $extension->enabled = $state ? (int) $attributes->enabled : 0;
            }
            $extension->store();
        }
    }
    protected function addParam($string, array $options)
    {
        $items = array_flip(explode(',', $string)) + array_flip($options);
        return implode(',', array_keys($items));
    }
    protected function checkRequirements($mrcatVersion)
    {
        $results = array();
        $this->checkVersion($results, 'PHP', phpversion());
        $this->checkVersion($results, 'Joomla!', JVERSION);
        $this->checkExtensions($results, $this->extensions);
        return $results;
    }
    protected function checkVersion(array &$results, $name, $version)
    {
        $major = $minor = 0;
        foreach ($this->versions[$name] as $major => $minor) {
            if (!$major || version_compare($version, $major, '<')) {
                continue;
            }
            if (version_compare($version, $minor, '>=')) {
                return;
            }
            break;
        }
        if (!$major) {
            $minor = reset($this->versions[$name]);
        }
        $recommended = end($this->versions[$name]);
        if (version_compare($recommended, $minor, '>')) {
            $results[] = sprintf(
                '%s %s is not supported. Minimum required version is %s %s, but it is highly recommended to use %s %s or later version.',
                $name,
                $version,
                $name,
                $minor,
                $name,
                $recommended
            );
        } else {
            $results[] = sprintf(
                '%s %s is not supported. Please update to %s %s or later version.',
                $name,
                $version,
                $name,
                $minor
            );
        }
    }
    protected function checkExtensions(array &$results, $extensions)
    {
        foreach ($extensions as $name) {
            if (!extension_loaded($name)) {
                $results[] = sprintf("Required PHP extension '%s' is missing. Please install it into your system.", $name);
            }
        }
    }
}