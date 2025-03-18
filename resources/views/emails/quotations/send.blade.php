@component('mail::message')
    # Dettaglio preventivo

    Gent.mo Cliente,
    di seguito troverà il riepilogo della sua richiesta di preventivo.
    Per qualsiasi informazione o chiarimento non esiti a contattarci.
    Cordiali saluti,
    Il team di MyDomotics


    ## Dettaglio preventivo:
    @foreach ($quotation->products as $product)

        @php
            $room = \App\Models\Room::find($product->pivot->product_room_id);
        @endphp

        {{$room->name}}
        Prodotto {{ $product->name }}
        Quantità {{ $product->pivot->quantity }}
        Prezzo €{{ $product->pivot->price }}
        Subtotale €{{ $product->pivot->quantity * $product->pivot->price }}
    @endforeach

    **Totale:** €{{ $quotation->products->sum(fn($product) => $product->pivot->quantity * $product->pivot->price) }}

    ## Complessivo preventivo
    @php
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
    @endphp

    @foreach ($aggregatedProducts as $productId => $product)
        Prodotto: {{ $product['name'] }}, Quantità: {{ $product['quantity'] }},  Totale: €{{ $product['total_price'] }}
    @endforeach

    **Totale:** €{{ $quotation->products->sum(fn($product) => $product->pivot->quantity * $product->pivot->price) }}

    Grazie per averci scelto!

    Grazie,
    {{ config('app.name') }}

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
@endcomponent
