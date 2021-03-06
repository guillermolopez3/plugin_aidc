<?php
    global $wpdb;
    if(isset($_GET['codigo']) && !empty($_GET['codigo'])){
        $codigo = $_GET['codigo'];
        $query_1 = array(
            'post_type'     => 'acreditacion',
            'post_status'   => 'publish',
            'paged'         => get_query_var('paged') ? get_query_var('paged') : 1,
            'meta_query'    => array(
                'relation'  => 'AND',
                array(
                    'key'       => 'aidc-codigo',
                    'value'     => $codigo,
                    'compare'   => 'LIKE'
                )
            )
        );

    }else{
        $query_1 = array(
            'post_type'     => 'acreditacion',
            'post_status' => 'publish',
            'orderby' => array(
                'aidc-fecha' => 'asc'
            ),
            'posts_per_page'=> 21,
            'paged' => get_query_var('paged') ? get_query_var('paged') : 1
        );
    }
    

    $acreditacion = new WP_Query($query_1); 

   
    //var_dump($data);
    ?>

<div class="jumbotron jumbotron-fluid">
  <div class="container">
    <h2 class="display-4">Acreditados por AIDC</h2>
    <p class="lead">Listados de profesionales acreditados</p>
  </div>
</div>
<div class="container cuerpo-busqueda">
    <div class="">
        <div class="row justify-content-center">
          <div class="col-12 col-sm-7 col-lg-5">
              <input type="text" id="q" name="s" class="form-control input-busqueda" placeholder="Ingresá nombre y apellido, código o empatía">
          </div>
          
          <div class="col-12 col-sm-2 mt-3 mt-sm-0">
              <input class=" btn boton-naranja " type="submit" class="form-control" value="Buscar" id="searchsubmit">
          </div>
        </div>
    </div>
    <div>
      <div class="row mt-5" id="lista">
        <?php 
            if($acreditacion->have_posts()):
                while($acreditacion->have_posts()):
                    $acreditacion->the_post();
                    $post_meta = get_post_meta(get_the_id());
                    $fecha = date("d-m-Y", strtotime($post_meta['aidc-fecha'][0]));
        ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                    <div class="text-right">
                        <h6><strong>Código del curso:</strong> <br><?php echo $post_meta['aidc-codigo'][0];?></h6>
                    </div>
                    <hr>
                    <h3 class="card-title"><?php echo get_the_title();?></h3>
                    
                    <p class="mb-2"><strong>mail:</strong> <?php echo $post_meta['aidc-mail'][0];?></p>
                    <p class="mb-2"><strong>Teléfono:</strong> <?php echo $post_meta['aidc-tel'][0];?></p>
                    <p class="mb-2"><strong>País:</strong> <?php echo $post_meta['aidc-pais'][0];?></p>
                    <p class="mb-2"><strong>Fecha de realización:</strong> <?php echo $fecha;?></p>

                    <?php
                        $colores = ['bg-primary', 'bg-success','bg-warning'];
                        $competencias = $post_meta['aidc-competencias'][0];
                        $pills = explode(",", $competencias);
                        //var_dump($colores[array_rand($colores,1)]);
                    ?>
                    <p class="pills">
                        <strong class="pills-title">Competencias adquiridas:</strong> <br>
                        <?php foreach($pills as $pill):?> 
                            <span class="badge <?php echo $colores[array_rand($colores,1)]; ?>"> 
                                <?php echo $pill;?>
                            </span>                        
                        <?php endforeach;?>
                    </p>
                    </div>
                </div>
            </div>
        <?php
                endwhile;
            endif;
        ?>
      </div>
    </div>
    <div class="text-center">
            <?php
                    $total_pages = $acreditacion->max_num_pages;
                    if ($total_pages > 1){

                        $current_page = max(1, get_query_var('paged'));
            
                        $pagination = paginate_links(array(
                            'base' => get_pagenum_link(1) . '%_%',
                            'format' => '/page/%#%',
                            'current' => $current_page,
                            'total' => $total_pages,
                            'type' => 'array',
                            'prev_text'    => __('<<'),
                            'next_text'    => __('>>'),
                            'add_args'  => array()
                        ));

                        if ( ! empty( $pagination ) ){ ?>
                         <ul class="pagination paginacion-ul">
                                <?php foreach ( $pagination as $key => $page_link ) : ?>
                                    <li class="page-item<?php if ( strpos( $page_link, 'current' ) !== false ) { echo ' active'; } ?>"><?php echo $page_link ?></li>
                                <?php endforeach ?>
                            </ul>   
                     <?php       
                        }                        
                    }
            ?>
            
         </div>
  </div>