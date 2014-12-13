<?php
/**
 * @category   MagePsycho
 * @package    MagePsycho_Massimporterpro
 * @author     magepsycho@gmail.com
 * @website    http://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MagePsycho_Massimporterpro_Model_Massimporterpro extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('massimporterpro/massimporterpro');
    }
}