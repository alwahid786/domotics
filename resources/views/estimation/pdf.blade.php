<!DOCTYPE html>
<html>
<head>
    <title>Estimation PDF</title>
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

    <img src="{{public_path('brand/logo-mydomotics.png')}}" alt="MyDomotics" width="200">

    <div style="text-align: right; font-size: 12px;">
        Covertec Design S.r.l Via D.Fontana 53/A - 80128 Napoli - www.mydomotics.it - Numero Verde 800.973.138
    </div>

</div>
<div class="container">

    <h3>Oggetto: Offerta relativa alla DOMOTIZZAZIONE della vostra unita' immobiliare</h3>
    <span style="font-size:12px">
    Gent.mo Cliente @if(isset($quotation->title)&&$quotation->title!=null) {{$quotation->title}}@endif,<br/>
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
    @if ($productsGroupedByRoom->count() > 0)
        @foreach ($productsGroupedByRoom as $roomId => $products)
            @php
                $room = \App\Models\Room::find($roomId);
            @endphp

            <h3>Ambiente: {{ $room ? $room->name : 'Unknown Room' }} {{ $room ? $room->code : 'no code' }}</h3>
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
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->code }}

                        </td>
                        <td>{{ $product->name }}<br>
                            @if(isset($product->image) && $product->image != '')
                                <img src="{{ public_path('storage/' . $product->image) }}" alt="{{ $product->name }}" width="150">
                            @endif
                        </td>
                        <td>{{ $product->pivot->note }}</td>
                        <td>{{ $product->pivot->quantity }}</td>
                        <td>€{{ $product->pivot->price }}</td>
                        <td>€{{ $product->pivot->quantity * $product->pivot->price }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="5" style="text-align: right;">Totale per ambiente {{ $room ? $room->name : 'Unknown Room' }} :</td>
                    <td> €{{ $products->sum(fn($product) => $product->pivot->quantity * $product->pivot->price) }}
                    </td>
                </tr>
                </tbody>
            </table>
        @endforeach

        <h3 class="mt-3"></h3>
    @else
        <p>Your quotation is empty.</p>
    @endif
    <h3>Riepilogo per totale dispositivo</h3>
    @php
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
    @endphp
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

    @foreach ($aggregatedProducts as $productId => $product)
        <tr>
            <td>{{ $product['name'] }}</td>
            <td>{{ $product['code'] }}</td>
            <td>{{ $product['quantity'] }}</td>
            <td>€{{ $product['total_price'] }}</td>
        </tr>
    @endforeach
                <tr>
                    <td colspan="3" style="text-align: right;">Totale Preventivo:</td>
                    <td> €{{ $quotation->products->sum(fn($product) => $product->pivot->quantity * $product->pivot->price) }}
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
