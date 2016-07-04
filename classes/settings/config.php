<?php
namespace PMC\Theme_Unit_Test\Settings;

class Config {


	const REST_BASE_URL = 'https://public-api.wordpress.com/rest/v1.1/sites/';

	const REQUEST_TOKEN_URL = 'https://public-api.wordpress.com/oauth2/token';

	const AUTHORIZE_URL = 'https://public-api.wordpress.com/oauth2/authorize';

	const VALIDATE_TOKEN_URL = 'https://public-api.wordpress.com/oauth2/token-info';

	const REST_URL_HOST = 'public-api.wordpress.com';

	const COOKIE_DOMAIN = '.vip.local';

	const access_token_key = 'rest_api_access_token';
	const api_domain = 'rest_api_domain';
	const api_client_id = 'rest_api_client_id';
	const api_client_secret = 'rest_api_client_secret';
	const api_redirect_uri = 'rest_api_redirect_uri';
	const api_xmlrpc_username = 'xmlrpc_username';
	const api_xmlrpc_password = 'xmlrpc_password';
	const show_form = 'show_data_import_form';

	public static $all_routes = array(
		0 => array(
			'users' => array(
				'query_params' => array(
					'authors_only' => true,
				),
			),
		),
		1 => array(
			'menus' => array(
				'query_params' => array(),
			),
		),
		2 => array(
			'tags' => array(
				'query_params' => array(),
			),
		),
		3 => array(
			'categories' => array(
				'query_params' => array(),
			),
		),
	);

	public static $xmlrpc_routes = array( 'taxonomies', 'options' );

	public static $custom_posttypes = array( 'post', 'page' );

	public static $custom_taxonomies = array();

	public static $default_taxonomies = array( 'post_tag', 'category' );

}