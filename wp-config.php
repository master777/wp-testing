<?php
/** 
 * Configuración básica de WordPress.
 *
 * Este archivo contiene las siguientes configuraciones: ajustes de MySQL, prefijo de tablas,
 * claves secretas, idioma de WordPress y ABSPATH. Para obtener más información,
 * visita la página del Codex{@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} . Los ajustes de MySQL te los proporcionará tu proveedor de alojamiento web.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** Ajustes de MySQL. Solicita estos datos a tu proveedor de alojamiento web. ** //
/** El nombre de tu base de datos de WordPress */
define('DB_NAME', 'wordpress');

/** Tu nombre de usuario de MySQL */
define('DB_USER', 'root');

/** Tu contraseña de MySQL */
define('DB_PASSWORD', '');

/** Host de MySQL (es muy probable que no necesites cambiarlo) */
define('DB_HOST', 'localhost');

/** Codificación de caracteres para la base de datos. */
define('DB_CHARSET', 'utf8');

/** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
define('DB_COLLATE', '');

/**#@+
 * Claves únicas de autentificación.
 *
 * Define cada clave secreta con una frase aleatoria distinta.
 * Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
 * Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', '2h+n|b;]zZe /DP1<N/>=G~gqjN93?UGDMY!P@oE}k|OZ0ubSB>n.I+%U6 ]@dn ');
define('SECURE_AUTH_KEY', '~PCqD6l92D#(=#G0JM&z1&GT<m*S|[D~CBC*Ju<?)G@bUmx#{|zNp1J/jsHn,qYC');
define('LOGGED_IN_KEY', 'ekA3c1W{2@f~[Y#<.#w$eEy`2|69+l;gM6I#0k}^gA%/%}E:+Es,sINsj*(Oe`zk');
define('NONCE_KEY', 'hG$Wuz-Eud[Jo$|VZ&]zi-zu8@i6du(`07d(Z|A|+~+rfbu9D1FvlL*)|N85%LMO');
define('AUTH_SALT', '3asfNg4`b*^Q8|.tNM!lTDv{IS|>o37Ex^Hq>@8I|J1WOO0*-)<wE-s^xXe8sKr7');
define('SECURE_AUTH_SALT', '>i6>W9E[ItH4p[!YMr`PO0_s-t`,NsWFfvX:g>gR<6U%<:BDs+@>[y=Nn<j.z[*@');
define('LOGGED_IN_SALT', 'X}9%`b|DZ%q6i53Vbb}+ZzRq+HtzsNa0=&`(L};V+yz3R!N ^5]wfgw 1-pu$+|L');
define('NONCE_SALT', 'k1$*=$W18;l_C8IR<$d3B&22Sa9?j-:bLJ0o~q_JA`QCuN5CJYrMD[fm>2rZ&:*o');

/**#@-*/

/**
 * Prefijo de la base de datos de WordPress.
 *
 * Cambia el prefijo si deseas instalar multiples blogs en una sola base de datos.
 * Emplea solo números, letras y guión bajo.
 */
$table_prefix  = 'wp_';


/**
 * Para desarrolladores: modo debug de WordPress.
 *
 * Cambia esto a true para activar la muestra de avisos durante el desarrollo.
 * Se recomienda encarecidamente a los desarrolladores de temas y plugins que usen WP_DEBUG
 * en sus entornos de desarrollo.
 */
define('WP_DEBUG', false);

/* ¡Eso es todo, deja de editar! Feliz blogging */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

