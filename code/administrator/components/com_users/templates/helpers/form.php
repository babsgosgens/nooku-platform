<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Form template helper class
 *
 * @author      Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */

class ComUsersTemplateHelperForm extends KTemplateHelperDefault
{

    /**
     * Provides a password strength check layout.
     *
     * @param array $config An optional configuration array.
     *
     * @return string The HTML layout.
     */
    public function passwcheck($config = array()) {

        $config = new KConfig($config);

        $config->append(array(
            'class'      => 'password-check',
            'input_id'   => 'password',
            'checker_id' => 'password-check',
            'score_map'  => array(
                '1' => JText::_('Very weak'),
                '2' => JText::_('Weak'),
                '3' => JText::_('Good'),
                '4' => JText::_('Strong'),
                '5' => JText::_('Very strong'))));

        $score_map = $config->score_map->toArray();

        // Add required libs and styles.
        $html = '<script src="media://com_users/js/users.js" />';
        $html .= '<style src="media://com_users/css/password.css" />';

        $html .= '<span id="' . $config->checker_id . '" class="' . $config->class . ' score0">' . JText::_('Please provide a password') . '</span>';

        // Async load of zxcvbn
        $zxcvbn_url = 'media://com_users/js/libs/zxcvbn.js';
        $html .= '<script type="text/javascript">';
        $html .= '(function(){var a;a=function(){var a,b;b=document.createElement("script");b.src="' . $zxcvbn_url . '";b.type="text/javascript";b.async=!0;a=document.getElementsByTagName("script")[0];return a.parentNode.insertBefore(b,a)};null!=window.attachEvent?window.attachEvent("onload",a):window.addEventListener("load",a,!1)}).call(this);';
        $html .= '</script>';

        $html .= '<script type="text/javascript">';
        $html .= 'window.addEvent("domready", function() {';
        $html .= '$("' . $config->input_id . '").addEvent("keyup", function() {';
        $html .= 'var score_map = ' . json_encode($score_map) . ';';
        $html .= 'var score = ComUsers.passwordScore(this.get("value")) + 1;';
        $html .= '$("' . $config->checker_id . '").set("class","' . $config->class . '" + " " + "score" + score);';
        $html .= '$("' . $config->checker_id . '").set("html", score_map[score]);';
        $html .= '});';
        $html .= '});';
        $html .= '</script>';

        return $html;
    }

}