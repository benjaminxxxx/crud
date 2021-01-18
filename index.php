<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud Básico</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <div class="container crud">
        <div class="row">
            <div class="col-md-12">
                <h2 class="my-4">Registro de productos</h2>
                <form class="row" name="registrar" action="#" method="post">
                    <input type="hidden" name="id">
                    <div class="form-group col-md-4">
                        <label for="name">Nombre del producto</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="price">Precio</label>
                        <input type="text" class="form-control" name="price" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="stock">Stock</label>
                        <input type="text" class="form-control" name="stock" required min="0" step="1">
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary"><span class="option-save">Guardar</span> <span class="waiting"></span> </button>
                        <button type="button" class="btn btn-secondary ml-4 d-none" id="cancel">Cancelar</button>
                    </div>
                </form>
            </div>
            <div class="col-md-12">
                <h2 class="my-4">Lista de productos</h2>
                <div class="table-responsive">
                    <table id="table-list" class="table table-bordered">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nombre del producto</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Stock</th>
                            <th scope="col" style="width: 187px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
                
            </div>
            
        </div>

    </div>
</body>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<script>
    function sendBody(msg){
        $('#table-list').find('tbody').html(msg);
    }
    function sendButton(msg){
        $('.waiting').html(msg);
    }
    function resetForm(){
        $('.option-save').html('Guardar');
        $('form[name="registrar"]')[0].reset();
        $('#cancel').addClass('d-none');
    }
    function queryList(){
        $.ajax({
            url: 'server/producto.php?option=read',
            beforeSend: function( xhr ) {
                sendBody('<tr><td colspan="100%">Cargando...</td></tr>');
            },
            statusCode: {
                404: function() {
                    alert( "Página no encontrada" );
                }
            },
            dataType:'json',
            success: function(data){
                if(!data.response){
                    alert(data.message);
                }else{
                    if(data.response.length>0){
                        
                        var stringResult = '';

                        for (let index = 0; index < data.response.length; index++) {
                            
                            stringResult+='<tr>';
                            stringResult+='<td>'+(index+1)+'</td>';
                            stringResult+='<td>'+data.response[index]['name']+'</td>';
                            stringResult+='<td>'+data.response[index]['price']+'</td>';
                            stringResult+='<td>'+data.response[index]['stock']+'</td>';
                            stringResult+='<td class="text-center">';
                            stringResult+='<a href="#" class="editar pr-3" data-id="'+data.response[index]['id']+'" data-name="'+data.response[index]['name']+'" data-price="'+data.response[index]['price']+'" data-stock="'+data.response[index]['stock']+'">Editar</a>';
                            stringResult+='<a href="#" class="eliminar" data-id="'+data.response[index]['id']+'">Eliminar</a>';
                            stringResult+='</td>';
                            stringResult+='</tr>';    
                        }
                        sendBody(stringResult);
                    }else{
                        sendBody('<tr><td colspan="100%">No hay registro</td></tr>');
                    }
                }

            }
        });
    }
    $( document ).ready(function() {
        /***********GUARDAR O ACTUALIZAR **********************/
        $('form[name="registrar"]').validate({
            rules: {
                price: {
                    required: true,
                    number: true
                },
                stock: {
                    required: true,
                    digits: true
                }
            },
            submitHandler: function(form) {
                //e.preventDefault();
               // $form = $(this);
               console.log($('form[name="registrar"]').serializeArray());
                $.ajax({
                    url: 'server/producto.php?option=create',
                    method: 'post',
                    data: $('form[name="registrar"]').serializeArray(),
                    beforeSend: function( xhr ) {
                        sendButton('...');
                    },
                    statusCode: {
                        404: function() {
                            alert( "Página no encontrada" );
                        }
                    },
                    dataType:'json',
                    success: function(data){
                        if(!data.response){
                            alert(data.message);
                        }else{
                            queryList();
                            sendButton('');
                            resetForm();
                        }

                    }
                });
            }
        });
        
        /***********FIN DE GUARDAR O ACTUALIZAR****************/
        /***********LISTAR **********************/
        queryList();
        /***********FIN LISTAR **********************/
        /*********** EDITAR *************************/
        $('.crud').on('click','.editar',function(e){

            var info = $(this);

            e.preventDefault();
            $('.option-save').html('Actualizar');
            $('input[name="id"]').val(info.data('id'));
            $('input[name="name"]').val(info.data('name'));
            $('input[name="price"]').val(info.data('price'));
            $('input[name="stock"]').val(info.data('stock'));
            $('#cancel').removeClass('d-none');
        });
        $('.crud').on('click','#cancel',function(e){

            e.preventDefault();
            resetForm();
            
        });
        /***********FIN EDITAR **********************/
        /*********** ELIMINAR *************************/
        $('.crud').on('click','.eliminar',function(e){

            var info = $(this);

            e.preventDefault();
            if (confirm('Está seguro de eliminar el siguiente registro?')) {
                $.ajax({
                    url: 'server/producto.php?option=delete',
                    method: 'post',
                    data: {
                        id:info.data('id'),
                    },
                    statusCode: {
                        404: function() {
                            alert( "Página no encontrada" );
                        }
                    },
                    success: function(data){
                        queryList();
                    }
                });
            }

        });
        /***********FIN ELIMINAR **********************/
    });
</script>
</html>