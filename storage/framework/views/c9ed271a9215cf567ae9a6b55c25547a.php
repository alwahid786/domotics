<?php $__env->startComponent('mail::message'); ?>
    # Dettaglio preventivo

    Gent.mo Cliente,
    di seguito troverà il riepilogo della sua richiesta di preventivo.
    Per qualsiasi informazione o chiarimento non esiti a contattarci.
    Cordiali saluti,
    Il team di MyDomotics


    ## Dettaglio preventivo:
    <?php $__currentLoopData = $quotation->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <?php
            $room = \App\Models\Room::find($product->pivot->product_room_id);
        ?>

        <?php echo e($room->name); ?>

        Prodotto <?php echo e($product->name); ?>

        Quantità <?php echo e($product->pivot->quantity); ?>

        Prezzo €<?php echo e($product->pivot->price); ?>

        Subtotale €<?php echo e($product->pivot->quantity * $product->pivot->price); ?>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    **Totale:** €<?php echo e($quotation->products->sum(fn($product) => $product->pivot->quantity * $product->pivot->price)); ?>


    ## Complessivo preventivo
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
        Prodotto: <?php echo e($product['name']); ?>, Quantità: <?php echo e($product['quantity']); ?>,  Totale: €<?php echo e($product['total_price']); ?>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    **Totale:** €<?php echo e($quotation->products->sum(fn($product) => $product->pivot->quantity * $product->pivot->price)); ?>


    Grazie per averci scelto!

    Grazie,
    <?php echo e(config('app.name')); ?>


    #Tempistica fornitura
    20 gg lavorativi a partire dalla ricezione del pagamento relativo al primo acconto e Vostra conferma di ordine mediante restituzione del presente contratto firmato per accettazione - Arrivo fornitura presso sede di MydomoticS
    Cantiere
    2 gg dalla avviso di consegna fornitura al cliente (contestuale all’arrivo della fornitura presso sede MyDomoticS) e contestuale ricezione pagamento relativo al II° acconto
    #Condizioni pagamento
    -Primo Acconto pari al 40% oltre Iva alla conferma di ordine per avvio produzione fornitura
    -Saldo pari al 60% oltre Iva alla consegna degli stessi prodotti che avverrà presso Vs cantiere/ufficio entro 48 ore dal
    ricevimento del pagamento
    #Garanzia / Assistenza post vendita
    -Garanzia su ciascun componente oggetto di fornitura pari a 2 anni per completa sostituzione in caso di malfunzionamento per causa imputabile al fornitore
    -Assistenza da remoto nelle 24 ore lavorative successive alla vs chiamata
    -Assistenza Post vendita mediante intervento dedicato con presenza di ns operatore presso il vs immobile nelle 48 ore lavorative successive alla vs chiamata di pronto intervento al ns N. Verde dedicato
<?php echo $__env->renderComponent(); ?>
<?php /**PATH /home/Intuisco/web/mydomotics.intuisco.net/public_html/resources/views/emails/quotations/send.blade.php ENDPATH**/ ?>