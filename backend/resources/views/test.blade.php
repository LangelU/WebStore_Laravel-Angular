<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.css">
    <title>Document</title>
</head>
<body>
<form action="{{ asset('test') }}"
      class="dropzone"
      id="file" method="POST">
      {{ csrf_field() }}
      <button class="submit"></button>
</form>
</body>
</html>
<script>
    Dropzone.options.myAwesomeDropzone = {
    paramName: "file", // Las imágenes se van a usar bajo este nombre de parámetro
    maxFilesize: 50 // Tamaño máximo en MB
    Dropzone.autoDiscover = false;                        
    autoProcessQueue: false,



};
</script>