<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>VO</title>
	<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
	</head>
  <link rel="stylesheet" href="css/style.css">

<body>
<center>
	<!-- <form method="post" name="salon" enctype="multipart/form-data" action="salon-web12.php"/> -->
	<form method="post" name="salon" enctype="multipart/form-data" action="read_plate.php"/>
    
		<br/>
		<br/>
		<div id="div_file" style="text-align:center">
			<p id="texto" style="padding:50px">FOTO</p>
			<input type="file" name="file" id="foto_vehiculo" class="input" accept="image/*" capture="camera" onchange="enviar()"/>
		</div>
			<input type="hidden" name="id" value="<?php echo time() ?>"/>
	</form>


<!-- Print Plate -->

<div  id="success-message"  style="display: nonex;" class="matriculas">
  <img src="images/matriculablanco.jpg" class="img-responsive" alt="matricula CSS" width="292" height="58">
  <div class="texto-encima"> </div>
  <div class="centrado"><span id="success-text"></span></div>
</div>

<!-- Error message -->
<div id="error-message" style="display: none; background-color: #F44336; color: #fff; font-size: 24px; text-align: center; padding: 10px; border: 1px solid #F44336; border-radius: 5px;">
    <strong>Error:</strong> <span id="error-text"></span>
</div>

<!-- Loader -->
<div id="loader" style="display: none;"  class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>


</center>
</body>
</html>


<script>

  //Función de enviar la foto a la función de read_plate.php dónde se analiza la fotografía y nos devuelve la matrícula.
  function enviar() {
    $("#foto_vehiculo").css("background-color", "#FF0000");
    var formData = new FormData();

    //mostramos loader y limpiamos/ocultamos matrícula 
    $("#loader").show();
    $("#success-text").text('');
    $("#success-message").hide();

    // console.log($('#foto_vehiculo')[0].files[0]);

    formData.append('file', $('#foto_vehiculo')[0].files[0]); //añadimos la foto tomada a formData
    formData.append('id', $("input[name='id']").val());  // también añadimos el id por si lo queremos usar más adelante.
    
    // Ejemplo de llamada AJAX
    $.ajax({
      url: 'read_plate.php', //se encarga de leer la matrícula con la foto que le pasamos
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      xhrFields: {
          withCredentials: true, // Permite enviar cookies y credenciales
      },
      headers: {
          // 'Origin': '#', 
      },
      success: function(response) {
        // Manejar la respuesta (la matrícula)
        console.log(response);
        // Parsear la respuesta JSON
        var jsonResponse = JSON.parse(response);
        
        //ocultamos el loader
        $("#loader").hide(); 

        if (jsonResponse.status === 1) {
          // Si la respuesta es un éxito, mostramos la matrícula
            $("#success-text").text(jsonResponse.msg);
            $("#success-message").show();
            $("#error-message").hide();
        } else {
          // Si hay un error, mostrar el mensaje en el div de error
            $("#error-text").text(jsonResponse.msg);
            $("#error-message").show();
            $("#success-message").hide();
        }
      },
      error: function(xhr, status, error) {
        // Manejar errores si es necesario
        $("#loader").hide();
        console.log(error);
      }
    });
  }
</script>
