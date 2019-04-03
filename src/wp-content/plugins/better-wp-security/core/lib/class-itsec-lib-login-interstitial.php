<?php

/**
 * Class ITSEC_Lib_Login_Interstitial
 */
class ITSEC_Lib_Login_Interstitial {

	const SHOW_AFTER_LOGIN = 'itsec_after_interstitial';
	const META_KEY = '_itsec_login_interstitial_token';
	const AJAX = 'itsec-login-interstitial-ajax';

	private $registered = array();

	/** @var WP_Error */
	private $error;

	/** @var string */
	private $session_token;

	/**
	 * Initialize the module.
	 *
	 * This registers hooks and filters.
	 */
	public function run() {

		/**
		 * Fires when the Login Interstitial framework is initialized.
		 *
		 * @param ITSEC_Lib_Login_Interstitial
		 */
		do_action( 'itsec_login_interstitial_init', $this );

		if ( ! $this->registered ) {
			return;
		}

		$this->registered = wp_list_sort( $this->registered, 'priority', 'ASC', true );

		add_action( 'wp_login', array( $this, 'wp_login' ), 10, 2 );
		add_action( 'wp_login_errors', array( $this, 'handle_token_expired' ) );
		add_action( 'login_init', array( $this, 'force_interstitial' ) );
		add_action( 'login_form', array( $this, 'ferry_after_login' ) );
		add_filter( 'auth_cookie', array( $this, 'capture_session_token' ), 10, 5 );

		$added_ajax = false;

		foreach ( $this->registered as $id => $opts ) {
			if ( ! empty( $opts['submit'] ) ) {
				add_action( "login_form_itsec-{$id}", array( $this, 'submit' ), 9 );
			}

			add_action( "login_form_itsec-{$id}", array( $this, 'display' ) );

			if ( ! $added_ajax && ! empty( $opts['ajax_handler'] ) ) {
				add_action( 'wp_ajax_' . self::AJAX, array( $this, 'ajax_handler' ) );
				add_action( 'wp_ajax_nopriv_' . self::AJAX, array( $this, 'ajax_handler' ) );

				$added_ajax = true;
			}
		}
	}

	/**
	 * Register an interstitial.
	 *
	 * @api
	 *
	 * @param string   $action
	 * @param callable $render
	 * @param array    $opts
	 *
	 * @return bool
	 */
	public function register( $action, $render, $opts ) {
		$opts = wp_parse_args( $opts, array(
			'force_completion' => true, // Will logout the user's session before displaying the interstitial.
			'show_to_user'     => true, // Boolean or callable.
			'wp_login_only'    => false, // Only show the interstitial if the login form is submitted from wp-login.php,
			'submit'           => false, // Callable called with user when submitting the form.
			'info_message'     => false,
			'after_submit'     => false,
			'ajax_handler'     => false,
			'priority'         => 5,
		) );

		$opts['render'] = $render;

		if ( ! is_bool( $opts['show_to_user'] ) && ! is_callable( $opts['show_to_user'] ) ) {
			return false;
		}

		if ( ! is_bool( $opts['force_completion'] ) && ! is_callable( $opts['force_completion'] ) ) {
			return false;
		}

		$this->registered[ $action ] = $opts;

		return true;
	}

	/**
	 * Show the interstitial.
	 *
	 * @api
	 *
	 * @param string  $action
	 * @param WP_User $user
	 *
	 * @return void
	 */
	public function show_interstitial( $action, $user ) {

		if ( ! isset( $this->registered[ $action ] ) ) {
			return;
		}

		$opts = $this->registered[ $action ];

		if ( $this->result( $opts['force_completion'], array( $user ) ) ) {
			$this->destroy_session( $user );
			$token = $this->set_token( $user );
		} else {
			$token = null;
		}

		$this->login_html( $user, $action, $token );
		die;
	}

	/**
	 * During the login process, check if we have any interstitials to display, and display them.
	 *
	 * @param string  $username
	 * @param WP_User $user
	 */
	public function wp_login( $username, $user = null ) {
		$user = $user ? $user : wp_get_current_user();

		if ( ! $user || ! $user->exists() ) {
			return;
		}

		if ( isset( $_REQUEST[ self::SHOW_AFTER_LOGIN ] ) ) {

			$action = $_REQUEST[ self::SHOW_AFTER_LOGIN ];

			if ( isset( $this->registered[ $action ] ) && $this->is_interstitial_applicable( $action, $user ) ) {
				$this->show_interstitial( $action, $user );
			}
		}

		foreach ( $this->get_applicable_interstitials( $user ) as $action => $opts ) {
			$this->show_interstitial( $action, $user );
		}
	}

