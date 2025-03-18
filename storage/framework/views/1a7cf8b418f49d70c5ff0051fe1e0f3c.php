<!DOCTYPE html>
<html>
<head>
    <title>Quotation PDF</title>
    <style>
        body {
            font-family: DejaVu Sans;
        }
        .container {
            width: 100%;
            padding: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table, .table th, .table td {
            border: 1px solid black;
        }
        .table th, .table td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Your Quotation</h1>

    <?php if($productsGroupedByRoom->count() > 0): ?>
        <?php $__currentLoopData = $productsGroupedByRoom; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roomId => $products): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $room = \App\Models\Room::find($roomId);
            ?>

            <h3>Room: <?php echo e($room ? $room->name : 'Unknown Room'); ?></h3>
            <table class="table">
                <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($product->name); ?></td>
                        <td><?php echo e($product->pivot->quantity); ?></td>
                        <td>$<?php echo e($product->pivot->price); ?></td>
                        <td>$<?php echo e($product->pivot->quantity * $product->pivot->price); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <h3 class="mt-3">Total Quotation: $<?php echo e($quotation->products->sum(fn($product) => $product->pivot->quantity * $product->pivot->price)); ?></h3>
    <?php else: ?>
        <p>Your quotation is empty.</p>
    <?php endif; ?>
</div>
</body>
</html>
<?php /**PATH /Users/rayfanale/Sites/localhost/grazia/resources/views/quotations/pdf.blade.php ENDPATH**/ ?>