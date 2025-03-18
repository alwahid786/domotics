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
        <?php echo e($room->name); ?>

    </h2>
            <a href="<?php echo e(route('dashboard')); ?>" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                < Torna a tutti gli ambienti
            </a>
    <div>
        <?php if(($quotation?->products->count()>0)): ?>
            <a href="<?php echo e(route('quotation.current.view')); ?>" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Vai al carrello
            </a>
        <?php endif; ?>


    </div>
</div>
     <?php $__env->endSlot(); ?>



    <!-- Search and Filter -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 bg-white shadow p-4 rounded">
            <!-- Products Table -->
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2">#Prodotto</th>
                            <th class="px-4 py-2">Codice</th>
                            <th class="px-4 py-2">Nome</th>
                            <th class="px-4 py-2">Prezzo</th>
                            <th class="px-4 py-2">Ambiente</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="border px-4 py-2"><a href="<?php echo e(route('products.show', [$product->id, $room])); ?>"> <?php echo e($product->id); ?></a></td>
                                <td class="border px-4 py-2"><a href="<?php echo e(route('products.show', [$product->id, $room])); ?>"> <?php echo e($product->code); ?></a></td>

                                <td class="border px-4 py-2"><a href="<?php echo e(route('products.show', [$product->id, $room])); ?>"><img src="<?php echo e(asset('storage/' . $product->image)); ?>" alt="<?php echo e($product->name); ?>" class="w-16 h-16 object-cover"><?php echo e($product->name); ?></a></td>
                                <td class="border px-4 py-2">€<?php echo e($product->priceByRole(Auth::user())); ?></a></td>
                                <td class="border px-4 py-2"><?php echo e($room->name); ?></td>
                                <td class="border px-4 py-2">
                                    <form action="<?php echo e(route('quotation.add', [$product->id, $room])); ?>" method="POST" style="display:inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('POST'); ?>
                                        <input type="text" value="1" required name="quantity" id="quantity" class="w-60" size="6">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Aggiungi al preventivo</button>
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
<?php /**PATH /home/Intuisco/web/mydomotics.intuisco.net/public_html/resources/views/products/shop.blade.php ENDPATH**/ ?>