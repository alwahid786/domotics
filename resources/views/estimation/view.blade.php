<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Stime') }}
            </h2>
            <button onclick="printMainContent()" class="btn btn-primary d-print-none">Print</button>
        </div>
    </x-slot>

    <!-- Hidden input for estimate value -->
    <input type="hidden" id="estimate" value="{{ $estimate }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />

    <style>
        body {
            overflow-x: hidden !important;
        }

        canvas {
            border: 1px solid #ccc;
            max-width: 100%;
        }

        .floorplan {
            position: relative;
            width: 100%;
        }

        .floorplan img {
            width: 1200px;
            margin: 20px auto;
            display: block;
        }

        @media print {

            /* Hide everything */
            body * {
                visibility: hidden;
            }

            /* Show only printMain and its children */
            #printMain,
            #printMain * {
                visibility: visible;
            }

            /* Position section at top */
            #printMain {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
            }

            /* Hide print buttons */
            .d-print-none {
                display: none !important;
            }
        }
    </style>
    <main class="p-2" id="printMain">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-10  bg-white shadow rounded" style="padding: 40px">
                <div class="floorplan">
                    <div style="text-align: center; color: black !important;">
                        <img src="{{ asset('uploads/estimations/invoice-header.png') }}" width="100%" height="auto"
                            alt="Header">
                        <p style="margin-top: -15px">COVERTEC Design: Via D.Fontana 53/A-80198 - Napoli Tel-0810491219
                            Cap. Soc. 10.000,00 int. versato p.i. 08218571217 </p>
                    </div>
                </div>
                <br>
                <div style="display: flex; flex-direction: column; max-width: 86%; text-align: end; align-items: end;">
                    <div>
                        <h5> {{ $estimation->name ? 'Gentile ' . $estimation->name : $user_name }} </h5>
                    </div>
                    <div style="max-width: 16%;">
                        <h5> {{ $estimation->address ? 'Indirizzo: ' . $estimation->address : '' }}</h5>
                    </div>
                </div>
                <br>
                <br>
                <p>
                    Oggetto :Offerta relativa alla DOMOTIZZAZIONE della vostra unita' immobiliare Palazzo
                    Sanfelice<br><br>
                    Su Sua cortese richiesta abbiamo predisposto la presente offerta per la fornitura di materiali e
                    apparecchiature del nostro sistema domotico wireless con protocollo di comunicazione RF/Wi-Fi presso
                    la sua unita' immobilare evidenziata in oggetto.<br><br>
                    La vastissima quantità di connessioni possibili con dispositivi MydomoticS già pronti ci consente di
                    tenere sotto controllo:<br>
                    Luci della casa<br>
                    Tapparelle oscuranti<br>
                    Finestre motorizzate<br>
                    Tende interne motorizzate<br>
                    Tende per esterno motorizzate con sensore anti vento, anti-pioggia e crepuscolare abbinabile alle
                    scene.<br>
                    Ogni Tipo e ogni Marca di Climatizzatore Split o Canalizzabile.<br>
                    Controllo delle adduzioni di Acqua e Gas con Possibilità di apertura e chiusura delle erogazioni
                    anche da remoto e chiaramente programmabili in scenari.<br>
                    Irrigazione Giardino e Balcone.<br>
                    Sensori di Fuga di Gas. Questo dispositivo e' spostabile e installabile in ogni ambiente e
                    totalmente libero da connessioni elettriche essendo alimentato a Batteria.<br>
                    Sensori di Fumo Questo dispositivo e' spostabile e installabile in ogni ambiente e totalmente libero
                    da connessioni elettriche essendo alimentato a Batteria.<br>
                    Sensori di Allagamento. Questo dispositivo e' spostabile e installabile in ogni ambiente e
                    totalmente libero da connessioni elettriche essendo alimentato a Batteria.<br>
                    Qualsiasi TV sia esso del tipo Smart o privo di qualsiasi modulo intelligente od interfaccia<br>
                    Qualsiasi dispositivo HI-FI Audio sia esso Smart o senza interfacce intelligenti<br>
                    Qualsiasi Decoder TV per la gestione della visione via Satellite<br>
                    Qualsiasi Videoproiettore<br>
                    Inserimento di dispositivi RGB per cromoterapia o effetti scenici in ambienti particolari come bar ,
                    discoteche…. o esterni di edifici che possono essere colorati in modi diversi .I nostri Controller
                    WIFI RGB possono gestire sia Barre Led RGB che le lampade.<br>
                    Dimmer per regolazioni luci sia esse in led che ad incandescenza.<br>
                    Bottone SOS per Docce o altri ambienti pericolosi. Questo dispositivo e' spostabile e installabile
                    in ogni ambiente e totalmente libero da connessioni elettriche essendo alimentato a Batteria.
                </p>

                <p><b>Qui di seguito le elenchiamo i dispositivi da lei richiesti</b></p>

                <p><b>Distinta della fornitura suddivisa per ambiente di installazione</b></p>
                <br>
                <!-- Canvas Container -->
                <div class="mb-4 text-center">
                    <canvas id="estimationCanvas"></canvas>
                </div>

                <!-- Table Container -->
                <div class="table-responsive">
                    <table class="table table-bordered" id="estimationTable">
                        <thead>
                            <tr>
                                <th>Sr. No</th>
                                <th>Room</th>
                                <th>Image</th>
                                <th>Code</th>
                                <th>Sensor Name</th>
                                <th>Sensor</th>
                                <th>Installation Notes</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody id="estimationTableBody"></tbody>
                        <tfoot>
                            <tr id="totalCountRow">
                                <td colspan="7" class="text-end">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div><strong>Total Sensors:</strong></div>
                                        <div id="totalCount">0</div>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                            <tr id="totalRow">
                                <td colspan="7" class="text-end">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div><strong>Total Price:</strong></div>
                                        <div>$<span id="totalPrice">0</span></div>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Sensor Summary Table -->
                <div class="mt-4 table-responsive">
                    <h3 class="text-lg font-semibold mb-2">Sensor Summary</h3>
                    <table id="sensorSummaryTable" class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-2">Sensor Name</th>
                                <th class="border p-2">Quantity</th>
                                <th class="border p-2">Unit Price</th>
                                <th class="border p-2">Total Price</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr class="bg-gray-50">
                                <td colspan="2" class="border p-2 text-right font-bold">Total Sensors:</td>
                                <td colspan="2" class="border p-2 font-bold" id="summaryTotalSensors">0</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td colspan="2" class="border p-2 text-right font-bold">Total Price:</td>
                                <td colspan="2" class="border p-2 font-bold" id="summaryTotalPrice">$0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <p><b>Tempistica di fornitura</b></p>

                <p>
                    <b>Tempistica arrivo fornitura e consegna in cantiere</b><br>
                    • 20 gg lavorativi a partire dalla ricezione del pagamento relativo al primo acconto e Vostra
                    conferma di ordine mediante restituzione del presente contratto firmato per accettazione - Arrivo
                    fornitura presso sede di MydomoticS
                </p>

                <p>
                    <b>Consegna fornitura in cantiere</b><br>
                    • 2 gg dalla avviso di consegna fornitura al cliente (contestuale all'arrivo della fornitura presso
                    sede MyDomoticS) e contestuale ricezione pagamento relativo al II° acconto
                </p>

                <p>
                    <b>Condizioni di pagamento</b><br>
                    • Primo Acconto pari al 40% oltre Iva alla conferma di ordine per avvio produzione fornitura<br>
                    • Saldo pari al 60% oltre Iva alla consegna degli stessi prodotti che avverrà presso Vs
                    cantiere/ufficio entro 48 ore dal ricevimento del pagamento
                </p>

                <p>
                    <b>Garanzie / Assistenza Post Vendita</b><br>
                    • Garanzia su ciascun componente oggetto di fornitura pari a 2 anni per completa sostituzione in
                    caso di malfunzionamento per causa imputabile al fornitore<br>
                    • Assistenza da remoto nelle 24 ore lavorative successive alla vs chiamata<br>
                    • Assistenza Post vendita mediante intervento dedicato con presenza di ns operatore presso il vs
                    immobile nelle 48 ore lavorative successive alla vs chiamata di pronto intervento al ns N. Verde
                    dedicato
                </p>

                <p>In attesa di vs. riscontro inviamo distinti saluti</p>


                <table style="width: 100%; margin-top: 30px;border:0 !important;">
                    <tr>
                        <td style="text-align: left; width: 50%; border:0 !important;">
                            <img src="{{ asset('uploads/estimations/logos.png') }}" alt="Logos"
                                style="width: 200px; height: auto;">
                        </td>
                        <td style="text-align: right; width: 50%; border:0 !important;">
                            <img src="{{ asset('uploads/estimations/sign.png') }}" alt="Sign"
                                style="width: 200px; height: auto;">
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </main>

    <!-- Bootstrap Modal for Sensor Details -->
    <div class="modal fade" id="dotInfoModal" tabindex="-1" aria-labelledby="dotInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dotInfoModalLabel">Sensor Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="dotInfoContent">
                    <!-- Sensor info will be loaded here -->
                </div>
            </div>
        </div>
    </div>



    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>



    <script>
        // Global variables
        let apiData = null; // Holds the full API response data.data
        let sensorDots = []; // Sensor dots info for click detection

        document.addEventListener("DOMContentLoaded", function() {
            fetchEstimations();
        });

        function fetchEstimations() {
            const estimateInput = document.getElementById("estimate");
            const estimate = estimateInput ? estimateInput.value : "";
            const url = "{{ route('estimations.fetch') }}?estimate=" + encodeURIComponent(estimate);
            fetch(url)
                .then(response => response.json())
                .then(responseData => {
                    // Update: our required data is nested in responseData.data
                    if (!responseData.data) {
                        console.error("API response missing 'data' property.");
                        return;
                    }
                    apiData = responseData.data;
                    // Check for image existence
                    if (!apiData.image) {
                        console.error("No image URL provided in API data.");
                        return;
                    }
                    buildEstimationTable(apiData);
                    drawFloorPlan(apiData);
                })
                .catch(error => {
                    console.error("Error fetching estimations:", error);
                });
        }

        function buildEstimationTable(data) {
            const tableBody = document.getElementById("estimationTableBody");
            const totalPriceCell = document.getElementById("totalPrice");
            const totalCountCell = document.getElementById("totalCount");
            tableBody.innerHTML = ""; // Clear any existing rows

            if (!data.sensorsData || data.sensorsData.length === 0) {
                totalPriceCell.textContent = "$0.00";
                totalCountCell.textContent = "0";
                console.warn("No sensor data available.");
                return;
            }

            // Group sensors by sensorName + roomId
            const sensorGroups = {};
            data.sensorsData.forEach(sensor => {
                const sensorName = sensor.sensorName || sensor.name || "N/A";
                const key = sensorName + "::" + sensor.roomId; // ensure grouping by room too
                const matchedRoom = data.roomsData.find(room => room.roomId === sensor.roomId);
                const roomName = matchedRoom ? matchedRoom.roomName : "N/A";

                if (!sensorGroups[key]) {
                    sensorGroups[key] = {
                        name: sensorName,
                        productName: sensor.productName,
                        quantity: 0,
                        unitPrice: parseFloat(sensor.sensorPrice || sensor.price || 0),
                        total: 0,
                        room: roomName,
                        description: sensor.sensorDescription || sensor.note || "",
                        code: sensor.productCode || "",
                        image: sensor.sensorImage?.image && !sensor.sensorImage.image.startsWith('http') ?
                            "{{ asset('storage') }}/" + sensor.sensorImage.image :
                            sensor.sensorImage?.image || (sensor.image && !sensor.image.startsWith('http') ?
                                "{{ asset('storage') }}/" + sensor.image :
                                sensor.image || "")
                    };
                }

                sensorGroups[key].quantity++;
                sensorGroups[key].total = sensorGroups[key].quantity * sensorGroups[key].unitPrice;
            });

            // Add rows to table
            let rowIndex = 1;
            Object.values(sensorGroups).forEach(sensor => {

                const tr = document.createElement("tr");

                // Serial Number
                const tdSrNo = document.createElement("td");
                tdSrNo.textContent = rowIndex++;
                tr.appendChild(tdSrNo);

                // Room Name
                const tdRoom = document.createElement("td");
                tdRoom.textContent = sensor.room;
                tr.appendChild(tdRoom);

                // Image
                const tdImage = document.createElement("td");
                const imageHtml = sensor.image

                    ?
                    `<img src="${sensor.image}" alt="${sensor.name}" style="width:50px; height:50px; object-fit:contain; border-radius:4px;"
            onerror="this.onerror=null; this.src='https://via.placeholder.com/50?text=No+Image';">` :
                    `<div
            style="width:50px; height:50px; display:flex; align-items:center; justify-content:center; background-color:#f0f0f0; border-radius:4px; font-size:10px; color:#666;">
            No Image</div>`;
                tdImage.innerHTML = imageHtml;
                tr.appendChild(tdImage);

                // Code
                const tdCode = document.createElement("td");
                tdCode.textContent = sensor.code;
                tr.appendChild(tdCode);

                // Name
                const tdName = document.createElement("td");
                tdName.textContent = sensor.name;
                tr.appendChild(tdName);

                // Sensor Name (again if needed)
                const tdSensor = document.createElement("td");
                tdSensor.textContent = sensor.productName;
                tr.appendChild(tdSensor);

                // Description / Notes
                const tdNotes = document.createElement("td");
                tdNotes.textContent = sensor.description;
                tr.appendChild(tdNotes);

                // Price
                const tdPrice = document.createElement("td");
                tdPrice.textContent = "$" + Number(sensor.unitPrice).toFixed(2);
                tr.appendChild(tdPrice);

                tableBody.appendChild(tr);
            });

            // Totals
            const totalPrice = Object.values(sensorGroups).reduce((sum, sensor) => sum + Number(sensor.total), 0);
            const totalCount = Object.values(sensorGroups).reduce((sum, sensor) => sum + sensor.quantity, 0);

            totalPriceCell.textContent = "$" + totalPrice.toFixed(2);
            totalCountCell.textContent = totalCount;

            updateSensorSummary(sensorGroups);
        }

       function updateSensorSummary(sensorGroups) {
        const summaryTableBody = document.querySelector('#sensorSummaryTable tbody');
        summaryTableBody.innerHTML = '';
        
        // Aggregate by productName instead of sensor.name
        const aggregatedSensors = {};
        Object.values(sensorGroups).forEach(sensor => {
        const key = sensor.productName; // Group by productName
        if (!aggregatedSensors[key]) {
        aggregatedSensors[key] = {
        productName: sensor.productName,
        quantity: 0,
        unitPrice: sensor.unitPrice,
        total: 0
        };
        }
        aggregatedSensors[key].quantity += sensor.quantity;
        aggregatedSensors[key].total = aggregatedSensors[key].quantity * aggregatedSensors[key].unitPrice;
        });
        
        // Render summary rows
        Object.values(aggregatedSensors).forEach(sensor => {
        const row = document.createElement('tr');
        row.innerHTML = `
        <td class="border p-2">${sensor.productName}</td>
        <td class="border p-2">${sensor.quantity}</td>
        <td class="border p-2">$${sensor.unitPrice.toFixed(2)}</td>
        <td class="border p-2">$${sensor.total.toFixed(2)}</td>
        `;
        summaryTableBody.appendChild(row);
        });
        
        // Update totals
        const totalSensors = Object.values(aggregatedSensors).reduce((sum, sensor) => sum + sensor.quantity, 0);
        const totalPrice = Object.values(aggregatedSensors).reduce((sum, sensor) => sum + sensor.total, 0);
        
        document.getElementById('summaryTotalSensors').textContent = totalSensors;
        document.getElementById('summaryTotalPrice').textContent = `$${totalPrice.toFixed(2)}`;
        }

        // Draw the floor plan image, rooms (as polygons) and sensor dots on the canvas
        function drawFloorPlan(data) {
            const canvas = document.getElementById("estimationCanvas");
            const ctx = canvas.getContext("2d");
            const floorImage = new Image();
            floorImage.crossOrigin = "Anonymous";
            floorImage.src = data.image;

            floorImage.onload = function() {
                // Set canvas dimensions
                canvas.width = floorImage.width;
                canvas.height = floorImage.height;
                // Draw the background image
                ctx.drawImage(floorImage, 0, 0);

                // // Draw rooms as polygons
                // if (data.roomsData && data.roomsData.length > 0) {
                //     data.roomsData.forEach(room => {
                //         drawRoomPolygon(ctx, room.coordinates);
                //     });
                // } else {
                //     console.warn("No room data available.");
                // }

                // // Draw sensor dots
                // sensorDots = []; // Reset sensor dots array
                // if (data.sensorsData && data.sensorsData.length > 0) {
                //     data.sensorsData.forEach(sensor => {
                //         const x = parseFloat(sensor.sensorCoordinates.x);
                //         const y = parseFloat(sensor.sensorCoordinates.y);

                //         // Save dot info for click detection
                //         sensorDots.push({
                //             x: x,
                //             y: y,
                //             radius: 5,
                //             sensorInfo: sensor
                //         });

                //         // Draw the sensor dot
                //         ctx.beginPath();
                //         ctx.arc(x, y, 5, 0, 2 * Math.PI);
                //         ctx.fillStyle = "red";
                //         ctx.fill();
                //     });
                // } else {
                //     console.warn("No sensor data available.");
                // }
            };

            floorImage.onerror = function(err) {
                console.error("Error loading floor image:", err);
            };

            // Listen for canvas clicks to detect sensor dot interaction
            canvas.addEventListener("click", onCanvasClick);
        }

        // Draw a polygon for a room based on provided coordinates
        function drawRoomPolygon(ctx, coordinates) {
            if (!coordinates || coordinates.length === 0) {
                console.warn("No coordinates to draw room polygon.");
                return;
            }

            ctx.beginPath();
            coordinates.forEach((coord, idx) => {
                const x = parseFloat(coord.x);
                const y = parseFloat(coord.y);
                if (idx === 0) {
                    ctx.moveTo(x, y);
                } else {
                    ctx.lineTo(x, y);
                }
            });
            ctx.closePath();

            ctx.fillStyle = "rgba(0, 123, 255, 0.1)"; // semi-transparent blue fill
            ctx.fill();
            ctx.strokeStyle = "blue";
            ctx.lineWidth = 2;
            ctx.stroke();
        }

        // Handle canvas click events to detect if a sensor dot is clicked
        function onCanvasClick(e) {
            const canvas = e.target;
            const rect = canvas.getBoundingClientRect();
            const mouseX = e.clientX - rect.left;
            const mouseY = e.clientY - rect.top;

            sensorDots.forEach(dot => {
                const dx = mouseX - dot.x;
                const dy = mouseY - dot.y;
                const distance = Math.sqrt(dx * dx + dy * dy);
                if (distance <= dot.radius) {
                    showSensorModal(dot.sensorInfo);
                }
            });
        }

        // Show sensor details in the Bootstrap modal
        function showSensorModal(sensor) {
            const modalBody = document.getElementById("dotInfoContent");

            // Find the room name based on roomId
            let roomName = "Unknown Room";
            if (apiData && apiData.roomsData) {
                const room = apiData.roomsData.find(r => r.roomId === sensor.roomId);
                if (room) {
                    roomName = room.roomName;
                }
            }

            // Handle different possible image property structures
            let imagePath = null;
            if (sensor.image) {
                imagePath = sensor.image;
            } else if (sensor.sensorImage) {
                if (typeof sensor.sensorImage === 'object' && sensor.sensorImage.image) {
                    // Handle nested object: {sensorImage: {image: 'path/to/image.jpg'}}
                    imagePath = sensor.sensorImage.image;
                } else {
                    // Direct string: {sensorImage: 'path/to/image.jpg'}
                    imagePath = sensor.sensorImage;
                }
            }

            // Create proper image URL
            let imageUrl = imagePath;
            if (imagePath && !imagePath.startsWith('http')) {
                imageUrl = "{{ asset('storage') }}/" + imagePath;
            }

            // Create image HTML
            let imageHtml = '';
            if (imageUrl) {
                imageHtml =
                    `<img src="${imageUrl}" alt="${sensor.sensorName || sensor.name}" style="width:100%; max-width:200px; height:auto; object-fit:contain; border-radius:4px; margin-bottom:15px;" onerror="this.onerror=null; this.src='https://via.placeholder.com/200?text=No+Image';">`;
            } else {
                imageHtml =
                    `<div style="width:100%; max-width:200px; height:150px; display:flex; align-items:center; justify-content:center; background-color:#f0f0f0; border-radius:4px; margin-bottom:15px; font-size:14px; color:#666;">No Image</div>`;
            }

            modalBody.innerHTML = `
            ${imageHtml}
            <p><strong>Sensor Name:</strong> ${sensor.sensorName || sensor.name}</p>
            <p><strong>Installation Notes:</strong> ${sensor.sensorDescription || sensor.note}</p>
            <p><strong>Room:</strong> ${roomName}</p>
            <p><strong>Price:</strong> $${sensor.sensorPrice || sensor.price}</p>
        `;

            const modalElement = document.getElementById("dotInfoModal");
            const sensorModal = new bootstrap.Modal(modalElement, {});
            sensorModal.show();
        }
    </script>
    {{-- print screen --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            fetchEstimations();
        });

        function printMainContent() {
            // 1. Clone the printMain node
            const original = document.getElementById('printMain');
            const clone = original.cloneNode(true);

            // 2. Replace canvas with an <img> snapshot
            const canvasEl = document.getElementById('estimationCanvas');
            const cloneCanvasContainer = clone.querySelector('#estimationCanvas');
            if (canvasEl && cloneCanvasContainer) {
                const dataURL = canvasEl.toDataURL();
                const img = document.createElement('img');
                img.src = dataURL;
                img.style.maxWidth = '100%';
                img.style.height = 'auto';
                cloneCanvasContainer.parentNode.replaceChild(img, cloneCanvasContainer);
            }

            // 3. Open a print window
            const printWindow = window.open('', '_blank', 'width=800,height=600');
            printWindow.document.write(`
            <html>
            <head>
              <title>Print</title>
              <link rel="stylesheet" href="/css/app.css">
            </head>
            <body>${clone.innerHTML}</body>
            </html>
        `);
            printWindow.document.close();

            // 4. Print and close
            printWindow.onload = () => {
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            };
        }
    </script>
</x-app-layout>