	/**
	 * Add a message that the interstitial expired.
	 *
	 * @param WP_Error $errors
	 *
	 * @return WP_Error
	 */
	public function handle_token_expired( $errors ) {

		if ( ! empty( $_GET['itsec_interstitial_expired'] ) ) {
			$errors->add(
				'itsec-login-interstitial-invalid-token',
				esc_html__( 'Sorry, this request has expired. Please log in again.', 'better-wp-security' )
			);
		}

		return $errors;
	}

	/**
	 * Force the requested interstitial to be displayed if the user is already logged-in.
	 */
	public function force_interstitial() {

		if ( empty( $_REQUEST[ self::SHOW_AFTER_LOGIN ] ) || ! isset( $this->registered[ $_REQUEST[ self::SHOW_AFTER_LOGIN ] ] ) ) {
			return;
		}

		$action = $_REQUEST[ self::SHOW_AFTER_LOGIN ];

		if ( 'POST' === $_SERVER['REQUEST_METHOD'] && ! empty( $_POST['action'] ) && "itsec-{$action}" === $_POST['action'] ) {
			return;
		}

		if ( ! is_user_logged_in() ) {
			return;
		}

		$user = wp_get_current_user();

		if ( ! $this->result( $this->registered[ $action ]['show_to_user'], array( $user, true ) ) ) {
			wp_safe_redirect( admin_url() );
			die;
		}

		$this->show_interstitial( $action, $user );
	}

	/**
	 * Ferry the after login interstitial query var into the form.
	 */
	public function ferry_after_login() {
		if ( ! empty( $_REQUEST[ self::SHOW_AFTER_LOGIN ] ) && isset( $this->registered[ $_REQUEST[ self::SHOW_AFTER_LOGIN ] ] ) ) {
			echo '<input type="hidden" name="' . esc_attr( self::SHOW_AFTER_LOGIN ) . '" value="' . esc_attr( $_REQUEST[ self::SHOW_AFTER_LOGIN ] ) . '">';
		}
	}

	/**
	 * Capture the session token to log out the user.
	 *
	 * @param string $cookie
	 * @param int    $user_id
	 * @param int    $expiration
	 * @param string $scheme
	 * @param string $token
	 *
	 * @return string
	 */
	public function capture_session_token( $cookie, $user_id, $expiration, $scheme, $token ) {
		$this->session_token = $token;

		return $cookie;
	}

	/**
	 * Handle submitting the interstitial form.
	 */
	public function submit() {
		$action = substr( current_action(), strlen( 'login_form_itsec-' ) );

		if ( empty( $this->registered[ $action ]['submit'] ) ) {
			return;
		}

		if ( 'POST' !== $_SERVER['REQUEST_METHOD'] || empty( $_POST['action'] ) || "itsec-{$action}" !== $_POST['action'] ) {
			return;
		}

		$opts = $this->registered[ $action ];

		if ( ( ! $user = $this->get_user( $action ) ) || ! $this->result( $opts['show_to_user'], array( $user, isset( $_REQUEST[ self::SHOW_AFTER_LOGIN ] ) ) ) ) {
			wp_safe_redirect( set_url_scheme( wp_login_url(), 'login_post' ) );
			die;
		}

		$maybe_error = call_user_func( $opts['submit'], $user, $_POST );

		if ( is_wp_error( $maybe_error ) ) {
			$this->error = $maybe_error;

			return;
		}

		if ( $next = $this->get_next_interstitial( $action, $user ) ) {
			$this->show_interstitial( $next, $user );
		}

		if ( $this->result( $opts['force_completion'], array( $user ) ) ) {
			$this->delete_token( $user );
			wp_set_auth_cookie( $user->ID, ! empty( $_REQUEST['rememberme'] ) );
		}

		if ( $opts['after_submit'] ) {
			call_user_func( $opts['after_submit'], $user, $_POST );
		}

		if ( ! get_user_meta( $user->ID, '_itsec_has_logged_in', true ) ) {
			update_user_meta( $user->ID, '_itsec_has_logged_in', ITSEC_Core::get_current_time_gmt() );
		}

		/**
		 * Fires when a user is re-logged back in after submitting an interstitial.
		 *
		 * @param WP_User $user
		 */
		do_action( 'itsec_login_interstitial_logged_in', $user );

		if ( $GLOBALS['interim_login'] = isset( $_REQUEST['interim-login'] ) ) {
			$this->interim_login();
		}

		if ( empty( $_REQUEST['redirect_to'] ) ) {
			$redirect_to = admin_url( 'index.php' );
			$requested   = '';
		} else {
			$redirect_to = $requested = $_REQUEST['redirect_to'];
		}

		$redirect_to = apply_filters( 'login_redirect', $redirect_to, $requested, $user );
		wp_safe_redirect( $redirect_to );

		die;
	}

