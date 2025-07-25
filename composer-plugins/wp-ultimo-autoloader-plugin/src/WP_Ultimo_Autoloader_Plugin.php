<?php
/**
 * WP Ultimo Autoloader Plugin
 *
 * Composer plugin that modifies the Jetpack autoloader classmap to prevent
 * autoloading when WP_ULTIMO_PLUGIN_FILE is defined but doesn't end with 'multisite-ultimate.php'.
 *
 * @package WP_Ultimo
 */

namespace WP_Ultimo\Composer;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;

/**
 * Class WP_Ultimo_Autoloader_Plugin
 */
class WP_Ultimo_Autoloader_Plugin implements PluginInterface, EventSubscriberInterface {

	/**
	 * IO object.
	 *
	 * @var IOInterface
	 */
	private $io;

	/**
	 * Composer object.
	 *
	 * @var Composer
	 */
	private $composer;

	/**
	 * Activate plugin.
	 *
	 * @param Composer    $composer Composer object.
	 * @param IOInterface $io IO object.
	 */
	public function activate( Composer $composer, IOInterface $io ) {
		$this->composer = $composer;
		$this->io       = $io;
	}

	/**
	 * Deactivate plugin.
	 *
	 * @param Composer    $composer Composer object.
	 * @param IOInterface $io IO object.
	 */
	public function deactivate( Composer $composer, IOInterface $io ) {
		// Intentionally left empty.
	}

	/**
	 * Uninstall plugin.
	 *
	 * @param Composer    $composer Composer object.
	 * @param IOInterface $io IO object.
	 */
	public function uninstall( Composer $composer, IOInterface $io ) {
		// Intentionally left empty.
	}

	/**
	 * Get subscribed events.
	 *
	 * @return array
	 */
	public static function getSubscribedEvents() {
		return array(
			ScriptEvents::POST_AUTOLOAD_DUMP => array(
				array( 'modifyJetpackAutoloader', -10 ), // Run after jetpack autoloader (priority -10)
			),
		);
	}

	/**
	 * Modify the jetpack autoloader classmap to include WP_ULTIMO_PLUGIN_FILE check.
	 *
	 * @param Event $event Script event object.
	 */
	public function modifyJetpackAutoloader( Event $event ) {
		$config     = $this->composer->getConfig();
		$vendorPath = $config->get( 'vendor-dir' );
		$classmapPath = $vendorPath . '/composer/jetpack_autoload_classmap.php';

		if ( ! file_exists( $classmapPath ) ) {
			return;
		}

		$content = file_get_contents( $classmapPath );

		// Check if our code is already present
		if ( strpos( $content, "defined('WP_ULTIMO_PLUGIN_FILE')" ) !== false ) {
			return;
		}

		// Find the opening PHP tag and add our check right after it
		$search = "<?php\n\n// This file `jetpack_autoload_classmap.php` was auto generated by automattic/jetpack-autoloader.";
		$replacement = "<?php\n\n// This file `jetpack_autoload_classmap.php` was auto generated by automattic/jetpack-autoloader.\nif (defined('WP_ULTIMO_PLUGIN_FILE') && substr(WP_ULTIMO_PLUGIN_FILE, -22) !== 'multisite-ultimate.php') {\n\treturn;\n}";

		$newContent = str_replace( $search, $replacement, $content );

		if ( $newContent !== $content ) {
			file_put_contents( $classmapPath, $newContent );
			$this->io->write( '<info>Modified jetpack_autoload_classmap.php with WP_ULTIMO_PLUGIN_FILE check</info>' );
		}
	}
}