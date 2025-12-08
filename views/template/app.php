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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-pap...SeuHashAqui..." crossorigin="anonymous" referrerpolicy="no-referrer" />

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

    .task-placeholder {
        background-color: rgba(36, 37, 40, 0.1);
        border: 2px dashed #555;
        height: 60px;
        margin-bottom: 8px;
        border-radius: 8px;
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