	/**
	 * Ajax Handler.
	 */
	public function ajax_handler() {

		if ( empty( $_POST['itsec_interstitial_action'] ) ) {
			return;
		}

		$action = $_POST['itsec_interstitial_action'];

		if ( empty( $this->registered[ $action ]['ajax_handler'] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid Interstitial Action', 'better-wp-security' ) ) );
		}

		$opts = $this->registered[ $action ];

		$user = $this->get_user( $action, true );

		if ( is_wp_error( $user ) ) {
			wp_send_json_error( array( 'message' => $user->get_error_message() ) );
		}

		if ( ! $this->result( $opts['show_to_user'], array( $user, isset( $_REQUEST[ self::SHOW_AFTER_LOGIN ] ) ) ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Unsupported Interstitial', 'better-wp-security' ) ) );
		}

		$data = $_POST;
		unset( $data['itsec_interstitial_user'], $data['itsec_interstitial_token'] );

		call_user_func( $opts['ajax_handler'], $user, $data );
	}

	/**
	 * Handle displaying the interstitial form.
	 */
	public function display() {

		$action = substr( current_action(), strlen( 'login_form_itsec-' ) );

		if ( empty( $this->registered[ $action ] ) ) {
			return;
		}

		$opts = $this->registered[ $action ];

		$user  = null;
		$token = isset( $_REQUEST['itsec_interstitial_token'] ) ? $_REQUEST['itsec_interstitial_token'] : null;

		$user = $this->get_user( $action );

		if ( ! $user ) {
			wp_safe_redirect( set_url_scheme( wp_login_url(), 'login_post' ) );
			die;
		}

		if ( ! $this->result( $opts['show_to_user'], array( $user, isset( $_REQUEST[ self::SHOW_AFTER_LOGIN ] ) ) ) ) {
			wp_safe_redirect( set_url_scheme( wp_login_url(), 'login_post' ) );
			die;
		}

		$this->login_html( $user, $action, $token );
		die;
	}

	/**
	 * Display an interstitial form during the login process.
	 *
	 * @param WP_User $user
	 * @param string  $action
	 * @param string  $token
	 */
	protected function login_html( $user, $action, $token = null ) {

		$wp_login_url = set_url_scheme( wp_login_url(), 'login_post' );
		$wp_login_url = add_query_arg( 'action', "itsec-{$action}", $wp_login_url );

		if ( isset( $_GET['wpe-login'] ) && ! preg_match( '/[&?]wpe-login=/', $wp_login_url ) ) {
			$wp_login_url = add_query_arg( 'wpe-login', $_GET['wpe-login'], $wp_login_url );
		}

		$interim_login = isset( $_REQUEST['interim-login'] );
		$redirect_to   = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '';

		$rememberme = ! empty( $_REQUEST['rememberme'] );

		wp_enqueue_script( 'user-profile' );

		// Prevent JetPack from attempting to SSO the update password form.
		add_filter( 'jetpack_sso_allowed_actions', '__return_empty_array' );

		if ( ! function_exists( 'login_header' ) ) {
			require_once( dirname( __FILE__ ) . '/includes/function.login-header.php' );
		}

		$opts = $this->registered[ $action ];

		login_header();
		?>

		<?php if ( $this->error ) : ?>
			<div id="login-error" class="message" style="border-left-color: #dc3232;">
				<?php echo $this->error->get_error_message(); ?>
			</div>
		<?php elseif ( $message = $this->result( $opts['info_message'], array( $user ) ) ): ?>
			<p class="message"><?php echo $message; ?></p>
		<?php endif; ?>

		<form name="itsec-<?php echo esc_attr( $action ); ?>" id="itsec-<?php echo esc_attr( $action ); ?>"
			  action="<?php echo esc_url( $wp_login_url ); ?>" method="post" autocomplete="off">

			<?php call_user_func( $opts['render'], $user, compact( 'token', 'wp_login_url', 'redirect_to', 'rememberme' ) ); ?>

			<?php if ( $interim_login ) : ?>
				<input type="hidden" name="interim-login" value="1"/>
			<?php else : ?>
				<input type="hidden" name="redirect_to" value="<?php echo esc_url( $redirect_to ); ?>"/>
			<?php endif; ?>

			<input type="hidden" name="rememberme" id="rememberme" value="<?php echo esc_attr( $rememberme ); ?>"/>
			<input type="hidden" name="action" value="<?php echo esc_attr( "itsec-{$action}" ); ?>">

			<?php if ( null !== $token ): ?>
				<input type="hidden" name="itsec_interstitial_user" value="<?php echo esc_attr( $user->ID ); ?>">
				<input type="hidden" name="itsec_interstitial_token" value="<?php echo esc_attr( $token ); ?>">
			<?php endif; ?>

			<?php if ( isset( $_REQUEST[ self::SHOW_AFTER_LOGIN ], $this->registered[ $_REQUEST[ self::SHOW_AFTER_LOGIN ] ] ) ) : ?>
				<input type="hidden" name="<?php echo esc_attr( self::SHOW_AFTER_LOGIN ); ?>" value="<?php echo esc_attr( $_REQUEST[ self::SHOW_AFTER_LOGIN ] ); ?>">
			<?php endif; ?>
		</form>

		<p id="backtoblog">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( 'Are you lost?', 'better-wp-security' ); ?>">
				<?php echo esc_html( sprintf( __( '&larr; Back to %s', 'better-wp-security' ), get_bloginfo( 'title', 'display' ) ) ); ?>
			</a>
		</p>

		</div>
		<?php do_action( 'login_footer' ); ?>
		<div class="clear"></div>
		</body>
		</html>
		<?php
	}

	/**
	 * Handle the interim login screen.
	 */
	private function interim_login() {

		if ( ! function_exists( 'login_header' ) ) {
			require_once( dirname( __FILE__ ) . '/includes/function.login-header.php' );
		}

		$GLOBALS['interim_login'] = 'success';
		$customize_login          = isset( $_REQUEST['customize-login'] );

		if ( $customize_login ) {
			wp_enqueue_script( 'customize-base' );
		}

		login_header( '', '<p class="message">' . __( 'You have logged in successfully.' ) . '</p>' );
		?>
		</div>
		<?php

		do_action( 'login_footer' ); ?>

		<?php if ( $customize_login ) : ?>
			<script type="text/javascript">
				setTimeout( function () {
					new wp.customize.Messenger( {
						url    : '<?php echo wp_customize_url(); ?>',
						channel: 'login'
					} ).send( 'login' )
				}, 1000 );
			</script>
		<?php endif; ?>

		</body></html>
		<?php die;
	}

	/**
	 * Get the next interstitial to be displayed.
	 *
	 * @param string  $current The interstitial that was just submitted.
	 * @param WP_User $user
	 *
	 * @return string|false
	 */
	private function get_next_interstitial( $current, $user ) {

		$past_current = false;

		foreach ( $this->get_applicable_interstitials( $user ) as $handler => $opts ) {
			if ( $handler === $current ) {
				$past_current = true;
				continue;
			}

			if ( $past_current ) {
				return $handler;
			}
		}

		return false;
	}

	/**
	 * Get all handlers that are applicable to the given user.
	 *
	 * @param WP_User $user
	 *
	 * @return array
	 */
	private function get_applicable_interstitials( $user ) {

		$applicable = array();

		foreach ( $this->registered as $action => $opts ) {
			if ( $this->is_interstitial_applicable( $action, $user ) ) {
				$applicable[ $action ] = $opts;
			}
		}

		return $applicable;
	}

	/**
	 * Is the interstitial applicable to the given user.
	 *
	 * @param string  $interstitial
	 *
	 * @param WP_User $user
	 *
	 * @return bool
	 */
	private function is_interstitial_applicable( $interstitial, $user ) {

		$opts = $this->registered[ $interstitial ];

		if ( ! $this->result( $opts['show_to_user'], array( $user, isset( $_REQUEST[ self::SHOW_AFTER_LOGIN ] ) ) ) ) {
			return false;
		}

		if ( ! did_action( 'login_init' ) && $this->result( $opts['wp_login_only'], array( $user ) ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Handle checking for and validating the token, if it does not exist, will redirect with error message.
	 *
	 * @param string $action
	 * @param bool   $return_error
	 *
	 * @return WP_Error|array Array with token and user.
	 */
	private function handle_token( $action, $return_error = false ) {

		$is_valid = true;
		$user     = null;

		if ( empty( $_REQUEST['itsec_interstitial_user'] ) || empty( $_REQUEST['itsec_interstitial_token'] ) ) {
			$is_valid = false;
		} elseif ( ( ! $user = get_userdata( $_REQUEST['itsec_interstitial_user'] ) ) || ! $this->verify_token( $user, $_REQUEST['itsec_interstitial_token'] ) ) {
			$is_valid = false;
		}

		if ( ! $is_valid ) {
			if ( $return_error ) {
				return new WP_Error(
					'itsec-login-interstitial-invalid-token',
					esc_html__( 'Sorry, this request has expired. Please log in again.', 'better-wp-security' )
				);
			}

			$this->redirect_invalid_token( $action );
		}

		return array( $user, $_REQUEST['itsec_interstitial_token'] );
	}

	/**
	 * Get the current user for the interstitial.
	 *
	 * @param string $action
	 * @param bool   $return_error
	 *
	 * @return WP_Error|WP_User|null
	 */
	private function get_user( $action, $return_error = false ) {

		$opts = $this->registered[ $action ];

		if ( false === $opts['force_completion'] ) {
			return is_user_logged_in() ? wp_get_current_user() : null;
		}

		if ( isset( $_REQUEST['itsec_interstitial_user'] ) || true === $opts['force_completion'] ) {
			$maybe = $this->handle_token( $action, $return_error );

			return is_wp_error( $maybe ) ? $maybe : $maybe[0];
		}

		$user = wp_get_current_user();

		if ( $user && $user->exists() && ! call_user_func( $opts['force_completion'], $user ) ) {
			return $user;
		}

		if ( $return_error ) {
			return new WP_Error(
				'itsec-login-interstitial-invalid-token',
				esc_html__( 'Sorry, this request has expired. Please log in again.', 'better-wp-security' )
			);
		}

		$this->redirect_invalid_token( $action );
	}

	/**
	 * Redirect back to the login page with a message that the token is invalid.
	 *
	 * @param string $action
	 */
	private function redirect_invalid_token( $action ) {
		$redirect = add_query_arg( 'itsec_interstitial_expired', $action, wp_login_url() );
		wp_safe_redirect( set_url_scheme( $redirect, 'login_post' ) );
		die;
	}

	/**
	 * Destroy the session for a user.
	 *
	 * @param WP_User $user
	 */
	private function destroy_session( $user ) {
		WP_Session_Tokens::get_instance( $user->ID )->destroy( $this->session_token ? $this->session_token : wp_get_session_token() );
		wp_clear_auth_cookie();
	}

	/**
	 * Verify that the token is valid.
	 *
	 * @param WP_User $user
	 * @param string  $key
	 *
	 * @return bool
	 */
	private function verify_token( $user, $key ) {
		$expected = get_user_meta( $user->ID, self::META_KEY, true );

		if ( ! $expected || ! is_array( $expected ) ) {
			return false;
		}

		if ( empty( $expected['expires'] ) || $expected['expires'] < ITSEC_Core::get_current_time_gmt() ) {
			return false;
		}

		return hash_equals( $expected['key'], $key );
	}

	/**
	 * Set the token for a user.
	 *
	 * @param WP_User $user
	 *
	 * @return string
	 */
	private function set_token( $user ) {
		$key = $this->generate_token();

		update_user_meta( $user->ID, self::META_KEY, array(
			'key'     => $key,
			'expires' => ITSEC_Core::get_current_time_gmt() + HOUR_IN_SECONDS
		) );

		return $key;
	}

	/**
	 * Generate a token to be used to verify intent of submitting a login interstitial.
	 *
	 * We can't use nonces here because the WordPress Session Tokens won't be initialized yet.
	 *
	 * @return string
	 */
	private function generate_token() {
		return sha1( wp_generate_password( 32, true, true ) );
	}

	/**
	 * Delete the token for a user.
	 *
	 * @param WP_User $user
	 */
	private function delete_token( $user ) {
		delete_user_meta( $user->ID, self::META_KEY );
	}

	/**
	 * Try and get a value from the provider.
	 *
	 * If it is a function, will call the function with the provided args.
	 *
	 * @param bool|callable $provider
	 * @param array         $args
	 *
	 * @return bool|mixed
	 */
	private function result( $provider, $args = array() ) {
		if ( is_bool( $provider ) ) {
			return $provider;
		}

		if ( is_callable( $provider, true ) ) {
			return call_user_func_array( $provider, $args );
		}

		return $provider;
	}
}