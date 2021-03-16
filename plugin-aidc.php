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

define('CRUD_PLUGIN_PATH', plugin_dir_path( __FILE__ ));


//CPT PARA LAS NOTAS
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
        <td><input type="text" name="aidc-competencias" value="<?php echo sanitize_text_field($competencias);?>" class="large-text" placeholder="Ingrese las competencias" /></td>
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
