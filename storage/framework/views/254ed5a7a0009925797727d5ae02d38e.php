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
                <?php echo e(__('Modifica ambiente')); ?>

            </h2>
            <div>
                <a href="<?php echo e(route('room.index')); ?>" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Torna agli ambienti
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <main class="mt-6">
        <div class="py-12 ">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 bg-white shadow p-4 rounded">
                <div class="container mx-auto px-4">
    <form action="<?php echo e(route('room.update', $room->id)); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="form-group mb-4">
            <label class="block text-sm font-medium" for="name">Nome</label>
            <input type="text" name="name" id="name" class="form-control border p-2 w-full" value="<?php echo e($room->name); ?>" required>
        </div>
        <div class="form-group mb-4">
            <label class="block text-sm font-medium for="code">Codice</label>
            <textarea name="code" id="code" class="form-control border p-2 w-full"><?php echo e($room->code); ?></textarea>
        </div>
        <div class="form-group mb-4">
            <label class="block text-sm font-medium for="description">Descrizione</label>
            <textarea name="description" id="description" class="form-control border p-2 w-full"><?php echo e($room->description); ?></textarea>
        </div>
        <div class="form-group mb-4">
            <label class="block text-sm font-medium for="image">Immagine</label>
            <input type="file" name="image" id="image" class="form-control border p-2 w-full"">
            <?php if($room->image): ?>
                <img src="<?php echo e(asset('storage/' . $room->image)); ?>" alt="<?php echo e($room->name); ?>" class="img-thumbnail mt-2" width="150">
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Aggiorna</button>
    </form>
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
<?php /**PATH /home/MyDomotics/web/preventivi.mydomotics.it/public_html/resources/views/room/edit.blade.php ENDPATH**/ ?>