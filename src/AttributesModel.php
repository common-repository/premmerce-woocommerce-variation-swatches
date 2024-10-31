<?php namespace Premmerce\Attributes;

class AttributesModel
{
    /**
     * Return attribute
     *
     * @param  int $attribute_id
     *
     * @return mixed $result
     */
    public static function getById($attribute_id)
    {
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}premmerce_attributes WHERE attribute_id = %d";
        
        $sql = $wpdb->prepare($sql, $attribute_id);
        
        return $wpdb->query($sql);
    }

    /**
     * Return row attribute
     *
     * @param  int $attribute_id
     *
     * @return mixed $result
     */
    public static function getRowById($attribute_id)
    {
        global $wpdb;

        return $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "premmerce_attributes WHERE attribute_id = '$attribute_id'");
    }

    /**
     * Update attribute by id
     *
     * @param  int $attribute_id
     * @param  array $args
     *
     * @return mixed $result
     */
    public static function updateById($attribute_id, $args)
    {
        global $wpdb;

        $sql = "UPDATE {$wpdb->prefix}premmerce_attributes SET `taxonomy_name` = %s, `description` = %s, `is_main` = %d, `is_archive` = %d, `type` = %s WHERE attribute_id = %d";
        $sql = $wpdb->prepare($sql, $args['taxonomy_name'], $args['description'], $args[ 'is_main' ], $args[ 'is_archive' ], $args[ 'type' ], $attribute_id);

        return $wpdb->query($sql);
    }

    /**
     * Insert attribute
     *
     * @param  array $args
     *
     * @return mixed $result
     */
    public static function insert($args)
    {
        global $wpdb;

        $sql = "INSERT INTO {$wpdb->prefix}premmerce_attributes  (`taxonomy_name`, `description`, `is_main`, `is_archive`, `attribute_id`, `type`) VALUES (%s,%s,%d,%d,%d,%s)";

        $sql = $wpdb->prepare($sql, $args['taxonomy_name'], $args['description'], $args['is_main'], $args['is_archive'], $args['attribute_id'], $args['type']);

        return $wpdb->query($sql);
    }

    /**
     * Delete attribute
     *
     * @param  string $taxonomy_name
     *
     * @return mixed $result
     */
    public static function deleteByTaxonomyName($taxonomy_name)
    {
        global $wpdb;

        $sql = "DELETE FROM {$wpdb->prefix}premmerce_attributes WHERE taxonomy_name = %s";

        $sql = $wpdb->prepare($sql, $taxonomy_name);

        return $wpdb->query($sql);
    }

    /**
     * Get attribute
     *
     * @param  string $taxonomy_name
     *
     * @return mixed $result
     */
    public static function getByTaxonomyName($taxonomy_name)
    {
        global $wpdb;

        return $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "premmerce_attributes WHERE taxonomy_name = '$taxonomy_name'");
    }

    /**
     * Create prefix_premmerce_attributes table
     */
    public static function createTable()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'premmerce_attributes';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE " . $table_name . " (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          attribute_id mediumint(9) NOT NULL,
          taxonomy_name VARCHAR(255) NOT NULL,
          description TEXT(1000) NOT NULL,
          is_main tinyint(1) NOT NULL,
          is_archive tinyint(1) NOT NULL,
          type VARCHAR(255) default 'default' NOT NULL,
          data TEXT(10000),
          UNIQUE KEY id (id)
        ) $charset_collate ;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        dbDelta($sql);
    }

    /**
     * Drop table
     */
    public static function dropTable()
    {
        global $wpdb;

        $tableName = $wpdb->prefix . 'premmerce_attributes';

        $sql = "DROP TABLE IF EXISTS $tableName;";

        $wpdb->query($sql);
    }

    /**
     *  Get archive main attributes
     */
    public static function getArchive()
    {
        global $wpdb;

        $sql = "SELECT `taxonomy_name` FROM " . $wpdb->prefix . "premmerce_attributes WHERE `is_archive` = 1";

        $result = $wpdb->get_results($sql);

        $result = array_map(function ($item) {
            return $item->taxonomy_name;
        }, $result);

        return $result;
    }

    /**
     * Get attributes by taxonomy names
     *
     * @param array $attribute_keys
     * @return array|null|object
     */
    public static function getWhereNameIn($attributes)
    {
        global $wpdb;

        $sql    = "SELECT * FROM {$wpdb->prefix}premmerce_attributes WHERE taxonomy_name IN ('" . implode("','", $attributes) . "')";
        $result = $wpdb->get_results($sql);
        return $result;
    }
}
