<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $floorName }} - Estimation</title>
    <style>
        body { font-family: sans-serif; }
        .floorplan {
            position: relative;
            width: 100%;
        }
        .floorplan img {
            width: 100%;
            display: block;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        th, td {
            border: 1px solid #444;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>

    <div class="floorplan">
        <img src="{{ public_path('uploads/estimations/MyDomoticsInvoice.png') }}" alt="Header">
    </div>

    <h2>{{ $floorName }} - Estimation Report</h2>

    <p>
        Oggetto :Offerta relativa alla DOMOTIZZAZIONE della vostra unita' immobiliare Palazzo Sanfelice<br><br>
        Su Sua cortese richiesta abbiamo predisposto la presente offerta per la fornitura di materiali e apparecchiature del nostro sistema domotico wireless con protocollo di comunicazione RF/Wi-Fi presso la sua unita' immobilare evidenziata in oggetto.<br><br><br>
        La vastissima quantità di connessioni possibili con dispositivi MydomoticS già pronti ci consente di tenere sotto controllo:<br>
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
        Qualsiasi TV sia esso del tipo Smart o privo di qualsiasi modulo intelligente od interfaccia<br>
        Qualsiasi dispositivo HI-FI Audio sia esso Smart o senza interfacce intelligenti<br>
        Qualsiasi Decoder TV per la gestione della visione via Satellite<br>
        Qualsiasi Videoproiettore<br>
        Inserimento di dispositivi RGB per cromoterapia o effetti scenici in ambienti particolari come bar , discoteche…. o esterni di edifici che possono essere colorati in modi diversi .I nostri Controller WIFI RGB possono gestire sia Barre Led RGB che le lampade.<br>
        Dimmer per regolazioni luci sia esse in led che ad incandescenza.<br>
        Bottone SOS per Docce o altri ambienti pericolosi. Questo dispositivo e’ spostabile e installabile in ogni ambiente e totalmente libero da connessioni elettriche essendo alimentato a Batteria.
    </p>

    <p><b>Qui di seguito le elenchiamo i dispositivi da lei richiesti</b></p>

    <p><b>Distinta della fornitura suddivisa per ambiente di installazione</b></p>
    <br>
    <div class="floorplan">
        <img src="{{ public_path($imagePath) }}" alt="Floor Plan">
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Sensor Name</th>
                <th>Installation Notes</th>
                <th>Room</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sensorsData as $index => $sensor)
                @php
                    $room = collect($roomsData)->firstWhere('roomId', $sensor->room_id);
                    $roomName = $room['roomName'] ?? 'Unknown';
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $sensor->name }}</td>
                    <td>{{ $sensor->note }}</td>
                    <td>{{ $roomName }}</td>
                    <td>{{ number_format($sensor->price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align:right;"><strong>Total</strong></td>
                <td><strong>${{ number_format($totalPrice, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <p><b>Tempistica di fornitura</b></p>

    <p>
        <b>Tempistica arrivo fornitura e consegna in cantiere</b><br>
        • 20 gg lavorativi a partire dalla ricezione del pagamento relativo al primo acconto e Vostra conferma di ordine mediante restituzione del presente contratto firmato per accettazione - Arrivo fornitura presso sede di MydomoticS
    </p>

    <p>
        <b>Consegna fornitura in cantiere</b><br>
        • 2 gg dalla avviso di consegna fornitura al cliente (contestuale all’arrivo della fornitura presso sede MyDomoticS) e contestuale ricezione pagamento relativo al II° acconto
    </p>

    <p>
        <b>Condizioni di pagamento</b><br>
        • Primo Acconto pari al 40% oltre Iva alla conferma di ordine per avvio produzione fornitura<br>
        • Saldo pari al 60% oltre Iva alla consegna degli stessi prodotti che avverrà presso Vs cantiere/ufficio entro 48 ore dal ricevimento del pagamento
    </p>

    <p>
        <b>Garanzie / Assistenza Post Vendita</b><br>
        • Garanzia su ciascun componente oggetto di fornitura pari a 2 anni per completa sostituzione in caso di malfunzionamento per causa imputabile al fornitore<br>
        • Assistenza da remoto nelle 24 ore lavorative successive alla vs chiamata<br>
        • Assistenza Post vendita mediante intervento dedicato con presenza di ns operatore presso il vs immobile nelle 48 ore lavorative successive alla vs chiamata di pronto intervento al ns N. Verde dedicato
    </p>

    <p>In attesa di vs. riscontro inviamo distinti saluti</p>

    <table style="width: 100%; margin-top: 30px;border:0 !important;">
        <tr>
            <td style="text-align: left; width: 50%; border:0 !important;">
                <img src="{{ public_path('uploads/estimations/logos.png') }}" alt="Logos" style="width: 200px; height: auto;">
            </td>
            <td style="text-align: right; width: 50%; border:0 !important;">
                <img src="{{ public_path('uploads/estimations/sign.png') }}" alt="Sign" style="width: 200px; height: auto;">
            </td>
        </tr>
    </table>

</body>
</html>
