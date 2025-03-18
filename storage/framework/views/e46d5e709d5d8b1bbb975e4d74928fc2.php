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
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-800 leading-tight">
            <?php echo e(__('Dashboard')); ?>

        </h2>


     <?php $__env->endSlot(); ?>
<style>
    /* Default 2-column grid */
    .grid-cols-2 {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    /* Small screens (640px and up) - 2-column layout */
    @media (min-width: 640px) {

        .lg\:grid-cols-4{grid-template-columns:repeat(4,minmax(0,1fr))}
        .sm\:grid-cols-2{grid-template-columns:repeat(2,minmax(0, 1fr));
        }

    /* Large screens (1024px and up) - 4-column layout */
    @media (min-width: 1024px) {
        .lg\:grid-cols-4{grid-template-columns:repeat(4,minmax(0,1fr))}

    }


     /*
     .lg\:grid-cols-3{grid-template-columns:repeat(3,minmax(0,1fr))}

    .grid-cols-4{grid-template-columns:repeat(4,minmax(0,1fr))}
    .grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}
    */
    .grid{display:grid}
    .gap-4{grid-gap:1rem}
    .p-4{padding:1rem}
    .rounded{border-radius:.375rem}
    .bg-white{--bg-opacity:1;background-color:#fff;background-color:rgba(255,255,255,var(--bg-opacity))}
</style>
    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white shadow p-4 rounded">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <ul class="grid lg:grid-cols-4 sm:grid-cols-2 gap-4">
                <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="bg-white shadow p-4 rounded">
                            <a href="<?php echo e(route('room.products', ['room' => $room->id])); ?>">
                            <h2 class="text-2xl font-semibold"><?php echo e($room->name); ?></h2>
                            </a>
                            <div class="">
                                <a href="<?php echo e(route('room.products', ['room' => $room->id])); ?>">
                                <img src="<?php echo e(asset('storage/' . $room->image)); ?>" alt="<?php echo e($room->name); ?>" class="w-full h-64 object-cover">
                                </a>
                            </div>
                        </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
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
<?php /**PATH /home/Intuisco/web/mydomotics.intuisco.net/public_html/resources/views/dashboard.blade.php ENDPATH**/ ?>