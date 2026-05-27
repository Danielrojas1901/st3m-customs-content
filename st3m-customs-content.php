<?php
/**
 * Plugin Name: ST3M Customs Content
 * Plugin URI: 
 * Description: Plugin personalizado para gestionar contenidos dinámicos reutilizables.
 * Version: 1.0.1
 * Author: ST3M 
 * Text Domain: st3m-customs-content
 * Update URI: https://github.com/Danielrojas1901/st3m-customs-content
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Constantes base del plugin.
 */
if (!defined('ST3M_CC_VERSION')) {
    define('ST3M_CC_VERSION', '1.0.1');
}

if (!defined('ST3M_CC_FILE')) {
    define('ST3M_CC_FILE', __FILE__);
}

if (!defined('ST3M_CC_PATH')) {
    define('ST3M_CC_PATH', plugin_dir_path(__FILE__));
}

if (!defined('ST3M_CC_URL')) {
    define('ST3M_CC_URL', plugin_dir_url(__FILE__));
}

/**
 * Plugin Update Checker.
 */
require_once ST3M_CC_PATH . 'vendor/plugin-update-checker/plugin-update-checker.php';

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$st3m_update_checker = PucFactory::buildUpdateChecker(
    'https://github.com/Danielrojas1901/st3m-customs-content/',
    ST3M_CC_FILE,
    'st3m-customs-content'
);


/**
 * Ejecuta tareas al activar el plugin.
 */
function st3m_cc_activate() {
    st3m_register_cpt_sedes();
    st3m_register_cpt_aliados();

    flush_rewrite_rules();
}
register_activation_hook(ST3M_CC_FILE, 'st3m_cc_activate');

/**
 * Ejecuta tareas al desactivar el plugin.
 */
function st3m_cc_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(ST3M_CC_FILE, 'st3m_cc_deactivate');

/**
 * Renderiza un template interno del plugin.
 *
 * @param string $template_path Ruta relativa dentro del plugin.
 * @param array  $variables Variables disponibles para el template.
 *
 * @return string
 */
function st3m_render_template($template_path, $variables = array()) {

    $full_path = ST3M_CC_PATH . $template_path;

    if (!file_exists($full_path)) {
        return '';
    }

    if (!empty($variables) && is_array($variables)) {
        extract($variables, EXTR_SKIP);
    }

    ob_start();
    include $full_path;
    return ob_get_clean();
}

/*===============================
   SEDES
===============================*/

/**
 * Registra el Custom Post Type: Sedes.
 */
function st3m_register_cpt_sedes() {

    $labels = array(
        'name'                  => 'Sedes',
        'singular_name'         => 'Sede',
        'menu_name'             => 'Sedes',
        'name_admin_bar'        => 'Sede',
        'add_new'               => 'Añadir nueva',
        'add_new_item'          => 'Añadir nueva sede',
        'new_item'              => 'Nueva sede',
        'edit_item'             => 'Editar sede',
        'view_item'             => 'Ver sede',
        'all_items'             => 'Todas las sedes',
        'search_items'          => 'Buscar sedes',
        'not_found'             => 'No se encontraron sedes',
        'not_found_in_trash'    => 'No se encontraron sedes en la papelera',
    );

    $args = array(
        'labels'                => $labels,
        'public'                => true,
        'publicly_queryable'    => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'query_var'             => true,
        'rewrite'               => array('slug' => 'sedes'),
        'capability_type'       => 'post',
        'has_archive'           => true,
        'hierarchical'          => false,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-location-alt',
        'supports'              => array('title', 'thumbnail'),
        'show_in_rest'          => true,
    );

    register_post_type('st3m_sede', $args);
}
add_action('init', 'st3m_register_cpt_sedes');

/**
 * Registra la metabox de información de sedes.
 */
function st3m_add_metabox_sedes() {

    add_meta_box(
        'st3m_sede_info',
        'Información de la sede',
        'st3m_render_metabox_sedes',
        'st3m_sede',
        'normal',
        'default'
    );
}

add_action('add_meta_boxes', 'st3m_add_metabox_sedes');


/**
 * Renderiza los campos personalizados de la sede.
 */
