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
                <?php echo e(__('Prodotti')); ?>

            </h2>
            <div>
                <a href="<?php echo e(route('products.create')); ?>" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Stai aggiungendo un prodotto
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>



    <!-- Search and Filter -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">



                <div class="container mx-auto px-4">
                    <h1 class="text-2xl font-bold my-4">Create New Product</h1>
                    <?php if(session('success')): ?>
                        <div class="alert alert-success">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <!-- Form to create the product -->
                    <form action="/products/store" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>

                        <!-- Product Name -->
                        <div class="form-group mb-4">
                            <label for="name" class="block text-sm font-medium">Product Name:</label>
                            <input type="text" name="name" id="name" value="<?php echo e(old('name')); ?>" class="form-control border p-2 w-full" required>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Description -->
                        <div class="form-group mb-4">
                            <label for="description" class="block text-sm font-medium">Description:</label>
                            <textarea name="description" id="description" class="form-control border p-2 w-full"><?php echo e(old('description')); ?></textarea>
                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Status -->
                        <div class="form-group mb-4">
                            <label for="status" class="block text-sm font-medium">Status:</label>
                            <select name="status" id="status" class="form-control border p-2 w-full" required>
                                <option value="1" <?php echo e(old('status') == '1' ? 'selected' : ''); ?>>Active</option>
                                <option value="0" <?php echo e(old('status') == '0' ? 'selected' : ''); ?>>Inactive</option>
                            </select>
                            <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>



                        <!-- Image Upload -->
                        <div class="form-group mb-4">
                            <label for="image" class="block text-sm font-medium">Product Image:</label>
                            <input type="file" name="image" id="image" class="form-control border p-2 w-full">
                            <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Room Selection (Select2) -->


                        <div class="form-group">
                            <label for="rooms">Rooms</label>
                            <select name="rooms[]" class="form-control" multiple required>
                                <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($room->id); ?>" <?php echo e(in_array($room->id, old('rooms', [])) ? 'selected' : ''); ?>><?php echo e($room->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="roles">Roles and Prices</label>
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="row mb-3">
                                    <div class="col">
                                        <input type="checkbox" name="roles[]" value="<?php echo e($role->id); ?>" <?php echo e(in_array($role->id, old('roles', [])) ? 'checked' : ''); ?>> <?php echo e($role->name); ?>

                                    </div>
                                    <div class="col">
                                        <input type="text" name="prices[]" class="form-control" placeholder="Enter price for <?php echo e($role->name); ?>" value="<?php echo e(old('prices')[$loop->index] ?? ''); ?>" required>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary px-4 py-2 bg-blue-500 text-white">Create Product</button>
                        </div>
                    </form>
                </div>

                <!-- Include jQuery (required for Select2) -->
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                <!-- Include Select2 JS and CSS -->
                <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
                <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

                <!-- Initialize Select2 on the Room select -->
                <script>
                    $(document).ready(function() {
                        $('#rooms').select2({
                            placeholder: "Select Room(s)",
                            allowClear: true
                        });
                    });
                </script>




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
<?php /**PATH /Users/rayfanale/Sites/localhost/grazia/resources/views/products/create.blade.php ENDPATH**/ ?>