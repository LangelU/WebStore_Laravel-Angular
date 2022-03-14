<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    <title>Document</title>
</head>
<body>
 <form action="{{ asset('test') }}" class="dropzone" id="pictures" method="POST">
    @csrf
    <button type="submit" id="upload" class="btn btn-primary">Submit</button>
</form>
<form action="{{ asset('newProduct') }}" method="POST" id="productData">
       @csrf
      <input type="text" name="reference" id="reference" placeholder="referencia">
      <input type="text" name="prod_name" placeholder="nombre">
      <input type="text" name="prod_type" placeholder="tipo">
      <input type="textarea" name="prod_description" placeholder="descripcion">
      <input type="textarea" name="prod_details" placeholder="detalles">
      <input type="number" name="prod_price" placeholder="precio">
      <input type="text" name="prod_category" placeholder="categoria">
      <input type="number" name="prod_stock" placeholder="stock">
      <input type="text" name="prod_brand" placeholder="marca">
      <input type="text" name="prod_model" placeholder="modelo">
      <button type="submit" name="upload" class="btn btn-primary">Submit</button>
</form>
<script src="{!! asset('js/jquery-3.3.1.min.js') !!}" type="text/javascript"></script>
<script>
     Dropzone.options.pictures = {
        /* Add all your configuration here */
        paramName: "file",
        addRemoveLinks: true,
        autoProcessQueue: false,
        parallelUploads: 15,
        acceptedFiles: 'image/*',

        init: function() {
            let pictures = this;
            document.getElementById('upload').addEventListener("click", function (e) {
                e.preventDefault();
                pictures.processQueue();
            });         
        }

    };

</script>
</body>
</html>
