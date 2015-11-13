<?php
/**
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class Postcodefixer extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'postcodefixer';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Michael Dekker';
        $this->need_instance = 1;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Postcode fixer');
        $this->description = $this->l('Force postcode format NNNN LL for the Netherlands');

        $this->warning = $this->overrideWarning();
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        if (extension_loaded('pcre')) {
            return parent::install();
        }
        return false;
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    protected function overrideWarning()
    {
        $lang_id = Context::getContext()->language->id;
        $output = '';
        if (Configuration::get('PS_DISABLE_OVERRIDES') === '1') {
            $output .= $this->l('Overrides are disabled. This module doesn\'t work without overrides. Go to').' "'.
                $this->getTabName('AdminTools', $lang_id).
                ' > '.
                $this->getTabName('AdminPerformance', $lang_id).
                '" '.$this->l('and make sure that the option').' "'.
                Translate::getAdminTranslation('Disable all overrides', 'AdminPerformance').
                '" '.$this->l('is set to').' "'.
                Translate::getAdminTranslation('No', 'AdminPerformance').
                '"'.$this->l('.').'<br />';
        }
        if (Configuration::get('PS_DISABLE_NON_NATIVE_MODULE') === '1') {
            $output .= $this->l('Non native modules such as this one are disabled. Go to').
                ' "'.
                $this->getTabName('AdminTools', $lang_id).
                ' > '.
                $this->getTabName('AdminPerformance', $lang_id).
                '" '.$this->l('and make sure that the option').' "'.
                Translate::getAdminTranslation('Disable non PrestaShop modules', 'AdminPerformance').
                '" '.$this->l('is set to').' "'.
                Translate::getAdminTranslation('No', 'AdminPerformance').
                '"'.$this->l('.').'<br />';
        }
        return $output;
    }

    /**
     * Get Tab name from database
     * @param $class Class name of tab
     * @param $lang Language id
     * @return string Returns the localized tab name
     */
    protected function getTabName($class, $lang)
    {
        if ($class == null || $lang == null) {
            return '';
        }

        return Db::getInstance()->getValue(
            'SELECT `'._DB_PREFIX_.'tab_lang`.`name` FROM `'._DB_PREFIX_.'tab_lang`, `'._DB_PREFIX_.'tab` WHERE `'
            ._DB_PREFIX_.'tab`.`id_tab` = `'._DB_PREFIX_.'tab_lang`.`id_tab` AND `'._DB_PREFIX_.'tab`.`class_name` = \''
            .(string)pSql($class).'\' AND id_lang ='.(int)pSQL($lang)
        );
    }
}
