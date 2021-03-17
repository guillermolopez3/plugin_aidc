//reporte ventas
jQuery(document).ready(function($){
    $('#searchsubmit').click(function(){
        
        q = $('#q').val();
        //console.log('val' + $q)
        if(q.length > 0){
            $.ajax({
                url : aidc_vars.ajaxurl,
                method : 'POST',
                data:{
                    action : 'filterAcreditados',
                    busqueda : q
                },
                error: function(){
                   // alertify.error('Error al guardar');
                },
                beforeSend: function(){
                   // $('#sendModal').modal('show');
                },
                success: function(resp){
                    if(resp != null){
                        var mk = listResultRepoVtaMarckup(resp);
                        $('#lista').html(mk)
                    }
                    
                }
            })//fin ajax
        }
    })

});

//filtro para la tabla reporte de venta
function listResultRepoVtaMarckup(data){
    var marckUp = '';
    data.forEach(function(d){
        var colores = ['bg-primary', 'bg-success','bg-warning'];
        var competencias = d.competencias;
        var pills = competencias.split(',');
        marckUp += '<div class="col-12 col-md-6 col-lg-4">'
        marckUp +=      '<div class="card">'
        marckUp +=          '<div class="card-body">'
        marckUp +=            '<div class="text-right">'
        marckUp +=                '<h6><strong>Código del curso:</strong> <br>'+ d.codigo +'</h6>'
        marckUp +=             '</div>'
        marckUp +=            '<hr>'
        marckUp +=            '<h3 class="card-title">'+ d.nombre +'</h3>'
        marckUp +=            '<h6 class="card-subtitle mb-2 text-muted">Pasaporte:'+ d.pasaporte+'</h6>'
        marckUp +=            '<p class="mb-2"><strong>mail:</strong>'+ d.mail +'</p>'
        marckUp +=            '<p class="mb-2"><strong>Teléfono:</strong>'+d.tel+'</p>'
        marckUp +=            '<p class="mb-2"><strong>País:</strong>'+ d.pais +'</p>'
        marckUp +=            '<p class="mb-2"><strong>Fecha vencimiento:</strong>'+ d.fecha +'</p>'
        marckUp +=            '<p class="pills">'
        marckUp +=                '<strong class="pills-title">Competencias adquiridas:</strong> <br>'
                                    pills.forEach(function(p){
                                        const random = Math.floor(Math.random() * 3);
        marckUp +=                      '<span class="badge '+ colores[random] +'">' + p + '</span> '
        
                                    })
        marckUp +=            '</p>'
        marckUp +=            '</div>'
        marckUp +=        '</div>'
        marckUp +=    '</div>'
    })
    return marckUp;
}