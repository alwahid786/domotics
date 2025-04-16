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
                <?php if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Manager')): ?>
                    <?php echo e(__('Preventivo di')); ?> <?php echo e($quotation->user->name ?? $quotation->user->email); ?> - <?php echo e($quotation->created_at->format('d/m/Y')); ?>


                <?php else: ?>
                    <?php echo e(__('Il tuo preventivo')); ?> - <?php echo e($quotation->created_at->format('d/m/Y')); ?> - <?php echo e($quotation->title); ?>

                <?php endif; ?>
            </h2>
            <div>
                <a href="<?php echo e(route('quotation.exportPdf', $quotation->id)); ?>" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Esporta PDF</a>
                <form action="<?php echo e(route('quotations.send-email', $quotation->id)); ?>" method="POST"
                      style="display:inline-block;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Invia via Email</button>
                </form>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <main class="mt-6">
        <div class="py-12 ">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="grid gap-6 lg:grid-cols-2 lg:gap-8 bg-white shadow p-4 rounded">
            <div class="pt-3 sm:pt-5 mb-2 p-4">
                <!-- Search and Filter -->



                            <?php if($quotation && $quotation->products->count() > 0): ?>
                                <form action="#" method="POST" class="pt-4">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>

                                    
                                    <?php
                                        $productsGroupedByRoom = $quotation->products->groupBy('pivot.product_room_id');
                                    ?>

                                    <?php $__currentLoopData = $productsGroupedByRoom; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roomId => $products): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $room = \App\Models\Room::find($roomId);
                                        ?>

                                        <h2 class="text-xl font-semibold text-black dark:text-white">Ambiente: <?php echo e($room ? $room->name : 'Unknown Room'); ?> - <?php echo e($room ? $room->code : 'no code'); ?></h2>
                                        <ul class="list-group mb-4">
                                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong><?php echo e($product->name); ?> - <?php echo e($product->code); ?></strong>
                                                        <img src="<?php echo e(asset('storage/' . $product->image)); ?>" alt="<?php echo e($product->name); ?>" class="w-16 h-16 object-cover">
                                                        <br>
                                                        Quantità:
                                                        <input type="number"
                                                               name="quantities[<?php echo e($product->id); ?>][<?php echo e($product->pivot->product_room_id); ?>]"
                                                               value="<?php echo e($product->pivot->quantity); ?>" min="1"
                                                               class="form-control d-inline-block" style="width: 70px;" readonly>
                                                        ×
                                                        <?php if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Manager')): ?>
                                                            <input type="number"
                                                                   name="prices[<?php echo e($product->id); ?>][<?php echo e($product->pivot->product_room_id); ?>]"
                                                                   value="<?php echo e($product->pivot->price); ?>" step="0.01"
                                                                   class="form-control d-inline-block"
                                                                   style="width: 100px;" readonly> (da listino: €<?php echo e($product->priceByRole($quotation->user)); ?>)


                                                        <?php else: ?>
                                                            € <?php echo e($product->pivot->price); ?>

                                                        <?php endif; ?>
                                                        <span class="badge badge-primary badge-pill">
                        = €<?php echo e($product->pivot->quantity * $product->pivot->price); ?>

                    </span>
                                                    </div>

                                                </li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    <h3 class="mt-3 p-4 font-bold text-xl text-green-600 dark:text-green-400">Totale:
                                        €<?php echo e($quotation->products->sum(fn($product) => $product->pivot->quantity * $product->pivot->price)-($quotation->products->sum(fn($product) => $product->pivot->quantity * $product->pivot->price)*$quotation->discount/100)); ?></h3>

                                    <br/>
                                    <br/>
                                </form>

                    <br/>
                    <br/>
                    <h2 class="text-xl font-semibold text-black dark:text-white">Complessivo preventivo</h2>

                    <?php
$aggregatedProducts = $quotation->products->groupBy('id')->map(function ($group) {
    $quantity = $group->sum('pivot.quantity');
    $price = $group->sum(function ($product) {
        return $product->pivot->price * $product->pivot->quantity;
    });

    return [
        'name' => $group->first()->name,
        'quantity' => $quantity,
        'total_price' => $price,
    ];
});
?>

<?php $__currentLoopData = $aggregatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $productId => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <p>Prodotto: <?php echo e($product['name']); ?>, Quantità: <?php echo e($product['quantity']); ?>,  Totale: €<?php echo e($product['total_price']); ?></p>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <p>Your quotation is empty.</p>
                            <?php endif; ?>

            </div>

            <div class="pt-3 sm:pt-5 mb-2 p-5">
                <h2 class="text-xl font-semibold text-black dark:text-white">Perchè scegliere MyDomotics</h2>

                <p class="mt-4 text-sm/relaxed">
                    Da oggi e disponibile in Italia presso MyDomotics questo incredibile nuovo strumento per coloro
                    che si accingono a ristrutturare casa.
                </p>
            </div>


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
<?php endif; ?>
<?php /**PATH /var/www/html/domotics/resources/views/quotations/view.blade.php ENDPATH**/ ?>