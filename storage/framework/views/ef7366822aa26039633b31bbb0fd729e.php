<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'MyDomotics')); ?></title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <?php echo $__env->make('layouts.navigation', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <!-- Page Heading -->
            <?php if(isset($header)): ?>
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <?php echo e($header); ?>


                    <?php if(session('success')): ?>
                        <div class="alert alert-success">

                            <ul class="font-medium text-sm text-green-600 dark:text-green-400">
                                    <li><?php echo e(session('success')); ?></li>
                            </ul>

                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger">
                            <?php echo e(session('error')); ?>

                            <ul class="font-medium text-sm text-red-600 dark:text-green-400">
                                    <li><?php echo e(session('success')); ?></li>
                            </ul>
                        </div>
                    <?php endif; ?>
                    </div>
                </header>
            <?php endif; ?>

            <!-- Page Content -->
            <main>



                <?php echo e($slot); ?>

            </main>
        </div>
    </body>
</html>
<?php /**PATH /Users/rayfanale/Sites/localhost/grazia/resources/views/layouts/app.blade.php ENDPATH**/ ?>