function st3m_render_metabox_sedes($post) {

    wp_nonce_field('st3m_save_sede_info', 'st3m_sede_nonce');

    $ciudad = get_post_meta($post->ID, '_st3m_ciudad', true);
    $estado = get_post_meta($post->ID, '_st3m_estado', true);
    $direccion = get_post_meta($post->ID, '_st3m_direccion', true);
    $telefono = get_post_meta($post->ID, '_st3m_telefono', true);
    $email = get_post_meta($post->ID, '_st3m_email', true);
    $horario = get_post_meta($post->ID, '_st3m_horario', true);
    $mapa_url = get_post_meta($post->ID, '_st3m_mapa_url', true);

    ?>

    <p>
        <label for="st3m_ciudad"><strong>Ciudad</strong></label>
        <input 
            type="text" 
            id="st3m_ciudad" 
            name="st3m_ciudad"
            value="<?php echo esc_attr($ciudad); ?>"
            style="width:100%;"
            placeholder="Ej: Barquisimeto, Caracas..."
        >
    </p>

    <p>
        <label for="st3m_estado"><strong>Estado</strong></label>
        <input 
            type="text" 
            id="st3m_estado" 
            name="st3m_estado"
            value="<?php echo esc_attr($estado); ?>"
            style="width:100%;"
            placeholder="Ej: Lara, Distrito Capital..."
        >
    </p>

    <p>
        <label for="st3m_direccion"><strong>Dirección</strong></label>
        <input 
            type="text" 
            id="st3m_direccion" 
            name="st3m_direccion"
            value="<?php echo esc_attr($direccion); ?>"
            style="width:100%;"
            placeholder="Ej: Calle Principal, Av. Libertador..."
        >
    </p>

    <p>
        <label for="st3m_telefono"><strong>Teléfono</strong></label>
        <input 
            type="text" 
            id="st3m_telefono" 
            name="st3m_telefono"
            value="<?php echo esc_attr($telefono); ?>"
            style="width:100%;"
            placeholder="Ej: 0212-1234567"
        >
    </p>
    <p>
    <label for="st3m_email"><strong>Email</strong></label>
    <input 
        type="email" 
        id="st3m_email" 
        name="st3m_email"
        value="<?php echo esc_attr($email); ?>"
        style="width:100%;"
        placeholder="Ej: info@miempresa.com"
    >
    </p>

    <p>
        <label for="st3m_horario"><strong>Horario</strong></label>
        <textarea
            id="st3m_horario"
            name="st3m_horario"
            rows="3"
            style="width:100%;"
        ><?php echo esc_textarea($horario); ?></textarea>
    </p>

    <p>
        <label for="st3m_mapa_url"><strong>URL del mapa</strong></label>
        <input 
            type="url" 
            id="st3m_mapa_url" 
            name="st3m_mapa_url"
            value="<?php echo esc_url($mapa_url); ?>"
            style="width:100%;"
            placeholder="https://maps.google.com/..."
        >
    </p>

    <?php
}


/**
 * Guarda los campos personalizados de la sede.
 */
function st3m_save_metabox_sedes($post_id) {

    /**
     * Verifica si existe el nonce.
     */
    if (!isset($_POST['st3m_sede_nonce'])) {
        return;
    }

    /**
     * Verifica nonce de seguridad.
     */
    if (!wp_verify_nonce($_POST['st3m_sede_nonce'], 'st3m_save_sede_info')) {
        return;
    }

    /**
     * Evita guardar durante autosave.
     */
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    /**
     * Verifica permisos del usuario.
     */
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    /**
     * Guarda ciudad.
     */
    if (isset($_POST['st3m_ciudad'])) {
        update_post_meta(
            $post_id,
            '_st3m_ciudad',
            sanitize_text_field($_POST['st3m_ciudad'])
        );
    }

    /**
     * Guarda estado.
     */
    if (isset($_POST['st3m_estado'])) {
        update_post_meta(
            $post_id,
            '_st3m_estado',
            sanitize_text_field($_POST['st3m_estado'])
        );
    }

    /**
     * Guarda dirección.
     */
    if (isset($_POST['st3m_direccion'])) {
        update_post_meta(
            $post_id,
            '_st3m_direccion',
            sanitize_text_field($_POST['st3m_direccion'])
        );
    }

    /**
     * Guarda teléfono.
     */
    if (isset($_POST['st3m_telefono'])) {
        update_post_meta(
            $post_id,
            '_st3m_telefono',
            sanitize_text_field($_POST['st3m_telefono'])
        );
    }

    /**
     * Guarda email.
     */
    if (isset($_POST['st3m_email'])) {
        update_post_meta(
            $post_id,
            '_st3m_email',
            sanitize_email($_POST['st3m_email'])
        );
    }

    /**
     * Guarda horario.
     */
    if (isset($_POST['st3m_horario'])) {
        update_post_meta(
            $post_id,
            '_st3m_horario',
            sanitize_textarea_field($_POST['st3m_horario'])
        );
    }

    /**
     * Guarda URL del mapa.
     */
    if (isset($_POST['st3m_mapa_url'])) {
        update_post_meta(
            $post_id,
            '_st3m_mapa_url',
            esc_url_raw($_POST['st3m_mapa_url'])
        );
    }
}

