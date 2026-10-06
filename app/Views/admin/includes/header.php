<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { sidebar: '#f8fafc', accent: '#0ea5e9' }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen antialiased">
    <div class="flex min-h-screen">
<?= view('admin/includes/sidebar') ?>
<div class="flex-1 flex flex-col min-w-0">
<header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-slate-200 bg-white/95 px-4 backdrop-blur sm:px-6">
    <div class="flex items-center gap-3">
        <button type="button" onclick="toggleSidebar()" class="grid h-10 w-10 place-items-center rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 lg:hidden" aria-label="Open admin menu"><i class="bi bi-list text-2xl"></i></button>
        <span class="font-semibold text-slate-800">YoooCMS Administration</span>
    </div>
    <div class="flex items-center gap-2 text-sm text-slate-500"><i class="bi bi-person-circle text-lg"></i><span><?= esc(session()->get('admin_username') ?? 'Administrator') ?></span></div>
</header>
