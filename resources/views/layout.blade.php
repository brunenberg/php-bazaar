<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>{!!__('Bazaar')!!}</title>
    <script src="https://kit.fontawesome.com/087f8a3bb8.js" crossorigin="anonymous"></script>
</head>
<body>

  <header>
    @include('partials.navbar')
  </header>
  
  <main class="mx-20">
  @include('components.messages')
  @yield('content')
  </main>

  @stack('scripts')
</body>
</html>