add_action('save_post', 'st3m_save_metabox_sedes');


/**
 * Shortcode para mostrar sedes en formato de cards.
 *
 * Uso:
 * [st3m_sedes]
 */
function st3m_shortcode_sedes($atts) {

    $args = array(
        'post_type'      => 'st3m_sede',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    $query = new WP_Query($args);

    ob_start();

    if ($query->have_posts()) : ?>

        <div class="st3m-sedes-grid">

            <?php while ($query->have_posts()) : $query->the_post();

                $ciudad    = get_post_meta(get_the_ID(), '_st3m_ciudad', true);
                $estado    = get_post_meta(get_the_ID(), '_st3m_estado', true);
                $direccion = get_post_meta(get_the_ID(), '_st3m_direccion', true);
                $telefono  = get_post_meta(get_the_ID(), '_st3m_telefono', true);
                $email     = get_post_meta(get_the_ID(), '_st3m_email', true);
                $horario   = get_post_meta(get_the_ID(), '_st3m_horario', true);
                $mapa_url  = get_post_meta(get_the_ID(), '_st3m_mapa_url', true);
                
                
                $card_image = '';

                if (has_post_thumbnail()) {
                    $card_image = get_the_post_thumbnail(get_the_ID(), 'large');
                }

                $card_location = trim($ciudad . ', ' . $estado, ', ');

                echo st3m_render_template(
                    'templates/components/content-card.php',
                    array(
                        'card_title'       => get_the_title(),
                        'card_image'       => $card_image,
                        'card_subtitle'    => $card_location,
                        'card_description' => '',
                        'card_address'     => $direccion,
                        'card_location'    => '',
                        'card_phone'       => $telefono,
                        'card_email'       => $email,
                        'card_schedule'    => $horario,
                        'card_show_button' => !empty($mapa_url),
                        'card_button_url'  => $mapa_url,
                        'card_button_text' => 'Ver en mapa',
                    )
                );
                

             endwhile; ?>

        </div>

    <?php else : ?>

        <p>No hay sedes registradas actualmente.</p>

    <?php endif;

    wp_reset_postdata();

    return ob_get_clean();
}

add_shortcode('st3m_sedes', 'st3m_shortcode_sedes');

/*===================================
   ALIADOS
===================================*/

/**
 * Registra el Custom Post Type: Aliados.
 */
function st3m_register_cpt_aliados() {

    $labels = array(
        'name'                  => 'Aliados',
        'singular_name'         => 'Aliado',
        'menu_name'             => 'Aliados',
        'name_admin_bar'        => 'Aliado',
        'add_new'               => 'Añadir nuevo',
        'add_new_item'          => 'Añadir nuevo aliado',
        'new_item'              => 'Nuevo aliado',
        'edit_item'             => 'Editar aliado',
        'view_item'             => 'Ver aliado',
        'all_items'             => 'Todos los aliados',
        'search_items'          => 'Buscar aliados',
        'not_found'             => 'No se encontraron aliados',
        'not_found_in_trash'    => 'No se encontraron aliados en la papelera',
    );

    $args = array(
        'labels'                => $labels,
        'public'                => true,
        'publicly_queryable'    => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'query_var'             => true,
        'rewrite'               => array('slug' => 'aliados'),
        'capability_type'       => 'post',
        'has_archive'           => true,
        'hierarchical'          => false,
        'menu_position'         => 21,
        'menu_icon'             => 'dashicons-groups',
        'supports'              => array('title', 'thumbnail'),
        'show_in_rest'          => true,
    );

    register_post_type('st3m_aliado', $args);
}
add_action('init', 'st3m_register_cpt_aliados');

/**
 * Agrega la metabox para aliados.
 */
function st3m_add_metabox_aliados() {

    add_meta_box(
        'st3m_aliado_info',
        'Información del aliado',
        'st3m_render_metabox_aliados',
        'st3m_aliado',
        'normal',
        'default'
    );
}

add_action('add_meta_boxes', 'st3m_add_metabox_aliados');



/**
 * Renderiza la metabox de aliados.
 */
function st3m_render_metabox_aliados($post) {

    wp_nonce_field(
        'st3m_save_aliado_info',
        'st3m_aliado_nonce'
    );

    $tipo = get_post_meta($post->ID, '_st3m_aliado_tipo', true);

    $descripcion = get_post_meta(
        $post->ID,
        '_st3m_aliado_descripcion',
        true
    );

    $ubicacion = get_post_meta(
        $post->ID,
        '_st3m_aliado_ubicacion',
        true
    );

    $telefono = get_post_meta(
        $post->ID,
        '_st3m_aliado_telefono',
        true
    );

    $email = get_post_meta(
        $post->ID,
        '_st3m_aliado_email',
        true
    );

    $mostrar_boton = get_post_meta(
        $post->ID,
        '_st3m_aliado_mostrar_boton',
        true
    );

    $boton_texto = get_post_meta(
        $post->ID,
        '_st3m_aliado_boton_texto',
        true
    );

    $boton_url = get_post_meta(
        $post->ID,
        '_st3m_aliado_boton_url',
        true
    );
?>

    <p>
        <label for="st3m_aliado_tipo">
            <strong>Tipo de aliado</strong>
        </label>

        <input
            type="text"
            id="st3m_aliado_tipo"
            name="st3m_aliado_tipo"
            value="<?php echo esc_attr($tipo); ?>"
            style="width:100%;"
            placeholder="Ej: Institución educativa, empresa, ONG..."
        >
    </p>

    <p>
        <label for="st3m_aliado_descripcion">
            <strong>Descripción corta</strong>
        </label>

        <textarea
            id="st3m_aliado_descripcion"
            name="st3m_aliado_descripcion"
            rows="4"
            style="width:100%;"
            placeholder="Breve descripción del aliado, su misión, visión o relación con la comunidad."
        ><?php echo esc_textarea($descripcion); ?></textarea>
    </p>

    <p>
        <label for="st3m_aliado_ubicacion">
            <strong>Ubicación</strong>
        </label>

        <input
            type="text"
            id="st3m_aliado_ubicacion"
            name="st3m_aliado_ubicacion"
            value="<?php echo esc_attr($ubicacion); ?>"
            style="width:100%;"
            placeholder="Ej: Barquisimeto, Caracas, Venezuela..."
        >
    </p>

    <p>
        <label for="st3m_aliado_telefono">
            <strong>Teléfono</strong>
        </label>

        <input
            type="text"
            id="st3m_aliado_telefono"
            name="st3m_aliado_telefono"
            value="<?php echo esc_attr($telefono); ?>"
            style="width:100%;"
            placeholder="Ej: 0212-1234567"
        >
    </p>

    <p>
        <label for="st3m_aliado_email">
            <strong>Email</strong>
        </label>

        <input
            type="email"
            id="st3m_aliado_email"
            name="st3m_aliado_email"
            value="<?php echo esc_attr($email); ?>"
            style="width:100%;"
            placeholder="Ej: info@miempresa.com"
        >
    </p>

    <hr>

    <p>
        <label>
            <input
                type="checkbox"
                name="st3m_aliado_mostrar_boton"
                value="1"
                <?php checked($mostrar_boton, '1'); ?>
            >

            Mostrar botón
        </label>
    </p>

    <p>
        <label for="st3m_aliado_boton_texto">
            <strong>Texto del botón</strong>
        </label>

        <input
            type="text"
            id="st3m_aliado_boton_texto"
            name="st3m_aliado_boton_texto"
            value="<?php echo esc_attr($boton_texto); ?>"
            style="width:100%;"
            placeholder="Ej: Conócenos, ver mapa...."
        >
    </p>

    <p>
        <label for="st3m_aliado_boton_url">
            <strong>URL del botón</strong>
        </label>

        <input
            type="url"
            id="st3m_aliado_boton_url"
            name="st3m_aliado_boton_url"
            value="<?php echo esc_url($boton_url); ?>"
            style="width:100%;"
        >
    </p>

<?php
}

/**
 * Guarda los campos personalizados de aliados.
 */
function st3m_save_metabox_aliados($post_id) {

    if (!isset($_POST['st3m_aliado_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['st3m_aliado_nonce'], 'st3m_save_aliado_info')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (get_post_type($post_id) !== 'st3m_aliado') {
        return;
    }

    if (isset($_POST['st3m_aliado_tipo'])) {
        update_post_meta(
            $post_id,
            '_st3m_aliado_tipo',
            sanitize_text_field($_POST['st3m_aliado_tipo'])
        );
    }

    if (isset($_POST['st3m_aliado_descripcion'])) {
        update_post_meta(
            $post_id,
            '_st3m_aliado_descripcion',
            sanitize_textarea_field($_POST['st3m_aliado_descripcion'])
        );
    }

    if (isset($_POST['st3m_aliado_ubicacion'])) {
        update_post_meta(
            $post_id,
            '_st3m_aliado_ubicacion',
            sanitize_text_field($_POST['st3m_aliado_ubicacion'])
        );
    }

    if (isset($_POST['st3m_aliado_telefono'])) {
        update_post_meta(
            $post_id,
            '_st3m_aliado_telefono',
            sanitize_text_field($_POST['st3m_aliado_telefono'])
        );
    }

    if (isset($_POST['st3m_aliado_email'])) {
        update_post_meta(
            $post_id,
            '_st3m_aliado_email',
            sanitize_email($_POST['st3m_aliado_email'])
        );
    }

    $mostrar_boton = isset($_POST['st3m_aliado_mostrar_boton']) ? '1' : '0';

    update_post_meta(
        $post_id,
        '_st3m_aliado_mostrar_boton',
        $mostrar_boton
    );

    if (isset($_POST['st3m_aliado_boton_texto'])) {
        update_post_meta(
            $post_id,
            '_st3m_aliado_boton_texto',
            sanitize_text_field($_POST['st3m_aliado_boton_texto'])
        );
    }

    if (isset($_POST['st3m_aliado_boton_url'])) {
        update_post_meta(
            $post_id,
            '_st3m_aliado_boton_url',
            esc_url_raw($_POST['st3m_aliado_boton_url'])
        );
    }
}

add_action('save_post', 'st3m_save_metabox_aliados');

/**
 * Shortcode para mostrar aliados en formato de cards.
 *
 * Uso:
 * [st3m_aliados]
 */
function st3m_shortcode_aliados($atts) {

    $args = array(
        'post_type'      => 'st3m_aliado',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    $query = new WP_Query($args);

    ob_start();

    if ($query->have_posts()) : ?>

        <div class="st3m-aliados-grid">

            <?php while ($query->have_posts()) : $query->the_post();

                $tipo           = get_post_meta(get_the_ID(), '_st3m_aliado_tipo', true);
                $descripcion    = get_post_meta(get_the_ID(), '_st3m_aliado_descripcion', true);
                $ubicacion      = get_post_meta(get_the_ID(), '_st3m_aliado_ubicacion', true);
                $telefono       = get_post_meta(get_the_ID(), '_st3m_aliado_telefono', true);
                $email          = get_post_meta(get_the_ID(), '_st3m_aliado_email', true);
                $mostrar_boton  = get_post_meta(get_the_ID(), '_st3m_aliado_mostrar_boton', true);
                $boton_texto    = get_post_meta(get_the_ID(), '_st3m_aliado_boton_texto', true);
                $boton_url      = get_post_meta(get_the_ID(), '_st3m_aliado_boton_url', true);

                $card_image = '';

                if (has_post_thumbnail()) {

                    $card_image = get_the_post_thumbnail(
                        get_the_ID(),
                        'large'
                    );

                } else {

                    $default_image = ST3M_CC_URL . 'assets/images/default-ally.png';

                    $card_image = sprintf(
                        '<img src="%s" alt="%s">',
                        esc_url($default_image),
                        esc_attr(get_the_title())
                    );
                }

                echo st3m_render_template(
                    'templates/components/content-card.php',
                    array(
                        'card_title'       => get_the_title(),
                        'card_image'       => $card_image,
                        'card_subtitle'    => $tipo,
                        'card_description' => $descripcion,
                        'card_address'     => '',
                        'card_location'    => $ubicacion,
                        'card_phone'       => $telefono,
                        'card_email'       => $email,
                        'card_schedule'    => '',
                        'card_show_button' => $mostrar_boton === '1',
                        'card_button_url'  => $boton_url,
                        'card_button_text' => $boton_texto ?: 'Ver aliado',
                        'card_variant' => 'compact',
                    )
                );

            endwhile; ?>

        </div>

    <?php else : ?>

        <p>No hay aliados registrados actualmente.</p>

    <?php endif;

    wp_reset_postdata();

    return ob_get_clean();
}

add_shortcode('st3m_aliados', 'st3m_shortcode_aliados');


/**
 * Carga los estilos del plugin en el frontend.
 */
function st3m_enqueue_frontend_assets() {

    wp_enqueue_style(
        'st3m-cards',
        ST3M_CC_URL . 'assets/css/st3m-cards.css',
        array(),
        ST3M_CC_VERSION
    );
}

add_action('wp_enqueue_scripts', 'st3m_enqueue_frontend_assets');