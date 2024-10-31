<?php namespace Premmerce\Attributes;

use Premmerce\Attributes\AttributesModel as Model;

class Updater
{
    const CURRENT_VERSION = '1.2.1';

    const DB_OPTION = 'premmerce_advanced_attributes_db_version';

    public function checkForUpdates()
    {
        return $this->compare(self::CURRENT_VERSION);
    }

    private function compare($version)
    {
        $dbVersion = get_option(self::DB_OPTION, 1.0);

        return version_compare($dbVersion, $version, '<');
    }

    public function update()
    {
        if ($this->checkForUpdates()) {
            foreach ($this->getUpdates() as $version => $callback) {
                if ($this->compare($version)) {
                    call_user_func($callback);
                }
            }
        }
    }

    public function getUpdates()
    {
        return array(
            '1.1' => array( $this, 'update1_1' ),
        );
    }

    public function update1_1()
    {
        Model::createTable();

        update_option(self::DB_OPTION, '1.1');
    }
}
