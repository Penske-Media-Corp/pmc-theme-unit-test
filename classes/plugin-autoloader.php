<?php
/**
 * Autoloader for PHP classes inside PMC Plugins
 *
 * @author Amit Gupta <agupta@pmc.com>
 * @since 2015-05-12
 */

namespace PMC\Theme_Unit_Test;


class Unit_Test_Autoloader {

	public static function autoload_resource( $resource = '' ) {
		$namespace_root = 'PMC\\Theme_Unit_Test\\';
		$resource       = trim( $resource, '\\' );
		if ( empty( $resource ) || strpos( $resource, '\\' ) === false || strpos( $resource, $namespace_root ) !== 0 ) {
			//not our namespace, bail out
			return;
		}
		$path = explode( '\\', str_replace( '_', '-', $resource ) );

		if ( ! empty( $path[3] ) ) {
			$class_path = strtolower( $path[2] ) . '/' . strtolower( $path[3] );
		} else {
			$class_path = strtolower( $path[2] );
		}

		$resource_path = sprintf( '%s/classes/%s.php', untrailingslashit( dirname( __DIR__ ) ), $class_path );
		if ( file_exists( $resource_path ) && validate_file( $resource_path ) === 0 ) {
			require_once $resource_path;
		} else {

			$file_prefix = '';

			if ( strpos( $resource_path, 'traits' ) > 0 ) {
				$file_prefix = 'trait';
			} elseif ( strpos( $resource_path, 'interfaces' ) > 0 ) {
				$file_prefix = 'interface';
			} elseif ( strpos( $resource_path, 'classes' ) > 0 ) {  // this has to be the last
				$file_prefix = 'class';
			}

			if ( ! empty( $file_prefix ) ) {

				$resource_parts = explode( '/', $resource_path );

				$resource_parts[ count( $resource_parts ) - 1 ] = sprintf(
					'%s-%s',
					strtolower( $file_prefix ),
					$resource_parts[ count( $resource_parts ) - 1 ]
				);

				$resource_path = implode( '/', $resource_parts );

			}

			if ( file_exists( $resource_path ) && validate_file( $resource_path ) === 0 ) {
				require_once $resource_path;
			}

		}
	}
}

/**
 * Register autoloader
 */
spl_autoload_register( array( __NAMESPACE__ . '\Unit_Test_Autoloader', 'autoload_resource' ) );


//EOF
