<?php namespace Premmerce\Attributes\Admin;

use Premmerce\Attributes\AttributesPlugin;
use Premmerce\Attributes\Admin\Tabs\Settings;
use Premmerce\SDK\V2\FileManager\FileManager;
use Premmerce\Attributes\Admin\Tabs\SimpleTab;
use Premmerce\Attributes\Admin\Tabs\BundleAndSave;
use Premmerce\Attributes\Admin\Tabs\TabRenderer;
use Premmerce\Attributes\AttributesModel as Model;

/**
 * Class Admin
 *
 * @package Premmerce\Attributes\Admin
 */
class Admin
{
    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * @var string
     */
    private $settingsPage;

    /**
     * @var array list of all available attribute types
     */
    private $types = array();

    /**
     * Admin constructor.
     *
     * Register menu items and handlers
     *
     * @param FileManager $fileManager
     */
    public function __construct(FileManager $fileManager)
    {
        $this->fileManager  = $fileManager;
        $this->tabRenderer  = new TabRenderer($this->fileManager);
        $this->settingsPage = 'premmerce-advanced-attributes-admin';

        add_action('init', function () {
            $this->initTabs();
        }, 11);

        $this->hooks();
    }

  /**
   * Add hooks
   */
  public function hooks()
  {
      add_action('woocommerce_after_add_attribute_fields', array($this, 'renderFields'));
      add_action('woocommerce_after_edit_attribute_fields', array($this, 'renderEditField'), 99);
      add_action('add_tag_form_fields', array($this, 'renderMetaField'));
      add_action('created_term', array($this, 'saveTermFields'), 10, 3);
      add_action('edit_term', array($this, 'saveTermFields'), 10, 3);
      add_action('woocommerce_attribute_added', array($this, 'saveFields'), 1, 2);
      add_action('woocommerce_attribute_updated', array($this, 'updateFields'), 1, 3);
      add_action('woocommerce_attribute_deleted', array($this, 'deleteAttribute'), 1, 3);
      add_action('init', array($this, 'setTypes'));
      add_action('admin_menu', array($this, 'addMenuPage'));
      add_action('admin_enqueue_scripts', array($this, 'registerAssets'), 11);
      add_filter('admin_footer_text', array($this, 'removeFooterAdmin'), 10);
  }

  /**
   * Set types
   */
  public function setTypes()
  {
      $this->types['default'] = __('Default', 'premmerce-advanced-attributes');
      $this->types['label']   = __('Label', 'premmerce-advanced-attributes');
      $this->types['color']   = __('Color', 'premmerce-advanced-attributes');
      $this->types['bicolor'] = __('Bicolor', 'premmerce-advanced-attributes');
      $this->types['image']   = __('Image', 'premmerce-advanced-attributes');
      $this->types['radio']   = __('Radio', 'premmerce-advanced-attributes');


      if (function_exists('wc_get_attribute_taxonomies')) {
          $productAttributes = wc_get_attribute_taxonomies();
      } else {
          global $wpdb;
          $productAttributes = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}woocommerce_attribute_taxonomies WHERE attribute_name != '' ORDER BY attribute_name ASC;");
      }

