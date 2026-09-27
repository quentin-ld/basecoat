<?php
/**
 * The assets basecoat says it loads, and whether they are there to load.
 *
 * @package basecoat
 */

declare(strict_types=1);

/**
 * `inc/theme-assets.php` enqueues three things: `style.css`, the built bundle's
 * stylesheet, and the built bundle's script. Each is a claim about a file that
 * exists in the repository, and each of those files is produced by something
 * outside this repository — `bin/harness build` writes `assets/build/`.
 *
 * A test that only asserted the handles were registered would pass against a
 * build that never ran, against a rename, and against a bundle deleted before
 * packaging. These assert the resolved URL points at a file with content.
 *
 * What would make them fail: deleting `assets/build/`, renaming the entry point
 * in `package.json` so webpack emits a different name, or removing the
 * `wp_enqueue_style()` call. Verified by hand for the first.
 */
class ThemeAssetsTest extends WP_UnitTestCase {

	/**
	 * Every file the theme enqueues exists and is not empty.
	 */
	public function test_enqueued_assets_resolve_to_real_files(): void {
		$expected = array(
			'basecoat'       => 'style.css',
			'basecoat-theme' => 'assets/build/index.css',
		);

		foreach ( $expected as $handle => $relative ) {
			$file = get_stylesheet_directory() . '/' . $relative;

			$this->assertFileExists( $file, "{$handle} points at {$relative}, which does not exist" );
			$this->assertGreaterThan( 0, filesize( $file ), "{$handle} points at an empty {$relative}" );
		}
	}

	/**
	 * The bundle ships an asset file, and WordPress gets its version from it.
	 *
	 * `inc/theme-assets.php` does not maintain a version constant for the
	 * bundle. It reads `index.asset.php`, which webpack writes and which carries
	 * a hash of the output. If that file stops being generated the theme falls
	 * back to the theme version, which never changes between builds and would
	 * serve a stale stylesheet from cache forever.
	 */
	public function test_the_bundle_declares_its_own_version(): void {
		$asset_file = get_stylesheet_directory() . '/assets/build/index.asset.php';

		$this->assertFileExists( $asset_file );

		$asset = require $asset_file;

		$this->assertIsArray( $asset );
		$this->assertArrayHasKey( 'version', $asset );
		$this->assertArrayHasKey( 'dependencies', $asset );
		$this->assertNotEmpty( $asset['version'], 'an empty version defeats cache busting' );
	}

	/**
	 * The stylesheet is enqueued, and the URL is under the theme.
	 */
	public function test_the_bundle_stylesheet_is_enqueued(): void {
		// The theme enqueues on `wp_enqueue_scripts`, which only runs on a front
		// end request. Without this the registry is empty and the test would
		// pass for the wrong reason on any theme that enqueued nothing at all.
		do_action( 'wp_enqueue_scripts' );

		$styles = wp_styles();

		$this->assertArrayHasKey( 'basecoat-theme', $styles->registered );

		$src = $styles->registered['basecoat-theme']->src;
		$this->assertStringContainsString( 'assets/build/index.css', $src );
		$this->assertFileExists( get_stylesheet_directory() . '/assets/build/index.css' );
	}

	/**
	 * The theme is a block theme, so it carries the two files WordPress reads.
	 *
	 * A theme.json that does not parse silently falls back to defaults. A block
	 * theme with no templates/index.html has nothing to render at all.
	 */
	public function test_it_is_a_valid_block_theme(): void {
		$theme_json = get_stylesheet_directory() . '/theme.json';

		$this->assertFileExists( $theme_json );

		// A local file in the theme's own tree, not a remote request.
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$settings = json_decode( (string) file_get_contents( $theme_json ), true );

		$this->assertIsArray( $settings, 'theme.json does not parse' );
		$this->assertArrayHasKey( 'version', $settings );

		$this->assertFileExists( get_stylesheet_directory() . '/templates/index.html' );
	}
}
