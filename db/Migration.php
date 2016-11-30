<?php
/**
 * @author Nikola Kostadinov<nikolakk@gmail.com>
 * Date: 12.09.2015
 * Time: 01:22 ч.
 */

namespace insight\core\db;

use yii\console\Exception;

class Migration extends \yii\db\Migration{
    const FK_CASCADE = 'CASCADE';
    const FK_RESTRICT = 'RESTRICT';

    public function getTableOptions()
    {
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            return 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        } else
            throw new Exception('Unsupported database.');
    }
}