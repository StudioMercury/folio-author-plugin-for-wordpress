<?php
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
    die( 'Access denied.' );

if( !class_exists( 'DPSFolioAuthor_Templates' ) )
{
    class DPSFolioAuthor_Templates extends DPSFolioAuthor_Module
    {
        private $templateDirectory;
        private $templateDirectoryPath;

        public function __construct() {
            $this->templateDirectory = 'templates-dps';
            $this->templateDirectoryPath = DPSFA_DIR.'/'.$this->templateDirectory;
            $this->registerHookCallbacks();
        }

        public function getTemplatePath($template) {
            // give precedence to files located in the theme's templates-dps directory
            if (is_file(get_template_directory().'/'.$template)) {
                $template = get_template_directory().'/'.$template;
            } elseif (is_file(DPSFA_DIR.'/'.$template)) {
                $template = DPSFA_DIR.'/'.$template;
            }

            return $template;
        }

        public function getTemplates() {
            $templates = array_merge(
                $this->getTemplatesFrom($this->templateDirectoryPath),
                $this->getTemplatesFrom(get_template_directory().'/'.$this->templateDirectory)
            );
            // make template paths unique
            return array_flip(array_flip($templates));
        }

        public function pageTemplateDropdown( $default = '' ) {
            $templates = $this->getTemplates();

            ksort( $templates );

            foreach (array_keys( $templates ) as $template ) {
                if ( $default == $templates[$template] ) {
                    $selected = " selected='selected'";
                } else {
                    $selected = '';
                }
                echo "\n\t<option value='".$templates[$template]."' $selected>$template</option>";
            }
        }

        public function registerHookCallbacks() {
            add_action( 'template_redirect', array( $this, 'redirectTemplate' ) );
        }

        public function redirectTemplate() {
            if (is_singular('dpsfa_article')) {
                $article = DPSFolioAuthor_Article::getInstance();
                $template = $article->get_article_field( get_the_ID(), 'template');
                if (isset($template) ) {
                    the_post();
                    $renderer = new DPSFolioAuthor_TemplateRenderer(
                        $this->getTemplateMaxImageWidth($template),
                        $this->getTemplateMaxImageHeight($template)
                    );
                    ob_start(array($renderer, 'render'));
                    include($this->getTemplatePath($template));
                    ob_end_flush();
                    exit;
                }
            }
        }

        public function activate( $networkWide ){}
        public function deactivate(){}
        public function upgrade( $dbVersion = 0 ){}
        protected function isValid( $property = 'all' ){}
        public function init() {}

        private function getTemplateMaxImageHeight($template) {
            $maxImageHeight = 0;
            $templatePath = $this->getTemplatePath($template);
            if (preg_match('|Max Image Height:\s*([\d]+)$|mi', file_get_contents($templatePath), $matches)) {
                if ($matches && count($matches >= 2)) {
                    $maxImageHeight = intval($matches[1]);
                }
            }

            return $maxImageHeight;
        }

        private function getTemplateMaxImageWidth($template) {
            $maxImageWidth = 0;
            $templatePath = $this->getTemplatePath($template);
            if (preg_match('|Max Image Width:\s?([\d]+)$|mi', file_get_contents($templatePath), $matches)) {
                if ($matches && count($matches >= 2)) {
                    $maxImageWidth = intval($matches[1]);
                }
            }

            return $maxImageWidth;
        }

        private function getTemplatesFrom($path) {
            $templates = array();
            if (is_dir($path)) {
                $files = scandir($path);

                foreach ($files as $filename) {
                    if ($filename != '.' && $filename != '..' && preg_match('/\.php$/', $filename)) {
                        if (preg_match( '|Article Template Name:\s?(.*)$|mi', file_get_contents($path.'/'.$filename), $matches )) {
                            if ($matches && count($matches) >= 2) {
                                $templateName = trim($matches[1]);
                                if ($templateName && $templateName != '') {
                                    $templates[$templateName] = $this->templateDirectory.'/'.$filename;
                                }
                            }
                        }
                    }
                }
            }

            return $templates;
        }
    }
}
