<!-- The Modal -->
<div class="modal" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="form"  enctype="multipart/form-data">

            <!-- Modal body -->
            <div class="modal-body">
                   <input type="hidden" name="id"  class="form-control"  id="id">
                    
                    <div class="form-group">
                        <label for="nombre">nombre:</label>
                        <input type="text" name="nombre"  class="form-control"  id="nombre">
                    </div>
                    <div class="form-group">
                        <label for="descripcion">descripcion:</label>
                        <input type="text" name="descripcion"  class="form-control"  id="descripcion">
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Portada    :</label><br>
                        <input type="file" name="portada" class=""  id="portada">
                    </div>
            </div>
    
            <!-- Modal footer -->
            <div class="modal-footer d-flex justify-content-between ">
                <button type="submit" id="guardar" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </form>
  <img src="" alt="">
      </div>
    </div>
  </div>

  <script>

    
    $('#form').submit(function(e) {

        var id = $("input[name=id]").val();
        
        e.preventDefault();
        var formData = new FormData(this);
        e.preventDefault();
      
        
        var action="{{ route('libro.store') }}";
        var method="POST";
        
        if(id){
        action='api/libro/' + id;
        formData.append('_method', 'put');
        }
    
        $.ajax({
            type:method,
            url:action,
            dataType: 'json',
            data: formData,
                cache:false,
                contentType: false,
                processData: false,
            success:function(data){    
                limpiarCampos();
                get();        
                document.getElementById("mensaje").innerHTML= data.success;
                $('#myModal').modal('hide');
            
                $('#mensaje').fadeIn(2000);
                $('#mensaje').fadeOut(4000);
            },
            error: function (err) {
                if (err.status == 422) { // when status code is 422, it's a validation issue
                    console.log(err.responseJSON);
                  

                    // you can loop through the errors object and show it to the user
                    console.warn(err.responseJSON.errors);


                    // display errors on each form field
                   
                    $.each(err.responseJSON.errors, function (i, error) {
                        var el = $(document).find('[name="'+i+'"]');
                        $('div#nombre + span').remove()
                        el.after($('<span style="color: red;">'+error[0]+'</span>'));
                    });
                   
                }
            }
        });
    
    });
    /*

    $("#guardar").click(function(e){
    
    e.preventDefault();
   
    var id = $("input[name=id]").val();
    alert(id);
    var nombre = $("input[name=nombre]").val();
    var descripcion = $("input[name=descripcion]").val();
    var portada = $("input[name=portada]").val();
    var action="{{ route('libro.store') }}";
    var method="POST";
    if(id!=""){
      action='/api/libro/' + id;
      method="PUT";
    }

    $.ajax({
        type:method,
        url:action,
        dataType: 'json',
        data:{nombre:nombre, descripcion:descripcion, portada:portada},
        success:function(data){    
            limpiarCampos();
            get();        
            document.getElementById("mensaje").innerHTML= data.success;
           $('#myModal').modal('hide');
          
           $('#mensaje').fadeIn(2000);
           $('#mensaje').fadeOut(4000);
          
        }
    });

});*/
 </script>