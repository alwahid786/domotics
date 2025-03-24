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
                <a href="<?php echo e(route('quotation.create')); ?>" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    + Crea nuovo preventivo
                </a>

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
                            <th class="px-4 py-2">Preventivo</th>
                            <th class="px-4 py-2">Utente</th>
                            <th class="px-4 py-2">Stato</th>
                            <th class="px-4 py-2">n. prodotti</th>
                            <th class="px-4 py-1">Azioni</th>
                            <th class="px-4 py-1">Quote</th>
                        </tr>
                        </thead>
                        <tbody>
        <?php $__currentLoopData = $quotations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quotation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="border px-4 py-2">
                    <a href="<?php echo e(route('quotation.titlechange', $quotation->id)); ?>" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"> <i class="fas fa-edit mr-2"></i>  #<?php echo e($quotation->id); ?></a>
                    <a href="<?php echo e(route('quotation.titlechange',$quotation->id)); ?>"><?php echo e($quotation->title); ?></a>


                </td>
                <td class="border px-4 py-2">
                    <?php echo e($quotation->user->name ?? $quotation->user->email); ?>

                </td>
                <td class="border px-4 py-2">
                    <?php echo e($quotation->status); ?>

                </td>
                <td class="border px-4 py-2">
                    <?php echo e($quotation->products->count()); ?>

                </td>
                <td class="border px-4 py-2">
                    <?php if(($quotation->status==='confirmed'||$quotation->status==='pending')&& Auth::user()->hasRole('Admin')): ?>
                        <a href="<?php echo e(route('quotations.edit', $quotation->id)); ?>" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"> <i class="fas fa-edit mr-2"></i> </a>
                        <form action="<?php echo e(route('quotations.destroy', $quotation->id)); ?>" method="POST" style="display: inline-block;">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150" onclick="return confirm('Are you sure you want to delete this quotation?')"> <i class="fas fa-trash-alt mr-2"></i> </button>
                        </form>

                    <?php elseif($quotation->status==='pending'): ?>
                        <a href="<?php echo e(route('quotations.edit', $quotation->id)); ?>" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"><i class="fas fa-edit mr-2"></i></a>

                    <?php else: ?>
                        <a href="<?php echo e(route('quotations.view', $quotation->id)); ?>" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"><i class="fas fa-file-alt mr-2"></i></a>
                    <?php endif; ?>

                </td>
                <td class="border px-4 py-2">
                        <?php if($quotation->status!='completed'): ?>
                        <?php if($quotation->pdf_path): ?>
                            <form action="<?php echo e(route('quotations.removePdf', $quotation->id)); ?>" method="POST" style="display: inline-block;">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 dark:bg-red-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-red-800 uppercase tracking-widest hover:bg-red-700 dark:hover:bg-red-300 focus:bg-red-700 dark:focus:bg-red-300 active:bg-red-900 dark:active:bg-red-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-red-800 transition ease-in-out duration-150"><i class="fas fa-trash-alt mr-2"></i> </button>
                            </form>
                        <?php else: ?>
                            <?php if($quotation->user_id==\Illuminate\Support\Facades\Auth::user()->id): ?>
                            <style>
                                .custom-file-input {
                                    display: none;
                                }

                                .custom-file-label {
                                    display: inline-block;
                                    padding: 0.5rem 1rem;
                                    font-size: 0.875rem;
                                    font-weight: 600;
                                    color: #fff;
                                    background-color: #4a5568;
                                    border: 1px solid transparent;
                                    border-radius: 0.375rem;
                                    cursor: pointer;
                                    transition: background-color 0.15s ease-in-out;
                                }

                                .custom-file-label:hover {
                                    background-color: #2d3748;
                                }

                                .custom-file-label:focus {
                                    outline: none;
                                    box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.5);
                                }
                            </style>

                            <form action="<?php echo e(route('quotations.uploadPdf', $quotation->id)); ?>" method="POST" enctype="multipart/form-data" style="display: inline-block;">
                                <?php echo csrf_field(); ?>
                                <label for="pdf" class="custom-file-label">Scegli PDF</label>
                                <input type="file" name="pdf" id="pdf" accept="application/pdf" class="custom-file-input" required>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Carica PDF</button>
                            </form>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if($quotation->pdf_path): ?>
                        <a href="<?php echo e(Storage::url($quotation->pdf_path)); ?>" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"><i class="fas fa-file-alt mr-2"></i></a>

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
<?php /**PATH C:\xampp\htdocs\domotics\resources\views/quotations/index.blade.php ENDPATH**/ ?>