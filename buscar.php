<?php
// Realizar la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "empleado1";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error al conectar a la base de datos: " . $conn->connect_error);
}

// Obtener el apellido de la petición AJAX
$apellido = $_GET["apellido"];

// Escapar caracteres especiales para evitar inyección SQL
$apellido = $conn->real_escape_string($apellido);

// Configuración de paginación
$results_per_page = isset($_GET['results_per_page']) ? $_GET['results_per_page'] : 10;
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($current_page - 1) * $results_per_page;

// Consulta para buscar similitudes con las letras ingresadas y con el apellido completo, con paginación
$sql = "SELECT * FROM empleado WHERE apellido LIKE '%$apellido%' LIMIT $offset, $results_per_page";
$result = $conn->query($sql);

// Obtener el total de resultados sin paginación
$total_results = $result->num_rows;

// Calcular el número total de páginas
$total_pages = ceil($total_results / $results_per_page);

// Mostrar los resultados de la búsqueda
if ($total_results > 0) {
    echo "<br>";
    echo "<table style='border-collapse: collapse; width: 100%;'>";
    echo "<tr>";
    echo "<th style='border: 1px solid black; text-align: center; padding: 8px; background-color: #2d572c;'>Nombre</th>";
    echo "<th style='border: 1px solid black; text-align: center; padding: 8px; background-color: #2d572c;'>Apellido</th>";
    echo "<th style='border: 1px solid black; text-align: center; padding: 8px; background-color: #2d572c;'>CI</th>";
    echo "<th style='border: 1px solid black; text-align: center; padding: 8px; background-color: #2d572c;'>Fecha de Nacimiento</th>";
    echo "<th style='border: 1px solid black; text-align: center; padding: 8px; background-color: #2d572c;'>Dirección</th>";
    echo "<th style='border: 1px solid black; text-align: center; padding: 8px; background-color: #2d572c;'>Sexo</th>";
    echo "<th style='border: 1px solid black; text-align: center; padding: 8px; background-color: #2d572c;'>Salario</th>";
    echo "</tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td style='border: 1px solid black; text-align: center; padding: 8px;'>" . $row["nombre"] . "</td>";
        echo "<td style='border: 1px solid black; text-align: center; padding: 8px;'>" . $row["apellido"] . "</td>";
        echo "<td style='border: 1px solid black; text-align: center; padding: 8px;'>" . $row["ci"] . "</td>";
        echo "<td style='border: 1px solid black; text-align: center; padding: 8px;'>" . $row["fecha_n"] . "</td>";
        echo "<td style='border: 1px solid black; text-align: center; padding: 8px;'>" . $row["direccion"] . "</td>";
        echo "<td style='border: 1px solid black; text-align: center; padding: 8px;'>" . $row["sexo"] . "</td>";
        echo "<td style='border: 1px solid black; text-align: center; padding: 8px;'>" . $row["salario"] . "</td>";
        echo "</tr>";
    }

    echo "</table>";

    // Mostrar la paginación
    echo "<br>";
    echo "<div style='text-align: center;'>";
    echo "Páginas:";
    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<a class='pagination-link' href='#' data-page='$i' style='margin: 0 5px; text-decoration: none;'>" . $i . "</a>";
    }
    echo "</div>";

    // Mostrar el campo para seleccionar la cantidad de elementos por página
    echo "<br>";
    echo "<div style='text-align: center;'>";
    echo "Elementos por página:";
    echo "<select id='results-per-page-select' style='margin-left: 5px;'>";
    echo "<option value='10' " . ($results_per_page == 10 ? "selected" : "") . ">10</option>";
    echo "<option value='20' " . ($results_per_page == 20 ? "selected" : "") . ">20</option>";
    echo "<option value='30' " . ($results_per_page == 30 ? "selected" : "") . ">30</option>";
    echo "</select>";
    echo "</div>";

    // Mostrar el script para actualizar la tabla y la paginación mediante AJAX
    echo "<script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>";
    echo "<script>";
    echo "$(document).ready(function() {";
    echo "    $('.pagination-link').click(function(e) {";
    echo "        e.preventDefault();";
    echo "        var page = $(this).data('page');";
    echo "        var resultsPerPage = $('#results-per-page-select').val();";
    echo "        var url = window.location.href;";
    echo "        url = url.replace(/([&?]page=)[^&]+/, '$1' + page);";
    echo "        url = url.replace(/([&?]results_per_page=)[^&]+/, '$1' + resultsPerPage);";
    echo "        $.ajax({";
    echo "            url: url,";
    echo "            success: function(response) {";
    echo "                $('#results-container').html(response);";
    echo "            }";
    echo "        });";
    echo "    });";
    echo "    $('#results-per-page-select').change(function() {";
    echo "        var resultsPerPage = $(this).val();";
    echo "        var url = window.location.href;";
    echo "        url = url.replace(/([&?]results_per_page=)[^&]+/, '$1' + resultsPerPage);";
    echo "        url = url.replace(/([&?]page=)[^&]+/, '$1' + 1);";
    echo "        $.ajax({";
    echo "            url: url,";
    echo "            success: function(response) {";
    echo "                $('#results-container').html(response);";
    echo "            }";
    echo "        });";
    echo "    });";
    echo "});";
    echo "</script>";
} else {
    echo "<p style='text-align: center;'>No se encontraron resultados.</p>";
}

$conn->close();
?>
