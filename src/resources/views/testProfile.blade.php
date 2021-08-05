<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">
    </head>
    <body>
      <div>
        <form action="{{ route('apiPostProfile') }}" method="POST">
          @csrf 
          <input type="text" name="name" placeholder="nhap ten">
          <button type="submit">gui</button>
        </form>
      </div>    
    </body>
    <script src="{{ url('js/app.js') }}"></script>
</html>