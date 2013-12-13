<?php
/**
 *
 * Digital Publishing Suite Folio Authoring Plugin
 * Class : SETTINGS
 *
 */


 /* TODOS:

 */

if(!class_exists('DPSFolioAuthor_Settings')) {

	class DPSFolioAuthor_Settings extends DPSFolioAuthor_Module {

	    protected $settings;
	    protected $settingsField;
        const ENCRYPTION_METHOD = 'AES-256-CBC';
		const REQUIRED_CAPABILITY = 'administrator';

        protected function __construct(){
            # only adds an empty option if one does not exist yet
            # helps to fix double validation when inserting new option
			add_option(DPSFolioAuthor::PREFIX . 'settings', array(
                'company' => '',
                'key' => '',
                'secret' => '',
                'login' => '',
                'password' => ''
            ));
            $this->registerHookCallbacks();
		}

        public function __set( $variable, $value ){
			if( $variable != 'settings' )
				return;
			$this->settings	= self::validateSettings( $value );
		}

        public function registerHookCallbacks(){
			add_action( 'admin_init',					array( $this, 'registerSettings' ) );
		}

		public function registerSettings(){
		    $settingsMeta = DPSFolioAuthor::PREFIX . 'settings';
		    register_setting( 'dps-settings', $settingsMeta, array($this, 'validate_settings') );
            add_settings_section( 'dps-settings', '', '', $settingsMeta );
            add_settings_field( 'field-one', '', array($this,'settings_view'), $settingsMeta, 'dps-settings' );
		}

		public function validate_settings( $data ){
            // handle HTMLResources
            if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
            if ($_FILES[DPSFolioAuthor::PREFIX . 'settings_htmlresources']) {
                $uploadedfile = $_FILES[DPSFolioAuthor::PREFIX . 'settings_htmlresources'];
                $file = wp_handle_upload($uploadedfile, array( 'test_form' => false ));
            }
            if(array_key_exists('file',$file)){ $data["htmlresources"] = (string)$file['url']; }

            return $this->encryptData($data);
		}

		public function settings_view(){
		    $settings = $this->get_settings();
            $settingsMeta = DPSFolioAuthor::PREFIX . 'settings';
		    if(!$settings){ $settings = array(); }
            include_once( dirname( __DIR__ ) .'/views/admin/dpsfa-settings-fields.php');
		}

        public function activate( $networkWide ){ }
        public function deactivate(){ }

        public function init(){
			if( did_action( 'init' ) !== 1 )
				return;
			$this->settings = self::get_settings();
		}

		public function get_settings(){
		    $settingsMeta = DPSFolioAuthor::PREFIX . 'settings';
            return $this->decryptData(get_option( $settingsMeta ));
		}

        public function upgrade(){ }
        protected function isValid( $property = 'all' ){
			// Note: __set() calls validateSettings(), so settings are never invalid
			return true;
		}

        private function decrypt($string) {
            $output = false;

            $encrypt_method = self::ENCRYPTION_METHOD;
            $secret_key = wp_salt();
            $secret_iv = wp_salt('secure_auth');

            // hash
            $key = hash('sha256', $secret_key);

            // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
            $iv = substr(hash('sha256', $secret_iv), 0, 16);

            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);

            return $output;
        }

        private function decryptData($data) {
            $plain_txt = "asdf";

            if( is_array($data) && array_key_exists('key', $data) ) {
                $data['key'] = $this->decrypt($data['key']);
            }
            if( is_array($data) && array_key_exists('secret', $data) ) {
                $data['secret'] = $this->decrypt($data['secret']);
            }

            return $data;
        }

        private function encrypt($string) {
            $output = false;

            $encrypt_method = self::ENCRYPTION_METHOD;
            $secret_key = wp_salt();
            $secret_iv = wp_salt('secure_auth');

            // hash
            $key = hash('sha256', $secret_key);

            // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
            $iv = substr(hash('sha256', $secret_iv), 0, 16);

            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);

            return $output;
        }

        private function encryptData($data) {
            if (array_key_exists('key', $data)) {
                $data['key'] = $this->encrypt($data['key']);
            }
            if (array_key_exists('secret', $data)) {
                $data['secret'] = $this->encrypt($data['secret']);
            }
            return $data;
        }

    } // END class DPSFolioAuthor_Settings
}
