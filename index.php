<!DOCTYPE html>
<html>
<head>
    <title>Búsqueda de empleados</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
        });

        function buscarAutomatico(page) {
            var apellido = document.getElementById("apellidoAutomatico").value;
            
            $.ajax({
                url: "buscar.php",
                method: "GET",
                data: { apellido: apellido, page: page },
                success: function(response) {
                    $("#tablaAutomatico tbody").html(response);
                }
            });
        }

        function buscarBoton() {
            var apellido = document.getElementById("apellidoBoton").value;
            
            $.ajax({
                url: "buscar.php",
                method: "GET",
                data: { apellido: apellido },
                success: function(response) {
                    $("#tablaBoton tbody").html(response);
                }
            });
        }
    </script>
</head>
<body>
    <h1>Búsqueda de empleados</h1>

    <h3>Búsqueda automática por letra (onkeyup):</h3>
    <input type="text" id="apellidoAutomatico" onkeyup="buscarAutomatico(1)" placeholder="Ingrese el apellido">
    <br>
    <table id="tablaAutomatico" style="width:100%">
        
        <tbody></tbody>
    </table>
    <div id="paginationAutomatico" class="pagination"></div>

    <h3>Búsqueda por letra con boton buscar (onclick):</h3>
    <input type="text" id="apellidoBoton" placeholder="Ingrese el apellido">
    <button onclick="buscarBoton()">Buscar</button>
    <br>
    <table id="tablaBoton" style="width:100%">
        <thead>
            
        </thead>
        <tbody></tbody>
    </table>
</body>
</html>
