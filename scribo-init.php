<?php

/*
 * Plugin Name:       Initialisation des plugins Scribo
 * Plugin URI:        https://scribo-init.corbisier.fr
 * Update URI:        https://www.corbisier.fr/wordpress/
 * Description:       Plugin pour choisir la taille des images et convertir les images téléchargées en plusieurs formats.
 * Author:            Eric CORBISIER
 * Author URI:        https://corbisier.fr
 * Version:           1.02
 * Requires at least: 6.3
 * Requires PHP:      8
 * Tags:              initialisation, constante, lescorbycats, scribo
 * Text Domain:       scribo-init
 * Text Domain min:   sbi
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path:       /languages
 */


defined('ABSPATH') or die();

$plugin_file = __FILE__;

require_once plugin_dir_path(__FILE__) . 'inc/class.php';