      if (! empty($productAttributes)) {
          foreach ($productAttributes as $attr) {
              add_action('pa_' . $attr->attribute_name . '_edit_form_fields', array($this, 'renderEditMetaField'));
          }
      }
  }

    /**
     * Render fields on product_attributes page
     */
    public function renderFields()
    {
        $this->fileManager->includeTemplate('admin/attributes-add.php', array(
            'types' => $this->types,
        ));
    }

    /**
     * Add submenu to premmerce menu page
     */
    public function addMenuPage()
    {
        global $admin_page_hooks;

        $premmerceMenuExists = isset($admin_page_hooks['premmerce']);

        if (! $premmerceMenuExists) {
            $svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="20" height="16" style="fill:#82878c" viewBox="0 0 20 16"><g id="Rectangle_7"> <path d="M17.8,4l-0.5,1C15.8,7.3,14.4,8,14,8c0,0,0,0,0,0H8h0V4.3C8,4.1,8.1,4,8.3,4H17.8 M4,0H1C0.4,0,0,0.4,0,1c0,0.6,0.4,1,1,1 h1.7C2.9,2,3,2.1,3,2.3V12c0,0.6,0.4,1,1,1c0.6,0,1-0.4,1-1V1C5,0.4,4.6,0,4,0L4,0z M18,2H7.3C6.6,2,6,2.6,6,3.3V12 c0,0.6,0.4,1,1,1c0.6,0,1-0.4,1-1v-1.7C8,10.1,8.1,10,8.3,10H14c1.1,0,3.2-1.1,5-4l0.7-1.4C20,4,20,3.2,19.5,2.6 C19.1,2.2,18.6,2,18,2L18,2z M14,11h-4c-0.6,0-1,0.4-1,1c0,0.6,0.4,1,1,1h4c0.6,0,1-0.4,1-1C15,11.4,14.6,11,14,11L14,11z M14,14 c-0.6,0-1,0.4-1,1c0,0.6,0.4,1,1,1c0.6,0,1-0.4,1-1C15,14.4,14.6,14,14,14L14,14z M4,14c-0.6,0-1,0.4-1,1c0,0.6,0.4,1,1,1 c0.6,0,1-0.4,1-1C5,14.4,4.6,14,4,14L4,14z"/></g></svg>';
            $svg = 'data:image/svg+xml;base64,' . base64_encode($svg);

            add_menu_page(
                'Premmerce',
                'Premmerce',
                'manage_options',
                'premmerce',
                '',
                $svg
            );
        }

        $page = add_submenu_page(
            'premmerce',
            __('Variation swatches', 'premmerce-advanced-attributes'),
            __('Variation swatches', 'premmerce-advanced-attributes'),
            'manage_options',
            $this->settingsPage,
            array($this, 'options')
        );


        if (! $premmerceMenuExists) {
            global $submenu;
            unset($submenu['premmerce'][0]);
        }
    }

    /**
     * Options page
     */
    public function options()
    {
        // $this->fileManager->includeTemplate('admin/macros.php');

        $this->tabRenderer->render();
    }

    /**
     * Register admin css and js
     *
     * @param $page
     */
    public function registerAssets($page)
    {
        //scripts and styles for settings tabs
        if ($page === 'premmerce_page_premmerce-advanced-attributes-admin') {
            wp_enqueue_script('wc-enhanced-select');
            wp_enqueue_style('woocommerce_admin_styles');

            wp_enqueue_style('wp-color-picker');

            wp_enqueue_style('premmerce_advanced_attributes_admin_style', $this->fileManager->locateAsset('admin/css/style.css'));

            wp_enqueue_script(
                'premmerce_advanced_attributes_admin_script',
                $this->fileManager->locateAsset('admin/js/script.js'),
                array('select2', 'wp-color-picker', 'jquery-ui-droppable')
            );

            wp_enqueue_script(
                'premmerce_advanced_attributes_admin_script',
                $this->fileManager->locateAsset('admin/js/script.js'),
                array('select2', 'wp-color-picker', 'jquery-ui-droppable')
            );

            //Google fonts
            wp_enqueue_style('premmerce_google_fonts', $this->getGoogleFontUrl('all'), array(), null);

            $stylesFontFamily = Settings::renderGoogleFontFamily();
            //add styles from widget fields
            if (!empty($stylesFontFamily)) {
                wp_add_inline_style('premmerce_advanced_attributes_admin_style', $stylesFontFamily);
            }
        }
    }

    /**
     * get Google Font Url
     */
    public static function getGoogleFontUrl($fonts)
    {
        $url   = 'https://fonts.googleapis.com/css2?family=%1$s&display=swap';

        if ($fonts === 'all') {
            //all fonts from settings array
            $fonts      = Settings::googleFonts();
            $fontsInUrl = self::googleAllFontString($fonts);
        } else {
            $fontsInUrl = self::googleOneFontString($fonts);
        }

        $googleFontUrl = sprintf($url, $fontsInUrl);

        return $googleFontUrl;
    }

    /**
     * Get Google url string for one current font
     */
    public static function googleOneFontString($font = array())
    {
        $string = "{$font['url_name']}:wght@{$font['wght']}";

        return $string;
    }

    /**
     * Get Google url string for all fonts
     */
    public static function googleAllFontString($fonts = array())
    {
        $string = '';

        if (!empty($fonts)) {
            foreach ($fonts as $font) {
                $string .= !empty($string) ? '&family=' : '';
                $string .= "{$font['url_name']}:wght@{$font['wght']}";
            }
        }

        return $string;
    }

    /**
     * Admin footer modification
     *
     * @param $text - default Wordpress footer thankyou text
     */
    public function removeFooterAdmin($text)
    {
        $screen = get_current_screen();
        $premmercePages = array(
            'premmerce_page_premmerce-advanced-attributes-admin'
        );

        if (in_array($screen->id, $premmercePages)) {
            $link   = 'https://wordpress.org/support/plugin/premmerce-woocommerce-variation-swatches/reviews/?filter=5';
            $target = 'target="_blank"';
            $text   = '<span id="footer-thankyou">';
            $text  .= sprintf(
                __('Please rate our Premmerce Variation Swatches for WooCommerce on <a href="%1$s" %2$s>WordPress.org</a><br/>Thank you from the Premmerce team!', 'premmerce-advanced-attributes'),
                $link,
                $target
            );
            $text .= '</span>';
        } else {
            $text = '<span id="footer-thankyou">' . $text . '</span>';
        }

        return $text;
    }

    /**
     * Render field on tags edit page
     * @param string $taxonomy
     */
    public function renderMetaField($taxonomy)
    {
        if ($this->checkTaxonomy($taxonomy)) {
            $taxonomy = Model::getByTaxonomyName($taxonomy);

            if (! $taxonomy) {
                $type = 'default';
            } else {
                $type = $taxonomy->type;
            }
            wp_enqueue_media();

            if ('default' != $type) {
                $this->fileManager->includeTemplate('admin/term-types/new/' . $type . '.php', array(
                    'taxonomy'      => $taxonomy,
                    'fileManager'   => $this->fileManager
                ));
            }
        }
    }

    /**
     * Render templates for custom attribute types
     *
     * @param \WP_Term $term
     */
    public function renderEditMetaField($term)
    {
        if ($this->checkTaxonomy($term->taxonomy)) {
            $taxonomy = Model::getByTaxonomyName($term->taxonomy);

            if (! $taxonomy) {
                $type = 'default';
            } else {
                $type = $taxonomy->type;
            }
            wp_enqueue_media();

            if ('default' != $type) {
                $this->fileManager->includeTemplate('admin/term-types/edit/' . $type . '.php', array(
                    'taxonomy'      => $taxonomy,
                    'term'          => $term,
                    'fileManager'   => $this->fileManager
                ));
            }
        }
    }

    /**
     * Save metadata to custom attributes terms
     *
     * @param int $term_id
     * @param string $tt_id
     * @param string $taxonomy
     */
    public function saveTermFields($term_id, $tt_id = '', $taxonomy = '')
    {
        if ($this->checkTaxonomy($taxonomy)) {
            if (isset($_POST[ 'term_thumbnail_id' ])) {
                update_term_meta($term_id, AttributesPlugin::THUMBNAIL_ID, absint($_POST['term_thumbnail_id']));
            }

            if (isset($_POST[ 'term_color' ])) {
                update_term_meta($term_id, AttributesPlugin::COLOR, sanitize_hex_color($_POST['term_color']));
            }

            if (isset($_POST[ 'term_first_color' ])) {
                update_term_meta($term_id, AttributesPlugin::FIRST_COLOR, sanitize_hex_color($_POST['term_first_color']));
            }

            if (isset($_POST[ 'term_second_color' ])) {
                update_term_meta($term_id, AttributesPlugin::SECOND_COLOR, sanitize_hex_color($_POST['term_second_color']));
            }
        }
    }

    /**
     * Render fields on attribute edit page
     */
    public function renderEditField()
    {
        $attribute_id = $_GET['edit'];

        $advanced_fields = Model::getRowById($attribute_id);

        $this->fileManager->includeTemplate('admin/attributes-edit.php', array(
            'advanced_fields' => $advanced_fields,
            'types'           => $this->types
        ));
    }

    /**
     * Handler for attribute save form
     * @param  int $attribute_id
     * @param  array $data
     */
    public function saveFields($attribute_id, $data)
    {
        $description = $_POST['attribute_description'];
        $is_main     = isset($_POST['attribute_is_main']);
        $is_archive  = isset($_POST['attribute_public']);
        $type        = $_POST['attribute_type'];

        $this->updateMainAttributes('pa_' . $data['attribute_name' ], $is_main);

        Model::insert(array(
            'taxonomy_name' => 'pa_' . $data['attribute_name' ],
            'description'   => $description,
            'is_main' 		=> $is_main,
            'is_archive' 	=> $is_archive,
            'attribute_id'  => $attribute_id,
            'type'          => $type
        ));
    }

    /**
     * Handler for attribute update form
     * @param  int $attribute_id
     * @param  array $data
     */
    public function updateFields($attribute_id, $data)
    {
        $description   = $_POST['attribute_description'];
        $is_main       = isset($_POST['attribute_is_main']);
        $taxonomy_name = 'pa_' . $data['attribute_name'];
        $is_archive    = isset($_POST['attribute_public']);
        $type          = $_POST['attribute_type'];

        $this->updateMainAttributes($taxonomy_name, $is_main);

        //UPDATE OR CREATE

        if (Model::getById($attribute_id) != false) {
            Model::updateById($attribute_id, array(
                'taxonomy_name' => $taxonomy_name,
                'description'   => $description,
                'is_main' 		=> $is_main,
                'is_archive' 	=> $is_archive,
                'type'          => $type,
            ));
        } else {
            Model::insert(array(
                'taxonomy_name' => $taxonomy_name,
                'description'   => $description,
                'is_main' 		=> $is_main,
                'is_archive' 	=> $is_archive,
                'attribute_id'  => $attribute_id,
                'type'          => $type
            ));
        }
    }

    /**
     * Handler for delete attribute
     * @param  int $id
     * @param  string $name
     * @param  string $taxonomy
     */
    public function deleteAttribute($id, $name, $taxonomy)
    {
        Model::deleteByTaxonomyName($taxonomy);

        $this->updateMainAttributes($taxonomy, 0);
    }

    /**
     * Update main attributes list
     * @param string $name
     * @param bool $is_main
     */
    public function updateMainAttributes($name, $is_main)
    {
        $list = get_option('premmerce_main_attributes', array());

        if ($is_main) {
            if (! in_array($name, $list)) {
                array_push($list, $name);
            }
        } else {
            if (in_array($name, $list)) {
                $key = array_search($name, $list);

                unset($list[$key]);
            }
        }

        update_option('premmerce_main_attributes', $list);
    }

    /**
     * Return attributes term object
     * @param  array $postId
     * @return mixed
     */
    public static function getMainAttributes($postId)
    {
        $mainAttributes = get_option('premmerce_main_attributes');

        $term_query = array_filter($mainAttributes, function ($item) {
            return taxonomy_exists($item);
        });

        if (! empty($term_query)) {
            $args = array(
                'taxonomy' => $term_query,
                'object_ids' => $postId
            );

            $term_query = get_terms($args);

            return $term_query;
        }

        return array();
    }

    /**
     * Return attributes description
     * @param  string $taxonomy
     * @return string
     */
    public static function getAttributeDescription($taxonomy)
    {
        if (! $attribute = wp_cache_get($taxonomy, 'attributes')) {
            $attribute = Model::getByTaxonomyName($taxonomy);

            wp_cache_set($taxonomy, $attribute, 'attributes');
        }

        if ($attribute) {
            return $attribute->description;
        }
        return false;
    }

    /**
     * Check if taxonomy is attribute
     *
     * @param string $taxonomy
     * @return bool
     */
    public function checkTaxonomy($taxonomy)
    {
        return strpos($taxonomy, 'pa') !== false;
    }

    /**
     * Register tabs and init tab renderer
     */
    private function initTabs()
    {
        $this->tabRenderer->register(new Settings());

        $this->tabRenderer->register(new BundleAndSave($this->fileManager));

        $this->tabRenderer->register(
            new SimpleTab(
                'account',
                __('Account', 'premmerce-advanced-attributes'),
                function () {
                    premmerce_pwvs_fs()->add_filter('hide_account_tabs', '__return_true');
                    premmerce_pwvs_fs()->_account_page_load();
                    premmerce_pwvs_fs()->_account_page_render();
                },
                function () {
                    return premmerce_pwvs_fs()->is_registered();
                }
            )
        );

        $this->tabRenderer->register(
            new SimpleTab(
                'contact',
                __('Contact Us', 'premmerce-advanced-attributes'),
                function () {
                    premmerce_pwvs_fs()->_contact_page_render();
                }
            )
        );

        $this->tabRenderer->init();
    }
}
