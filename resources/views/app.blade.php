<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Borel&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/billboard.js/3.9.3/billboard.min.css" integrity="sha512-6vagKaGvYsy3qbgnB1u56G+WyVS5iPsJH1umRLKEKcCK7oYBQsu/7teAvRQfoIeTNZXoWwn3ord7C0wwJCGw+w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- bootsrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        @vite(['resources/css/app.css'])

    </head>
    <body>
      <div id="app">

        @vite(['resources/js/app.js'])
      </div>
    </body>
</html>
