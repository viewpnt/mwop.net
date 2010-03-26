<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Db
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */


/**
 * require other test files needed, this will
 * ensure that Zend_Loader::loadClass is not called
 */


PHPUnit_Util_Filter::addFileToFilter(__FILE__);


/**
 * @category   Zend
 * @package    Zend_Db
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Db_Table_Asset_TableBugsProductsCustom extends Zend_Db_Table_Asset_TableBugsProducts
{
    protected $_rowClass    = 'Zend_Db_Table_Asset_Row_TestMyRow';
    protected $_rowsetClass = 'Zend_Db_Table_Asset_Rowset_TestMyRowset';

    protected $_referenceMap    = array(
        'Bug' => array(
            'columns'           => 'bug_id',
            'refTableClass'     => 'Zend_Db_Table_Asset_TableBugsCustom',
            'refColumns'        => 'bug_id',
            'onDelete'          => self::CASCADE,
            'onUpdate'          => self::CASCADE
        ),
        'Product' => array(
            'columns'           => 'product_id',
            'refTableClass'     => 'Zend_Db_Table_Asset_TableProductsCustom',
            'refColumns'        => 'product_id',
            'onDelete'          => 'anything but self::CASCADE',
            'onUpdate'          => 'anything but self::CASCADE'
        )
    );
}