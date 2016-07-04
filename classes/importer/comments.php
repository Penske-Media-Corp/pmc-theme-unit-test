<?php
namespace PMC\Theme_Unit_Test\Importer;

use PMC\Theme_Unit_Test\PMC_Singleton;
use PMC\Theme_Unit_Test\Rest_API\O_Auth;


class Comments extends PMC_Singleton {

	/**
	 * Insert a new Comment to the DB.
	 *
	 * @since 2015-07-13
	 *
	 * @version 2015-07-13 Archana Mandhare PPT-5077
	 *
	 * @param  @type array   $comment_json   containing Comment data
	 * @param  @type int $post_id Post Id this comment is associated with
	 *
	 *
	 * @return int|WP_Error The Comment Id on success. The value 0 or WP_Error on failure.
	 *
	 */
	public function save_comment( $comment_json, $post_id ) {

		$time = date( '[d/M/Y:H:i:s]' );

		try {

			if ( empty( $comment_json ) ) {
				error_log( $time . ' No Comment data provided for post ' . PHP_EOL, 3, PMC_THEME_UNIT_TEST_ERROR_LOG_FILE );

				return false;
			}

			$comment_data = array(
				'comment_post_ID'      => $post_id,
				'comment_author'       => $comment_json['author']['name'],
				'comment_author_email' => $comment_json['author']['email'],
				'comment_author_url'   => $comment_json['author']['URL'],
				'comment_content'      => $comment_json['content'],
				'comment_type'         => $comment_json['type'],
				'comment_parent'       => ( false === $comment_json['parent'] ) ? 0 : $comment_json['parent'],
				'comment_status'       => $comment_json['status'],
				'comment_date'         => $comment_json['date'],
				'user_id'              => get_current_user_id(),
			);

			$comment_id = wp_insert_comment( $comment_data );

			if ( is_wp_error( $comment_id ) ) {

				error_log( $time . ' -- ' . $comment_id->get_error_message() . PHP_EOL, 3, PMC_THEME_UNIT_TEST_ERROR_LOG_FILE );

				return $comment_id;
			} else {

				error_log( "{$time} -- Comment from author **-- {$comment_json['author']['name'] } --** added with ID = {$comment_id}" . PHP_EOL, 3, PMC_THEME_UNIT_TEST_IMPORT_LOG_FILE );

				return $comment_id;
			}
		} catch ( \Exception $e ) {

			error_log( $e->getMessage() . PHP_EOL, 3, PMC_THEME_UNIT_TEST_ERROR_LOG_FILE );

			return false;
		}

	}


	/**
	 * Assemble user data from API and inserts post comments.
	 *
	 * @since 2015-07-13
	 *
	 * @version 2015-07-13 Archana Mandhare PPT-5077
	 *
	 * @param array json_decode() array of comments object
	 * @param int $post_id
	 *
	 * @return array of Comments ids on success.
	 * @todo - Find ways to insert comments as an object mapped from json_decode
	 *
	 */
	public function instant_comments_import( $json_data, $post_id ) {

		$comments_ids = array();

		if ( empty( $json_data ) || ! is_array( $json_data ) ) {
			return $comments_ids;
		}

		foreach ( $json_data as $comment_data ) {

			$comments_ids[] = $this->save_comment( $comment_data, $post_id );

		}

		return $comments_ids;
	}

	/**
	 * Route the call to the import function for this class
	 *
	 * @since 2015-07-15
	 *
	 * @version 2015-07-15 Archana Mandhare PPT-5077
	 *
	 * @param array $api_data data returned from the REST API that needs to be imported
	 * @param int $post_id
	 *
	 * @return array
	 *
	 */
	public function call_import_route( $api_data, $post_id ) {

		return $this->instant_comments_import( $api_data, $post_id );

	}

	/**
	 * Access endpoints to make call to REST API
	 *
	 * This method will make a call to the public REST API
	 * and fetch data from live site and save to the current site DB.
	 *
	 * @since 2015-07-06
	 *
	 * @version 2015-07-06 Archana Mandhare PPT-5077
	 *
	 *
	 * @param int $old_post_id
	 * @param int $new_post_id
	 *
	 */
	public function call_rest_api_route( $old_post_id, $new_post_id ) {

		//Fetch comment for each post and save to the DB
		$route = "posts/{$old_post_id}/replies";

		$comments = O_Auth::get_instance()->access_endpoint( $route, array(), 'comments', false );

		if ( ! empty( $comments ) ) {

			$this->call_import_route( $comments, $new_post_id );

		}

	}
}