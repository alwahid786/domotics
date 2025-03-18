<!DOCTYPE html>
<html>
<head>
    <title>Quotation PDF</title>
    <style>
        body {
            font-family: DejaVu Sans;
            font-size:12px;
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
<div class="header">

    <img src="<?php echo e(public_path('brand/logo-mydomotics.png')); ?>" alt="MyDomotics" width="200">

    <div style="text-align: right; font-size: 12px;">
        Covertec Design S.r.l Via D.Fontana 53/A - 80128 Napoli - www.mydomotics.it - Numero Verde 800.973.138
    </div>

</div>
<div class="container">

    <h3>Oggetto: Offerta relativa alla DOMOTIZZAZIONE della vostra unita' immobiliare</h3>
    <span style="font-size:12px">
    Gent.mo Cliente <?php if(isset($quotation->title)&&$quotation->title!=null): ?> <?php echo e($quotation->title); ?><?php endif; ?>,<br/>
        Su Sua cortese richiesta abbiamo predisposto la presente offerta per la fornitura di materiali e apparecchiature del nostro sistema domotico wireless con protocollo di comunicazione RF/Wi-Fi presso la sua unita' immobilare evidenziata in oggetto.
        <br><br>
La vastissima quantità di connessioni possibili con dispositivi MydomoticS già pronti ci consente di tenere sotto controllo:
<br><br>
        Luci della casa<br>
Tapparelle oscuranti<br>
Finestre motorizzate<br>
Tende interne motorizzate<br>
Tende per esterno motorizzate con sensore anti vento, anti-pioggia e crepuscolare abbinabile alle scene.<br>
Ogni Tipo e ogni Marca di Climatizzatore Split o Canalizzabile.<br>
Controllo delle adduzioni di Acqua e Gas con Possibilità di apertura e chiusura delle erogazioni anche da remoto e chiaramente programmabili in scenari.<br>
Irrigazione Giardino e Balcone.<br>
Sensori di Fuga di Gas. Questo dispositivo e’ spostabile e installabile in ogni ambiente e totalmente libero da connessioni elettriche essendo alimentato a Batteria.<br>
Sensori di Fumo Questo dispositivo e’ spostabile e installabile in ogni ambiente e totalmente libero da connessioni elettriche essendo alimentato a Batteria.<br>
Sensori di Allagamento. Questo dispositivo e’ spostabile e installabile in ogni ambiente e totalmente libero da connessioni elettriche essendo alimentato a Batteria.<br>
Qualsiasi TV sia esso del tipo Smart o privo di qualsiasi modulo intelligente od interfaccia Qualsiasi dispositivo HI-FI Audio sia esso Smart o senza interfacce intelligenti<br>
Qualsiasi Decoder TV per la gestione della visione via Satellite<br>
Qualsiasi Videoproiettore Inserimento di dispositivi RGB per cromoterapia o effetti scenici in ambienti particolari come bar , discoteche.... o esterni di edifici che possono essere colorati in modi diversi .I nostri Controller WIFI RGB possono gestire sia Barre Led RGB che le lampade.<br>
Dimmer per regolazioni luci sia esse in led che ad incandescenza.<br>
Bottone SOS per Docce o altri ambienti pericolosi. Questo dispositivo e’ spostabile e installabile in ogni ambiente e totalmente libero da connessioni elettriche essendo alimentato a Batteria.<br>
<br>Qui di seguito le elenchiamo i dispositivi da lei richiesti.<br><br><br><br>

    <br/>
    Cordiali saluti,<br/>
    Il team di MyDomotics<br/><br/><br/><br/><br/><br/><br/><br/>
    </span>
<h3>Distinta della fornitura suddivisa per ambiente di installazione</h3><br/><br/>
    <?php if($productsGroupedByRoom->count() > 0): ?>
        <?php $__currentLoopData = $productsGroupedByRoom; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roomId => $products): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $room = \App\Models\Room::find($roomId);
            ?>

            <h3>Ambiente: <?php echo e($room ? $room->name : 'Unknown Room'); ?> <?php echo e($room ? $room->code : 'no code'); ?></h3>
            <table class="table">
                <thead>
                <tr>
                    <th style="width: 75px;">Codice</th>
                    <th style="width: 175px;">Prodotto</th>
                    <th>Note</th>
                    <th style="width: 50px;">Qtà</th>
                    <th style="width: 65px;">Prezzo</th>
                    <th style="width: 75px;">Subtotale</th>
                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($product->code); ?>


                        </td>
                        <td><?php echo e($product->name); ?><br>
                            <?php if(isset($product->image) && $product->image != ''): ?>
                                <img src="<?php echo e(public_path('storage/' . $product->image)); ?>" alt="<?php echo e($product->name); ?>" width="150">
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($product->pivot->note); ?></td>
                        <td><?php echo e($product->pivot->quantity); ?></td>
                        <td>€<?php echo e($product->pivot->price); ?></td>
                        <td>€<?php echo e($product->pivot->quantity * $product->pivot->price); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td colspan="5" style="text-align: right;">Totale per ambiente <?php echo e($room ? $room->name : 'Unknown Room'); ?> :</td>
                    <td> €<?php echo e($products->sum(fn($product) => $product->pivot->quantity * $product->pivot->price)); ?>

                    </td>
                </tr>
                </tbody>
            </table>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <h3 class="mt-3"></h3>
    <?php else: ?>
        <p>Your quotation is empty.</p>
    <?php endif; ?>
    <h3>Riepilogo per totale dispositivo</h3>
    <?php
        $aggregatedProducts = $quotation->products->groupBy('id')->map(function ($group) {
            $quantity = $group->sum('pivot.quantity');
            $price = $group->sum(function ($product) {
                return $product->pivot->price * $product->pivot->quantity;
            });

            return [
                'name' => $group->first()->name,
                'code' => $group->first()->code,
                'quantity' => $quantity,
                'total_price' => $price,
            ];
        });
    ?>
    <span style="font-size:12px">
        <table class="table">
                <thead>
                <tr>
                    <th>Prodotto</th>
                    <th>Codice</th>
                    <th>Qtà</th>
                    <th>Subtotale</th>
                </tr>
                </thead>
                <tbody>

    <?php $__currentLoopData = $aggregatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $productId => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($product['name']); ?></td>
            <td><?php echo e($product['code']); ?></td>
            <td><?php echo e($product['quantity']); ?></td>
            <td>€<?php echo e($product['total_price']); ?></td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td colspan="3" style="text-align: right;">Totale Preventivo:</td>
                    <td> €<?php echo e($quotation->products->sum(fn($product) => $product->pivot->quantity * $product->pivot->price)); ?>

                    </td>
                </tr>
    </tbody>
            </table>

    </span><br/><br/><br/><br/>
    <span style="font-size:10px">
    <h4>Tempistica fornitura</h4>
    20 gg lavorativi a partire dalla ricezione del pagamento relativo al primo acconto e Vostra conferma di ordine mediante restituzione del presente contratto firmato per accettazione
    <br/>- Arrivo fornitura presso sede di MydomoticS
    <h4>Cantiere</h4>
    2 gg dalla avviso di consegna fornitura al cliente (contestuale all’arrivo della fornitura presso sede MyDomoticS) e contestuale ricezione pagamento relativo al II° acconto
    <h4>Condizioni pagamento</h4>
    -Primo Acconto pari al 40% oltre Iva alla conferma di ordine per avvio produzione fornitura
    <br/>-Saldo pari al 60% oltre Iva alla consegna degli stessi prodotti che avverrà presso Vs cantiere/ufficio entro 48 ore dal
    ricevimento del pagamento
    <h4>Garanzia / Assistenza post vendita</h4>
    -Garanzia su ciascun componente oggetto di fornitura pari a 2 anni per completa sostituzione in caso di malfunzionamento per causa imputabile al fornitore
    <br/>-Assistenza da remoto nelle 24 ore lavorative successive alla vs chiamata
    <br/>-Assistenza Post vendita mediante intervento dedicato con presenza di ns operatore presso il vs immobile nelle 48 ore lavorative successive alla vs chiamata di pronto intervento al ns N. Verde dedicato
    </span>
</div>
</body>
</html>
<?php /**PATH /home/Intuisco/web/mydomotics.intuisco.net/public_html/resources/views/quotations/pdf.blade.php ENDPATH**/ ?>