<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ $floorName }} - Estimation</title>
    <style>
    body {
        font-family: sans-serif;
    }

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

    th,
    td {
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
        Oggetto :Offerta relativa alla DOMOTIZZAZIONE della vostra ...
        <!-- (text truncated for brevity—the same as your original) -->
    </p>

    <p><b>Qui di seguito le elenchiamo i dispositivi da lei richiesti</b></p>
    <p><b>Distinta della fornitura suddivisa per ambiente di installazione</b></p>

    <div class="floorplan">
        <img src="{{ public_path($imagePath) }}" alt="Floor Plan">
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Sensor Name</th>
                <th>Image</th>
                <th>Installation Notes</th>
                <th>Room</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sensorsData as $index => $sensor)
            @php
            // find the room name
            $room = collect($roomsData)->firstWhere('roomId', $sensor->room_id);
            $roomName = $room['roomName'] ?? 'Unknown';

            // figure out relative path in storage/app/public
            $relative = $sensor->raw_image_path
            ?? $sensor->image
            ?? null;

            // absolute disk path
            $diskPath = $relative
            ? storage_path('app/public/' . $relative)
            : null;

            // base64-encode if file exists
            $base64 = null;
            if ($diskPath && file_exists($diskPath)) {
            $ext = pathinfo($diskPath, PATHINFO_EXTENSION);
            $bytes = file_get_contents($diskPath);
            $base64 = 'data:image/' . $ext . ';base64,' . base64_encode($bytes);
            }
            @endphp

            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $sensor->name ?? $sensor->sensorName }}</td>
                <td>
                    @if($base64)
                    <img src="{{ $base64 }}" alt="{{ $sensor->name ?? $sensor->sensorName }}"
                        style="width:50px; height:50px; object-fit:contain;">
                    @else
                    <div style="
                                width:50px; height:50px;
                                background:#f0f0f0;
                                display:flex; align-items:center; justify-content:center;
                                font-size:10px; color:#666;
                            ">
                        No Image
                    </div>
                    @endif
                </td>
                <td>{{ $sensor->note ?? $sensor->sensorDescription }}</td>
                <td>{{ $roomName }}</td>
                <td>{{ number_format($sensor->price ?? $sensor->sensorPrice, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align:left; padding-right:10px;">
                    <table style="width:100%; border:none;">
                        <tr style="border:none;">
                            <td style="border:none; text-align:left; width:50%;"><strong>Total Sensors</strong></td>
                            <td style="border:none; text-align:right; width:50%;">
                                <strong>{{ count($sensorsData) }}</strong>
                            </td>
                        </tr>
                    </table>
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="5" style="text-align:left; padding-right:10px;">
                    <table style="width:100%; border:none;">
                        <tr style="border:none;">
                            <td style="border:none; text-align:left; width:50%;"><strong>Total</strong></td>
                            <td style="border:none; text-align:right; width:50%;">
                                <strong>${{ number_format($totalPrice, 2) }}</strong>
                            </td>
                        </tr>
                    </table>
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <p><b>Tempistica di fornitura</b></p>
    <p>
        <!-- (rest of your timing and terms text here) -->
    </p>

    <table style="width: 100%; margin-top: 30px; border:0 !important;">
        <tr>
            <td style="text-align: left; width: 50%; border:0 !important;">
                <img src="{{ public_path('uploads/estimations/logos.png') }}" alt="Logos" style="width:200px;">
            </td>
            <td style="text-align: right; width: 50%; border:0 !important;">
                <img src="{{ public_path('uploads/estimations/sign.png') }}" alt="Sign" style="width:200px;">
            </td>
        </tr>
    </table>

</body>

</html>