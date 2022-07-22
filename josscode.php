<?php

/**
 * Joss Code
 *
 * @package     JossCode
 * @author      Henri Susanto
 * @copyright   2022 Henri Susanto
 * @license     GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: Joss Code
 * Plugin URI:  https://github.com/susantohenri
 * Description: This plugin generate code under post
 * Version:     1.0.0
 * Author:      Henri Susanto
 * Author URI:  https://github.com/susantohenri
 * Text Domain: joss-code
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

add_action('the_content', function ($content) {
    $countdownlimit = get_option('countdownlimit');
    $countdownlimit = !$countdownlimit ? 10 : $countdownlimit;
    $codeprefix = get_option('codeprefix');
    $codeprefix = !$codeprefix ? '' : $codeprefix;
    $content .= "
        <style type='text/css'>
            #josscode {text-align: center}
            #josscode_timer {font-size: 200%}
            #josscode_code {display: none}
            #josscode_button {display: none; margin: 0 auto}
        </style>

        <div id='josscode'>
            <b id='josscode_timer'></b>
            <b id='josscode_code'></b>
            <button id='josscode_button' onclick='josscode_toggleshow()'>show</button>
        </div>

        <script type='text/javascript'>
            var sec = {$countdownlimit};
            var josscode_countdowntimer = setInterval(function() {
                document.getElementById('josscode_timer').innerHTML = sec;
                sec--;
                if (sec == 00) josscode_showcode();
            }, 1000);

            function josscode_showcode () {
                clearInterval(josscode_countdowntimer)
                document.getElementById('josscode_code').innerHTML = '{$codeprefix}' + (Math.random() + 1).toString(36).substring(6).toUpperCase()
                document.getElementById('josscode_timer').style = 'display: none'
                document.getElementById('josscode_button').style = 'display: block'
            }

            function josscode_toggleshow () {
                if ('show' === document.getElementById('josscode_button').innerHTML) {
                    document.getElementById('josscode_code').style = 'display: block'
                    document.getElementById('josscode_button').innerHTML = 'hide'
                } else if ('hide' === document.getElementById('josscode_button').innerHTML) {
                    document.getElementById('josscode_code').style = 'display: none'
                    document.getElementById('josscode_button').innerHTML = 'show'
                }
            }
        </script>
    ";
    return $content;
});

add_action('admin_menu', function () {
    add_menu_page('Joss Code', 'Joss Code', 'administrator', __FILE__, function () {
    ?>
        <div class="wrap">
            <h1>Halaman Pengaturan Joss Code</h1>
            <form method="post" action="options.php">
                <?php settings_fields('josscode-settings-group'); ?>
                <?php do_settings_sections('josscode-settings-group'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Countdown Time Limit</th>
                        <td><input type="text" name="countdownlimit" value="<?php echo esc_attr(get_option('countdownlimit')); ?>" /></td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">Code Prefix</th>
                        <td><input type="text" name="codeprefix" value="<?php echo esc_attr(get_option('codeprefix')); ?>" /></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
    <?php
    }, '');
    add_action('admin_init', function () {
        register_setting('josscode-settings-group', 'countdownlimit');
        register_setting('josscode-settings-group', 'codeprefix');
    });
});
