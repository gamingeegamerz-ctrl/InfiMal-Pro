<!doctype html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'InfiMal')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full bg-slate-50 text-slate-900">
<script>
(function(){
  const mode=localStorage.getItem('infimal_theme');
  if(mode==='dark'){document.documentElement.classList.add('dark');}
})();
</script>
<div class="min-h-screen flex">
    <aside class="w-64 bg-white border-r p-4 hidden md:block">
        <div class="font-bold text-xl text-blue-600">InfiMal</div>
    </aside>
    <div class="flex-1">
        <header class="bg-white border-b p-4 flex justify-end">
            <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center">{{ strtoupper(substr(auth()->user()->name ?? 'U',0,1)) }}</div>
        </header>
        <main class="p-6">@yield('content')</main>
    </div>
</div>
</body>
</html>
