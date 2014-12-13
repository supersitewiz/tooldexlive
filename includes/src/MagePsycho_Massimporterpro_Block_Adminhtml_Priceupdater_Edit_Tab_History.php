<?php
/**
 * @category   MagePsycho
 * @package    MagePsycho_Massimporterpro
 * @author     magepsycho@gmail.com
 * @website    http://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MagePsycho_Massimporterpro_Block_Adminhtml_Priceupdater_Edit_Tab_History extends Mage_Adminhtml_Block_Widget_Grid {
    public function __construct() {
        parent::__construct();
		$this->setId('priceupdaterGrid');
		$this->setDefaultSort('created_at');
		$this->setDefaultDir('DESC');
		$this->setSaveParametersInSession(true);
		$this->setUseAjax(true);
    }

    protected function _prepareCollection() {
		$collection = Mage::getModel('massimporterpro/massimporterpro')->getCollection();
		$collection->addFieldToFilter('import_type', 'price_updater');
		$this->setCollection($collection);
		return parent::_prepareCollection();
    }

	protected function _prepareColumns() {
		$this->addColumn('import_file', array(
			'header'    => Mage::helper('massimporterpro')->__('Imported File'),
			'align'     =>'left',
			'index'     => 'import_file',
		));

		$this->addColumn('total_rows', array(
			'header'    => Mage::helper('massimporterpro')->__('Total Rows #'),
			'align'     => 'left',
			'width'     => '80px',
			'index'     => 'total_rows',
		));

		$this->addColumn('success_rows', array(
			'header'    => Mage::helper('massimporterpro')->__('Imported Rows #'),
			'align'     =>'left',
			'width'     => '70px',
			'index'     => 'success_rows',
		));

		$this->addColumn('error_rows', array(
			'header'    => Mage::helper('massimporterpro')->__('Failure Rows #'),
			'align'     =>'left',
			'width'     => '70px',
			'index'     => 'error_rows',
		));

		$this->addColumn('skipped_rows', array(
			'header'    => Mage::helper('massimporterpro')->__('Skipped Rows #'),
			'align'     =>'left',
			'width'     => '70px',
			'index'     => 'skipped_rows',
		));

		$this->addColumn('import_duration', array(
			'header'    => Mage::helper('massimporterpro')->__('Import Duration'),
			'align'     =>'left',
			'width'     => '70px',
			'renderer'  => 'massimporterpro/adminhtml_priceupdater_edit_tab_grid_renderer_duration',
			'index'     => 'import_duration',
		));

		$this->addColumn('created_at', array(
			'header'    => Mage::helper('massimporterpro')->__('Imported Date'),
			'align'     =>'left',
			'width'     => '150px',
			'index'     => 'created_at',
			'type'      => 'datetime',
		));

		$this->addColumn('action',
			array(
				'header'    =>  Mage::helper('massimporterpro')->__('Action'),
				'width'     => '100',
				'type'      => 'action',
				'getter'    => 'getId',
				'actions'   => array(
					array(
						'caption'   => Mage::helper('massimporterpro')->__('View Log'),
						'url'       => array('base'=> '*/*/viewLog'),
						'field'     => 'id',
						'onclick'  => 'popWin(this.href, "_blank", "top:0,left:0,resizable=yes,scrollbars=yes");return false;'
					)
				),
				'filter'    => false,
				'sortable'  => false,
				'index'     => 'stores',
				'is_system' => true,
		));

		$this->addExportType('*/*/exportCsv', Mage::helper('massimporterpro')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('massimporterpro')->__('XML'));
        $this->addExportType('*/*/exportExcel', Mage::helper('massimporterpro')->__('Excel XML'));

		return parent::_prepareColumns();
    }

    public function getGridUrl() {
		return $this->getUrl('*/*/history', array('_current'=>true));
    }

    public function getRowUrl($row) {
		return false;
	}

	protected function _prepareMassaction()
    {
        $this->setMassactionIdField('priceupdater_id');
        $this->getMassactionBlock()->setFormFieldName('priceupdater');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('massimporterpro')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('massimporterpro')->__('Are you sure?')
        ));

        return $this;
    }
}