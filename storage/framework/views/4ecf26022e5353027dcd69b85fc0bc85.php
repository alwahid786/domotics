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
                    <?php echo e(__('Preventivo di')); ?> <?php echo e($quotation->user->name ?? $quotation->user->email); ?>

                    - <?php echo e($quotation->created_at->format('d/m/Y')); ?> - <a
                        href="<?php echo e(route('quotation.titlechange',$quotation->id)); ?>"><?php echo e($quotation->title); ?></a>

                <?php else: ?>
                    <?php echo e(__('Il tuo preventivo')); ?>

                <?php endif; ?>
            </h2>
            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>
            <div>
                <a href="<?php echo e(route('quotation.exportPdf', $quotation->id)); ?>"
                   class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Esporta
                    PDF</a>
                <form action="<?php echo e(route('quotations.send-email', $quotation->id)); ?>" method="POST"
                      style="display:inline-block;">
                    <?php echo csrf_field(); ?>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        Invia via email
                    </button>
                </form>
            </div>
        </div>
     <?php $__env->endSlot(); ?>
    <style>
        .quote {
            width: 200px;
            max-height: 200px;
        }
    </style>
    <main class="mt-6">
        <div class="py-12 ">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="grid gap-6 lg:grid-cols-2 lg:gap-8 bg-white shadow p-4 rounded">
                    <div class="pt-3 sm:pt-5 mb-2 p-4" id="listaProdottiInPreventivo">
                        <!-- Search and Filter -->


                        <?php if($quotation && $quotation->products->count() > 0): ?>
                            <form action="<?php echo e(route('quotation.update')); ?>" method="POST" class="pt-4">
                                <input type="hidden" name="quotation_id" value="<?php echo e($quotation->id); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>

                                
                                <?php
                                    $productsGroupedByRoom = $quotation->products->groupBy('pivot.product_room_id');
                                ?>

                                <?php $__currentLoopData = $productsGroupedByRoom; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roomId => $products): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $room = \App\Models\Room::find($roomId);
                                    ?>

                                    <h2 class="text-xl font-semibold text-black dark:text-white">
                                        Ambiente: <?php echo e($room ? $room->name : 'Unknown Room'); ?> - (totale prodotti: €<?php echo e($products->sum(fn($product) => $product->pivot->quantity * $product->pivot->price)); ?>)</h2>
                                    <ul class="list-group mb-4">

                                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <table>
                                                        <tr style="padding-top: 10px;display: grid;">
                                                            <td colspan="2">
                                                                <strong><?php echo e($product->name); ?>

                                                                    - <?php echo e($product->code); ?></strong>
                                                                <span class="badge badge-primary badge-pill"
                                                                      style="float:right;color:red;">
                                                                        <a href="<?php echo e(route('quotation.remove', [$product->id, $quotation, $roomId])); ?>"
                                                                            class="btn btn-danger font-extrabold"
                                                                            onclick="return confirm('Sei sicuro di voler cancellare <?php echo e($product->name); ?> da <?php echo e($room->name); ?>?');">
                                                                            <i class="fas fa-trash-alt mr-2"></i>
                                                                        </a>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr style="border-bottom:1px dotted #ccc; padding-bottom: 20px;display: inline;">
                                                            <td>
                                                                <img src="<?php echo e(asset('storage/' . $product->image)); ?>"
                                                                     alt="Product Image"
                                                                     class="w-24 h-24 mt-2 quote">
                                                            </td>
                                                            <td style="padding: 5px;">
                                                                Quantità:
                                                                <input type="number"
                                                                       name="quantities[<?php echo e($product->id); ?>][<?php echo e($product->pivot->product_room_id); ?>]"
                                                                       value="<?php echo e($product->pivot->quantity); ?>" min="1"
                                                                       class="form-control d-inline-block"
                                                                       style="width: 70px;">
                                                                ×
                                                                <?php if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Manager')): ?>
                                                                    <input type="number"
                                                                           name="prices[<?php echo e($product->id); ?>][<?php echo e($product->pivot->product_room_id); ?>]"
                                                                           value="<?php echo e($product->pivot->price); ?>"
                                                                           step="0.01"
                                                                           class="form-control d-inline-block"
                                                                           style="width: 100px;">

                                                                <?php else: ?>
                                                                    € <?php echo e($product->pivot->price); ?>

                                                                    <input type="hidden"
                                                                           name="prices[<?php echo e($product->id); ?>][<?php echo e($product->pivot->product_room_id); ?>]"
                                                                           value="<?php echo e($product->pivot->price); ?>"
                                                                           step="0.01"
                                                                           class="form-control d-inline-block"
                                                                           style="width: 100px;">
                                                                <?php endif; ?>
                                                                <span class="badge badge-primary badge-pill">
                                                        = €<?php echo e($product->pivot->quantity * $product->pivot->price); ?>

                                                    </span>
                                                                <span class="badge badge-primary badge-pill">

                                                                    <br>
                                                                     (da listino: €<?php echo e($product->priceByRole($quotation->user)); ?>)
                                                    </span>
                                                                <br><br><label for="note">Note</label>
                                                                <input type="text"
                                                                       name="note[<?php echo e($product->id); ?>][<?php echo e($product->pivot->product_room_id); ?>]"
                                                                       id="note[<?php echo e($product->id); ?>][<?php echo e($product->pivot->product_room_id); ?>]"
                                                                       value="<?php echo e($product->pivot->note); ?>">

                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>

                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php if(Auth::user()->hasRole('Admin')): ?>
                                    <br/>
                                    <br/>
                                    <div class="form-group">
                                        <label for="discount">Sconto (%)</label>

                                        <input type="number" name="discount" id="discount"
                                               value="<?php echo e(old('discount', $quotation->discount?$quotation->discount:0)); ?>" step="0.01"
                                               class="form-control" size="5">
                                    </div>
                                <?php endif; ?>

                                <h3 class="mt-3 p-4 font-bold text-xl text-green-600 dark:text-green-400">Totale:
                                    €<?php echo e($quotation->products->sum(fn($product) => $product->pivot->quantity * $product->pivot->price)-($quotation->products->sum(fn($product) => $product->pivot->quantity * $product->pivot->price)*$quotation->discount/100)); ?></h3>
                                <br/>
                                <br/>
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Aggiorna preventivo
                                </button>
                                <?php if($quotation->status=='confirmed'&& Auth::user()->hasRole('Admin')): ?>
                                    <a href="<?php echo e(route('quotations.complete',$quotation->id)); ?>"
                                       class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Completa
                                        preventivo</a>
                                <?php else: ?>
                                    <a href="<?php echo e(route('quotations.confirm',$quotation->id)); ?>"
                                       class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Conferma
                                        preventivo</a>
                                <?php endif; ?>
                            </form>

                            <br/>
                            <br/>
                            <h4 class="text-xl font-semibold text-black dark:text-white">Una volta confermato il preventivo potrai, se necessario, aggiungere una piantina in pdf dalla sezione Preventivi</h4>
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
                                <p>Prodotto: <?php echo e($product['name']); ?>, Quantità: <?php echo e($product['quantity']); ?>, Totale:
                                    €<?php echo e($product['total_price']); ?></p>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <p>Your quotation is empty.</p>
                        <?php endif; ?>

                    </div>

                    <div class="pt-3 sm:pt-5 mb-2 p-5">
                        <h2 class="text-xl font-semibold text-black dark:text-white">Perchè scegliere MyDomotics</h2>

                        <p class="mt-4 text-sm/relaxed">
                            Da oggi e disponibile in Italia presso MyDomotics questo incredibile nuovo strumento
                            per coloro
                            che si accingono a ristrutturare casa.
                        </p>

                        <h2 class="text-xl font-semibold text-black dark:text-white">Aggiungi un prodotto al
                            preventivo</h2>


                        <div class="form-group mb-4">
                            <label for="product_id">Ambiente</label>

                            <select id="room-select" class="form-control">
                                <option value="">Scegli un ambiente</option>
                                <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($room->id); ?>"><?php echo e($room->name); ?> - <?php echo e($room->code); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>

                            <h6>Prodotti</h6>
                            <div id="product-list"></div>

                            <script>
                                document.getElementById('room-select').addEventListener('change', function () {
                                    var roomId = this.value;

                                    if (roomId) {
                                        fetch(`/products/search?room_id=${roomId}`)
                                            .then(response => response.json())
                                            .then(data => {
                                                var productList = document.getElementById('product-list');
                                                productList.innerHTML = '';

                                                data.forEach(product => {
                                                    var productItem = document.createElement('div');
                                                    productItem.textContent = product.name;

                                                    var addButton = document.createElement('button');
                                                    addButton.textContent = 'Aggiungi al preventivo';
                                                    addButton.classList.add('btn', 'dark:bg-gray-200');
                                                    addButton.classList.add('btn', 'border');
                                                    addButton.classList.add('btn', 'inline-flex');
                                                    addButton.classList.add('btn', 'bg-gray-800');
                                                    addButton.classList.add('btn', 'text-white');
                                                    addButton.classList.add('btn', 'text-xs');
                                                    addButton.addEventListener('click', function () {
                                                        fetch(`/quotation/add-product`, {
                                                            method: 'POST',
                                                            headers: {
                                                                'Content-Type': 'application/json',
                                                                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                                                            },
                                                            body: JSON.stringify({
                                                                product_id: product.id,
                                                                quotation_id: <?php echo e($quotation->id); ?>,
                                                                roomId: roomId
                                                            })
                                                        })
                                                            .then(response => response.json())
                                                            .then(data => {
                                                                if (data.success) {
                                                                    alert('Product added to quotation successfully');
                                                                    refreshQuotationProducts();
                                                                } else {
                                                                    alert('Failed to add product to quotation');
                                                                }
                                                            })
                                                            .catch(error => {
                                                                console.error('Error:', error);
                                                                alert('Failed to add product to quotation');
                                                            });
                                                    });

                                                    productItem.appendChild(addButton);
                                                    productList.appendChild(productItem);
                                                });
                                            });
                                    } else {
                                        document.getElementById('product-list').innerHTML = '';
                                    }
                                });

                                function refreshQuotationProducts() {
                                    window.location.reload();
                                }
                            </script>
                        </div>


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
<?php /**PATH /home/MyDomotics/web/preventivi.mydomotics.it/public_html/resources/views/quotations/edit.blade.php ENDPATH**/ ?>