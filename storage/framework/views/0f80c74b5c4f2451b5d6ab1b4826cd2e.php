<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <?php echo e(__('Stime')); ?>

            </h2>
            <div>
                <a href="<?php echo e(route('estimations.create')); ?>"
                    class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    + Aggiungi una nuova stima
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>



    <!-- Search and Filter -->
    <main class="mt-6">
        <div class="py-12 ">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 bg-white shadow p-4 rounded">
                <!-- Products Table -->
                <div class="overflow-x-auto">
                    <table class="table-auto w-full">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">Immagine</th>
                                <th class="px-4 py-2">Nome del Floor</th>
                                <th class="px-4 py-2">Totale</th>
                                <th class="px-4 py-2">Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $estimations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estimation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="border px-4 py-2"><img src="<?php echo e(asset($estimation->image)); ?>"
                                        alt="<?php echo e($estimation->image); ?>" class="w-16 h-16 object-cover"></td>
                                <td class="border px-4 py-2"><?php echo e($estimation->floor_name); ?></td>
                                <td class="border px-4 py-2"><?php echo e($estimation->total); ?></td>
                                <td class="border px-4 py-2">
                                    <a href="<?php echo e(route('estimations.show', $estimation->id)); ?>"
                                        class="nline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">La
                                        mostra</a>
                                    <!-- <a href="<?php echo e(route('estimations.edit', $estimation->id)); ?>" class="nline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Modifica</a>-->
                                    <a href="<?php echo e(route('estimations.destroy', $estimation->id)); ?>" class="nline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this estimation?')) { document.getElementById('delete-form-<?php echo e($estimation->id); ?>').submit(); }">Cancella</a> 
                                    <form id="delete-form-<?php echo e($estimation->id); ?>"
                                        action="<?php echo e(route('estimations.destroy', $estimation->id)); ?>" method="POST"
                                        style="display: none;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td class="border px-4 py-2" colspan="5">Nessun prodotto</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    <?php echo e($estimations->links()); ?>

                </div>
            </div>
        </div>
    </main>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH /opt/lampp/htdocs/domotics/resources/views/estimation/index.blade.php ENDPATH**/ ?>