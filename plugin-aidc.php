<?php
/*
Plugin Name: AIDC
Plugin URI: https://github.com/guillermolopez3
description: Plugin para gestionar las acreditaciones de la web de aidc
Version: 1.0
Author: Belop
Author URI: www.belop.com.ar
License: GPL2
*/

define('ESTILOS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('BELOP_AIDC_PLUGIN_PATH', plugin_dir_path(__FILE__));

//CPT PARA LAS acreditaciones
if ( ! function_exists('acreditaciones') ) {

    // Register Custom Post Type
    function acreditaciones() {
    
        $labels = array(
            'name'                  => 'Acreditaciones',
            'singular_name'         => 'Acreditación', 
            'menu_name'             => 'Acreditaciones',
            'name_admin_bar'        => 'Acreditaciones',
            'archives'              => 'Item Archives',
            'attributes'            => 'Item Attributes',
            //'parent_item_colon'     => 'Parent Item:',
            'all_items'             => 'Todas las Acreditaciones','add_new'=> 'Nueva Acreditación',
            'add_new_item'          => 'Nueva Acreditación', //label que aparece arriba
            'new_item'              => 'Nueva Acreditación',
            'edit_item'             => 'Editar Acreditación',
            'update_item'           => 'Actualizar Acreditación',
            'view_item'             => 'Ver Acreditación',
            'view_items'            => 'Ver Acreditación',
            'search_items'          => 'Buscar Acreditación',
            'not_found'             => 'No hay coincidenica',
            'not_found_in_trash'    => 'No se encontró en la papelera',
            'featured_image'        => 'Imagen destacada',
            'set_featured_image'    => 'Seleccionar imagen destacada',
            'remove_featured_image' => 'Quitar Imagen destacada',
            'use_featured_image'    => 'Usar como imagen destacada',
            'insert_into_item'      => 'Insert into item',
            'uploaded_to_this_item' => 'Actualizar este item',
            'items_list'            => 'Items list',
            'items_list_navigation' => 'Items list navigation',
            'filter_items_list'     => 'Filter items list',
        );
        $args = array(
            'label'                 => 'Acreditación',
            'menu_icon'             => 'dashicons-welcome-learn-more',
            'description'           => 'Acreditación',
            'labels'                => $labels,
            'show_in_rest'          => true,
            'supports'              => array('title','revisions','author',),
            'public'                => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'can_export'            => true,
            'publicly_queryable'    => true, //puedo traerlos con un custom query
            'rewrite'               => true,
            'has_archive'           =>true,
            'hierarchical'          =>true,
        );
        register_post_type( 'acreditacion', $args );
    
    }
    add_action( 'init', 'acreditaciones', 0 );
}



//metabox para la ubicacion
function acreditacion_metabox() {
    add_meta_box( 'hacienda-metabox', 'Información de la acreditación', 'campos_remate', 'acreditacion', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'acreditacion_metabox' );

//agrego la logica y el html para los metadata de la acreditacion
function campos_remate($post){
    wp_nonce_field( basename( __FILE__ ), 'prfx_nonce' );
    $pasaporte = get_post_meta( $post->ID, 'aidc-pasaporte', true );
    $mail = get_post_meta( $post->ID, 'aidc-mail', true );
    $tel = get_post_meta( $post->ID, 'aidc-tel', true );
    $pais = get_post_meta( $post->ID, 'aidc-pais', true );
    $fecha = get_post_meta( $post->ID, 'aidc-fecha', true );
    $fecha_baja = get_post_meta( $post->ID, 'aidc-fecha-baja', true );
    $codigo = get_post_meta( $post->ID, 'aidc-codigo', true );
    $competencias = get_post_meta( $post->ID, 'aidc-competencias', true );
    
    ?>
 
    <table width="100%" cellpadding="1" cellspacing="1" border="0">
        <tr>
        <td><strong>Pasaporte</strong></td>
        <td><input type="text" name="aidc-pasaporte" value="<?php echo sanitize_text_field($pasaporte);?>" class="text" placeholder="número de pasaporte" /></td>
        </tr>
        <tr>
        <td><strong>E-mail </strong></td>
        <td><input type="email" name="aidc-mail" value="<?php echo sanitize_text_field($mail);?>" class="large-text" placeholder="e-mail" /></td>
        </tr>
        <tr>
        <td><strong>Teléfono </strong></td>
        <td><input type="number" name="aidc-tel" value="<?php echo sanitize_text_field($tel);?>" class="text" placeholder="Teléfono" /></td>
        </tr>
        <tr>
        <td><strong>País </strong></td>
        <td><input type="text" name="aidc-pais" value="<?php echo sanitize_text_field($pais);?>" class="text" placeholder="País" /></td>
        </tr>
        <tr>
        <td width="20%"><strong>Fecha de alta </strong><br /></td>
        <td width="80%"><input type="date" name="aidc-fecha" value="<?php echo sanitize_text_field($fecha);?>" /></td>
        </tr>
        <tr>
        <td width="20%"><strong>Fecha del expiración </strong><br /></td>
        <td width="80%"><input type="date" name="aidc-fecha-baja" value="<?php echo sanitize_text_field($fecha_baja);?>" /></td>
        </tr>
        <tr>
        <td><strong>Código del curso </strong></td>
        <td><input type="text" name="aidc-codigo" value="<?php echo sanitize_text_field($codigo);?>" class="large-text" placeholder="Código" /></td>
        </tr>
        <tr>
        <td><strong>Competencias Adquiridas </strong></td>
        <td><input type="text" name="aidc-competencias" value="<?php echo sanitize_text_field($competencias);?>" class="large-text" placeholder="Ingrese las competencias separándolas por coma" /></td>
        </tr>
    </table>
<?php 
}

//Guardo los datos de los metabox
function save_acretitac( $post_id ) {
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'prfx_nonce' ] ) && wp_verify_nonce( $_POST[ 'prfx_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
 
    
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }

 
    if( isset( $_POST[ 'aidc-pasaporte' ] ) ) {
        update_post_meta( $post_id, 'aidc-pasaporte', $_POST[ 'aidc-pasaporte' ] );
    } 
    if( isset( $_POST[ 'aidc-mail' ] ) ) {
        update_post_meta( $post_id, 'aidc-mail', $_POST[ 'aidc-mail' ] );
    }
    if( isset( $_POST[ 'aidc-tel' ] ) ) {
        update_post_meta( $post_id, 'aidc-tel', $_POST[ 'aidc-tel' ] );
    }
    if( isset( $_POST[ 'aidc-pais' ] ) ) {
        update_post_meta( $post_id, 'aidc-pais', $_POST[ 'aidc-pais' ] );
    }
    if( isset( $_POST[ 'aidc-fecha' ] ) ) {
        update_post_meta( $post_id, 'aidc-fecha', $_POST[ 'aidc-fecha' ] );
    } 
    if( isset( $_POST[ 'aidc-fecha-baja' ] ) ) {
        update_post_meta( $post_id, 'aidc-fecha-baja', $_POST[ 'aidc-fecha-baja' ] );
    }
    if( isset( $_POST[ 'aidc-codigo' ] ) ) {
        update_post_meta( $post_id, 'aidc-codigo', $_POST[ 'aidc-codigo' ] );
    } 
    if( isset( $_POST[ 'aidc-competencias' ] ) ) {
        update_post_meta( $post_id, 'aidc-competencias', $_POST[ 'aidc-competencias' ] );
    }
 
}
add_action( 'save_post', 'save_acretitac' );



//cambia el título de agregar nuevo post a Nombre y apellido
add_filter('gettext','custom_enter_title');
function custom_enter_title( $input ) {
    global $post_type;
    if( is_admin() && 'Añadir el título' == $input && 'acreditacion' == $post_type )
        return 'Ingrese Nombre y Apellido del acreditado';
    return $input;
}

//enqueque estilos y js
function assets_aidc(){
    //boostrap
    wp_enqueue_style('bootstrap','https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css',null,'1');
    wp_enqueue_style('font_asesome','https://use.fontawesome.com/releases/v5.0.7/css/all.css',null,'1');
    wp_enqueue_style('estilos',  ESTILOS_PLUGIN_URL .'css/estilos.css', null, '1');

    //alertify
    wp_enqueue_style('alertify','https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css',null,'1');
    wp_enqueue_style('alertify_1','https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css',null,'1');
    
    //bootstrap js
    wp_enqueue_script( 'boot2','https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js', array( 'jquery' ),'',true );
    wp_enqueue_script( 'boot3','https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js', array( 'jquery' ),'',true );

    //alertifi js
    wp_enqueue_script( 'alertify_js','//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js', array( 'jquery' ),'',true );
    //mi js
    wp_enqueue_script( 'mijs',ESTILOS_PLUGIN_URL .'js/app.js', array( 'jquery' ),'',true );

    //wp_localize_script('ajax_script', 'aidc_vars', ['ajaxurl'=> admin_url('admin-ajax.php')]);

}
add_action('wp_enqueue_scripts','assets_aidc');


//shortcode para el hero
add_shortcode('search_acreditados', 'acreditados_template');

function acreditados_template(){
    $url = BELOP_AIDC_PLUGIN_PATH .'templates/search.php';
    require_once ("$url");
}

function my_enqueue_scripts() {
    wp_enqueue_script('jquery');
    wp_localize_script( 'jquery', 'aidc_vars', array(
        'ajaxurl'       => admin_url( 'admin-ajax.php' ),
        )
    );
}
add_action('wp_enqueue_scripts','my_enqueue_scripts');

//ajax
add_action('wp_ajax_filterAcreditados', 'filter_acreditados');
add_action('wp_ajax_nopriv_filterAcreditados', 'filter_acreditados');

function filter_acreditados(){
    global $wpdb;
    if(isset( $_POST['busqueda']) && !empty( $_POST['busqueda'] ) ){
        //busqueda por titulo del post y por la metadata
        $q = $_POST['busqueda'];
        $query = 'SELECT SQL_CALC_FOUND_ROWS wp_posts.* 
                FROM wp_posts INNER JOIN wp_postmeta ON (wp_posts.ID = wp_postmeta.post_id) 
                WHERE 1=1 
                AND (wp_posts.post_title LIKE "%'.$q .'%") AND wp_posts.post_type = "acreditacion" 
                AND (wp_posts.post_status = "publish") OR ((wp_postmeta.meta_key = "aidc-codigo" AND CAST(wp_postmeta.meta_value AS CHAR) LIKE "'.$q .'")) 
                GROUP BY wp_posts.ID 
                ORDER BY wp_posts.post_date DESC';

        $wpdb->query($wpdb->prepare($query)); 
        $results = $wpdb->last_result;

        foreach($results as $result){
            $post_meta = get_post_meta($result->ID);
            $fecha = date("d-m-Y", strtotime($post_meta['aidc-fecha-baja'][0]));

            $data[] = array(
                'codigo'        => $post_meta['aidc-codigo'][0],
                'nombre'        =>  $result->post_title,
                'pasaporte'     => $post_meta['aidc-pasaporte'][0],
                'mail'          => $post_meta['aidc-mail'][0],
                'tel'           => $post_meta['aidc-tel'][0],
                'pais'          => $post_meta['aidc-pais'][0],
                'fecha'         => $fecha,
                'competencias'  => $post_meta['aidc-competencias'][0]
            );
        }

        wp_send_json($data);
    }

}