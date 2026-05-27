<?php
/**
 * Se ejecuta cuando el plugin es desinstalado desde WordPress.
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

/**
 * Por ahora no eliminamos sedes ni aliados.
 *
 * Motivo:
 * - Las sedes y aliados son contenido creado por el usuario.
 * - Borrarlos automáticamente sería peligroso.
 * - Si más adelante agregamos opciones del plugin, aquí sí se limpiarían.
 */

// Ejemplo futuro:
// delete_option('st3m_cc_settings');
// delete_option('st3m_cc_version');