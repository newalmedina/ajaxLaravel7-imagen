@extends('layout')
@section('contenido')
    <div class="alert alert-success" id="mensaje" style="display:none"></div>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
       Nuevo Libro
    </button>
    
    <h1>Listado de Libros</h1>
    <hr>
    <table class="table">
        <thead>
          <tr>
            <th>portada</th>
            <th>nombre</th>
            <th>descripcion</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="resultado">

        </tbody>
    </table>
    <script>

          get();
          
          function get() {
              $.ajax({
              type:'get',
              url:"{{ route('libro.index') }}",
              dataType: 'json',           
              success:function(data){
                      var tabla="";
                      for(let item of  data['libros']){
                          var foto=item.portada;
                          if(!foto){
                           foto = "noimagen.jpg";
                          }
                               

                              tabla += `
                              <tr>
                                  <td ><img class='rounded' width="70" src="${foto}" alt=""></td>
                                  <td>${item.nombre}</td>
                                  <td>${item.descripcion}</td>                            
                                  <td>
                                       <button  id='editar' onclick='editar(${item.id})'  name="btnEditar" class="btn btn-primary "  >Editar </button>
                                       <button  id='eliminar' onclick='if(confirm("estas seguro?"))  eliminar(${item.id}) '  name="btnBorrar" class="btn btn-danger "  >Eliminar </button>
                                  </td >                              
                                  
                              </tr>
                              `;
                          }
                          
                          document.getElementById("resultado").innerHTML= tabla;
                  }
              });
          }
          
          function editar(id) {
            $.ajax({
                type:'GET',
                 url: '/api/libro/' + id,
                // url: "{{ route('libro.destroy',"+id+") }}",
                dataType: 'json',
                success:function(data){            
                    $('#myModal').modal('show');
                    $("#nombre").val(data["libro"].nombre);
                    $("#id").val(data["libro"].id);
                    $("#descripcion").val(data["libro"].descripcion);
                    $("#portada").val("");
                }
                
            });
       }
          function eliminar(id) {
                $.ajax({
                type:'DELETE',
                 url: '/api/libro/' + id,
                // url: "{{ route('libro.destroy',"+id+") }}",
                dataType: 'json',
                success:function(data){            
                    document.getElementById("mensaje").innerHTML= data.success;

                    $('#mensaje').fadeIn(500);
                    $('#mensaje').fadeOut(4000);
                    get();
                }
            });
       }

       function limpiarCampos(){
            $("#nombre").val("");
            $("#id").val("");
            $("#descripcion").val("");
            $("#portada").val("");
       }
      </script>
@endsection
