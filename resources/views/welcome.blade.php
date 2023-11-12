<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Short links</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    @vite(['resources/js/app.js'])
</head>

<body class="container">
    <form action="{{ route('create-token') }}" method="POST" class="row mt-5">
        @csrf
        <div class="col-3"></div>
        <div class="col-4">
            <input id="full_url" name="full_url" placeholder="Type the full URL" class="form-control">
        </div>
        <div class="col-2">
            <button type="submit" class="btn btn-primary">Send</button>
        </div>
        <div class="col-3"></div>
    </form>
    <div id="result" class="text-center mt-5">
    </div>
</body>

</html>
