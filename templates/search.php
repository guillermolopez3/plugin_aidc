<?php
    global $wpdb;
    $query_1 = array(
        'post_type'     => 'acreditacion',
        'post_status' => 'publish',
        'orderby' => array(
            'aidc-fecha' => 'asc'
        ),
        'posts_per_page'=> 21,
        //'paged'         => $_page, 
    );

    $acreditacion = new WP_Query($query_1); 
    //var_dump($acreditacion);

   

    $query = 'SELECT SQL_CALC_FOUND_ROWS wp_posts.* 
    --     FROM wp_posts INNER JOIN wp_postmeta ON (wp_posts.ID = wp_postmeta.post_id) 
    -- WHERE 1=1 
    -- AND (wp_posts.post_title LIKE "%AXR43219FF%") AND wp_posts.post_type = "acreditacion" 
    -- AND (wp_posts.post_status = "publish") OR ((wp_postmeta.meta_key = "aidc-codigo" AND CAST(wp_postmeta.meta_value AS CHAR) LIKE "AXR43219FF")) 
    -- GROUP BY wp_posts.ID 
    -- ORDER BY wp_posts.post_date DESC';

    $wpdb->query($wpdb->prepare($query)); 
    $data = $wpdb->last_result;
    //var_dump($data);
    ?>


<div class="container ">
    <div class=" mt-5">
        <div class="row justify-content-center">
          <div class="col-12 col-sm-7 col-lg-4">
              <input type="text" id="q" name="s" class="form-control" placeholder="Ingresá nombre y apellido o código">
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
                    $fecha = date("d-m-Y", strtotime($post_meta['aidc-fecha-baja'][0]));
        ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                    <div class="text-right">
                        <h6><strong>Código del curso:</strong> <br><?php echo $post_meta['aidc-codigo'][0];?></h6>
                    </div>
                    <hr>
                    <h3 class="card-title"><?php echo get_the_title();?></h3>
                    <h6 class="card-subtitle mb-2 text-muted">Pasaporte: <?php echo $post_meta['aidc-pasaporte'][0];?></h6>
                    <p class="mb-2"><strong>mail:</strong> <?php echo $post_meta['aidc-mail'][0];?></p>
                    <p class="mb-2"><strong>Teléfono:</strong> <?php echo $post_meta['aidc-tel'][0];?></p>
                    <p class="mb-2"><strong>País:</strong> <?php echo $post_meta['aidc-pais'][0];?></p>
                    <p class="mb-2"><strong>Fecha vencimiento:</strong> <?php echo $fecha;?></p>

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
  </div>