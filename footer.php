        <footer class="footer">
            <div class=" container-fluid ">
            <div class="copyright" id="copyright"> &copy; <script>
                document.getElementById('copyright').appendChild(document.createTextNode(new Date().getFullYear()))
                </script>, Diseño y desarrollo por <a href="https://www.emedos.com.py" target="_blank">M2 Design</a>.</div>
            </div>
        </footer>
        </div>    
    </div>
    <script src="../assets/js/core/jquery.min.js"></script>
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
    <!--  Google Maps Plugin    -->
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!--  Notifications Plugin    -->
    <script src="../assets/js/plugins/bootstrap-notify.js"></script>
    <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="../assets/js/now-ui-dashboard.min.js?v=1.5.0" type="text/javascript"></script><!-- Now Ui Dashboard DEMO methods, don't include it in your project! -->
    <!-- <script src="../assets/demo/demo.js"></script> -->
    <!-- <script>
        $(document).ready(function() {
        // Javascript method's body can be found in assets/js/demos.js
        demo.initDashboardPageCharts();

        });
        
    </script> -->
    <script>
        $(document).ready(function() {
            // Manejar la presentación del formulario
            $('#preguntaForm').submit(function(event) {
                event.preventDefault(); // Evitar el comportamiento predeterminado del formulario

                var form = this; // Guardar una referencia al formulario actual 
                
                $.ajax({
                    type: 'POST',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            $.notify(response.message, {type:'success'}); // Mostrar mensaje de éxito
                            form.reset();
                        } else {
                            $.notify(response.message, {type:'danger'}); // Mostrar mensaje de error
                        }
                    },
                    error: function() {
                        $.notify('Error en la solicitud', {type:'danger'}); // Mostrar mensaje de error genérico
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $("#encuesta-form").submit(function (event) {
                event.preventDefault(); // Evitar el comportamiento predeterminado del formulario

                // Realizar la solicitud AJAX
                $.ajax({
                    type: "POST",
                    url: "procesar-respuestas.php", // Cambiar a la ruta correcta
                    data: $(this).serialize(), // Serializar los datos del formulario
                    dataType: "json",
                    success: function (response) {
                        // Mostrar notificación utilizando notify.js
                        $.notify(response.message, {
                            type: response.status === "success" ? "success" : "danger",
                            autoHideDelay: 5000
                        });

                        var porcentajeInfo = "";
                        if (response.porcentaje_si <= 35) {
                            porcentajeInfo = "Bajo (Mal funcionamiento)";
                            color = "red";
                        } else if (response.porcentaje_si <= 70) {
                            porcentajeInfo = "Medio (Funcionamiento moderado)";
                            color = "orange";
                        } else if (response.porcentaje_si <= 90) {
                            porcentajeInfo = "Alto (Funcionamiento bueno)";
                            color = "#639933";
                        } else {
                            porcentajeInfo = "Óptimo (Funcionamiento perfecto)";
                            color = "#05ab05";
                        }
                        $("#porcentaje-info").html("<div id=\"answer\" style=\"color: "+color+"\">"+Math.round(response.porcentaje_si)+"%</div>" +  "<br>Nivel: " + porcentajeInfo + "<br>ID de Respuesta: " + response.token);

                        // Mostrar la tabla correspondiente
                        $("#tabla-porcentaje").show();

                        // Resetear el formulario si la respuesta es exitosa
                        if (response.status === "success") {
                            //$("#encuesta-form")[0].reset(); // TODO: ACTIVAR ESTO AL TERMINAR EL TEST
                        }
                    },
                    error: function () {
                        $.notify("Error al enviar el formulario", {
                            type: "danger",
                            autoHideDelay: 5000
                        });
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $("input[type='radio']").on("change", function () {
                var name = $(this).attr("name");
                var value = $(this).val();
                localStorage.setItem(name, value);
            });

            // Restaura los valores almacenados en localStorage cuando la página se carga
            $("input[type='radio']").each(function () {
                var name = $(this).attr("name");
                var value = localStorage.getItem(name);
                if (value !== null) {
                    if ($(this).val() === value) {
                        $(this).prop("checked", true);
                    }
                }
            });
        });    
    </script>
    <script>
        $(document).ready(function () {
            chartColor = "#FFFFFF";
            // Obtener el contexto del lienzo del gráfico
            var ctx = document.getElementById("bigDashboardChart").getContext("2d");
            var gradientStroke = ctx.createLinearGradient(500, 0, 100, 0);
            gradientStroke.addColorStop(0, '#80b6f4');
            gradientStroke.addColorStop(1, chartColor);
            var gradientFill = ctx.createLinearGradient(0, 200, 0, 50);
            gradientFill.addColorStop(0, "rgba(128, 182, 244, 0)");
            gradientFill.addColorStop(1, "rgba(255, 255, 255, 0.24)");
            // Obtener los datos del porcentaje de los últimos 10 registros
            $.ajax({
                type: "GET",
                url: "stats/obtener-porcentaje-ultimos-10.php",
                dataType: "json",
                success: function (response) {
                    // Crear un array de etiquetas con las fechas en el formato deseado
                    var labels = response.labels.map(function (index) {
                        var date = new Date(response.fechas[index - 1]);
                        var day = String(date.getDate()).padStart(2, '0');
                        var month = String(date.getMonth() + 1).padStart(2, '0');
                        var year = date.getFullYear();
                        var hours = String(date.getHours()).padStart(2, '0');
                        var minutes = String(date.getMinutes()).padStart(2, '0');
                        return day + '/' + month + '/' + year + ' ' + hours + ':' + minutes;
                    });
                    // Crear el gráfico utilizando Chart.js
                    var chart = new Chart(ctx, {
                        type: "line",
                        data: {
                            labels: labels, // Usar las etiquetas de fechas
                            datasets: [
                                {
                                    label: "Nivel de Calidad",
                                    data: response.porcentajes,
                                    backgroundColor: "rgba(75, 192, 192, 0.2)", // Color de fondo de las barras
                                    borderColor: chartColor, // Color del borde de las barras
                                    pointBackgroundColor: "#1e3d60",
                                    pointHoverBackgroundColor: "#1e3d60",
                                    pointHoverBorderColor: chartColor,
                                    fill: true,
                                    backgroundColor: gradientFill,
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            layout: {
                                padding: {
                                    left: 20,
                                    right: 20,
                                    top: 0,
                                    bottom: 0
                                }
                            },
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: "bottom",
                                    fillStyle: "#FFF",
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: '#333',
                                    titleFontColor: '#333',
                                    bodyFontColor: '#666',
                                    bodySpacing: 4,
                                    xPadding: 12,
                                    mode: "nearest",
                                    intersect: false,
                                    position: "nearest",
                                    callbacks: {
                                        label: function (context) {
                                            var label = context.dataset.label || '';
                                            if (label) {
                                                label += ': ';
                                            }
                                            label += context.parsed.y + '%';
                                            return label;
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true, // Iniciar en el valor mínimo (0)
                                    ticks: {
                                        stepSize: 10, // Incremento de los ticks
                                        color: "rgba(255,255,255,0.4)",
                                        font: {
                                            size: "bold"
                                        }
                                    }
                                }
                            }
                        }
                    });
                },
                error: function () {
                    console.error("Error al obtener los datos del porcentaje");
                }
            });
        });
    </script>
</body>
</html>