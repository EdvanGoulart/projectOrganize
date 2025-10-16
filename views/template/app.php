<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Organize</title>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

</head>
<style>
    .custom-scroll::-webkit-scrollbar {
        width: 8px;
    }

    .custom-scroll::-webkit-scrollbar-track {
        background: hsl(var(--b2));
        /* usa cor do tema DaisyUI */
        border-radius: 4px;
    }

    .custom-scroll::-webkit-scrollbar-thumb {
        background: hsl(var(--bc));
        /* cor do conteúdo/texto do tema */
        border-radius: 4px;
    }

    .custom-scroll::-webkit-scrollbar-thumb:hover {
        background: hsl(var(--bc) / 0.7);
    }

    /* Firefox */
    .custom-scroll {
        scrollbar-width: thin;
        scrollbar-color: hsl(var(--bc)) hsl(var(--b2));
    }
</style>

<body>
    <div class="mx-auto max-w-7xl h-screen flex flex-col space-y-2">
        <?php require base_path('views/partials/_navbar.view.php') ?>

        <?php require base_path('views/partials/_mensagem.view.php'); ?>

        <div class="flex flex-grow pb-6">
            <?php require base_path("views/{$view}.view.php"); ?>
        </div>
    </div>
</body>

</html>