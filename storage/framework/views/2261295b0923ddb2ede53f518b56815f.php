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
                <?php echo e(__('Preventivi')); ?>

            </h2>
            <div>

            </div>
        </div>
     <?php $__env->endSlot(); ?>



    <!-- Search and Filter -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 bg-white shadow p-4 rounded">

            <?php if($quotations->count() > 0): ?>
                <div class="overflow-x-auto">
                    <table class="table-auto w-full">
                        <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2">Preventivo #</th>
                            <th class="px-4 py-2">Stato</th>
                            <th class="px-4 py-2">n. prodotti</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
        <?php $__currentLoopData = $quotations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quotation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="border px-4 py-2">
                    #<?php echo e($quotation->id); ?>

                </td>
                <td class="border px-4 py-2">
                    <?php echo e($quotation->status); ?>

                </td>
                <td class="border px-4 py-2">
                    <?php echo e($quotation->products->count()); ?>

                </td>
                <td class="border px-4 py-2">
                    <?php if($quotation->status!='completed'): ?>
                        <a href="<?php echo e(route('quotations.edit', $quotation->id)); ?>" class="btn btn-primary">Modifica</a>
                        <form action="<?php echo e(route('quotations.destroy', $quotation->id)); ?>" method="POST" style="display: inline-block;">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger">Cancella</button>
                        </form>
                    <?php elseif($quotation->status==='confirmed'&& Auth::user()->hasRole('Admin')): ?>
                        <a href="<?php echo e(route('quotations.edit', $quotation->id)); ?>" class="btn btn-primary">Modifica</a>

                    <?php else: ?>
                        <a href="<?php echo e(route('quotations.view', $quotation->id)); ?>" class="btn btn-primary">Vedi</a>
                    <?php endif; ?>
                </td>
            </tr>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

<?php else: ?>
    <p>No quotations available.</p>
<?php endif; ?>

        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH /Users/rayfanale/Sites/localhost/grazia/resources/views/quotations/index.blade.php ENDPATH**/ ?>