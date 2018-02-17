<?php
namespace Helper;
/**
 * Data helper
 * 
 * @package demo
 * @author Zhehai He
 * @copyright 2018
 * @version 1.0
 * @access public
 */
class Data
{

    /**
     * Use filter_var to sanitize data
     *
     * @param $aryData
     * @param $arySettingList array
     * Base format:
     * -- $arySettingList = array(
     *       'field_name' => array(
     *           [Array of attributes],
     *       ),
     *    );
     *
     * [Array of attributes] Base format:
     * -- [Array of attributes] = array(
     *      attributes_1,
     *      attributes_2,
     *      ...,
     *   ),
     *
     * PHP Data Filtering, please read http://www.php.net/manual/en/book.filter.php
     * -- Example:
     *    array(
     *      'filter'    => FILTER_SANITIZE_STRING,
     *      'options'   => FILTER_FLAG_ENCODE_HIGH|FILTER_FLAG_ENCODE_LOW,
     *    ),
     *
     * @return array
     */
    public static function filter($aryData, $arySettingList)
    {
        $aryReturn = [];
        foreach ($arySettingList as $fieldName => $arySubSettingList) {
            if (array_key_exists($fieldName, $aryData)) {
                $aryReturn[$fieldName] = trim($aryData[$fieldName]);
            } else {
                $aryReturn[$fieldName] = null;
            }
            foreach ($arySubSettingList as $key => $arySetting) {
                if (!is_array($arySetting)) {
                    continue;
                }
                $aryReturn[$fieldName] = filter_var($aryReturn[$fieldName],
                  $arySetting['filter'], $arySetting);
            }
        }

        return $aryReturn;
    }
}