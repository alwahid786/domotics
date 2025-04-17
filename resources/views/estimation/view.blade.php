<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Stime') }}
            </h2>
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
            width: 500px;
            margin: 20px auto;
            display: block;
        }
    </style>

    <main class="p-2">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-10  bg-white shadow rounded" style="padding: 40px">
                <div class="floorplan">
                    <img src="{{ asset('uploads/estimations/MyDomoticsInvoice.png') }}" width="100%" height="auto" alt="Header">
                </div>
                <br>
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
                                <th>Sensor Name</th>
                                <th>Description</th>
                                <th>Room</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody id="estimationTableBody"></tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Total Price</td>
                                <td id="totalPrice"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

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
                            <img src="{{ asset('uploads/estimations/logos.png') }}" alt="Logos" style="width: 200px; height: auto;">
                        </td>
                        <td style="text-align: right; width: 50%; border:0 !important;">
                            <img src="{{ asset('uploads/estimations/sign.png') }}" alt="Sign" style="width: 200px; height: auto;">
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

</x-app-layout>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Global variables
    let apiData = null;       // Holds the full API response data.data
    let sensorDots = [];      // Sensor dots info for click detection

    document.addEventListener("DOMContentLoaded", function () {
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

    // Builds the table with sensor details and total price
    function buildEstimationTable(data) {
        const tableBody = document.getElementById("estimationTableBody");
        const totalPriceCell = document.getElementById("totalPrice");
        tableBody.innerHTML = "";  // Clear any existing rows

        if (!data.sensorsData || data.sensorsData.length === 0) {
            totalPriceCell.textContent = "$0.00";
            console.warn("No sensor data available.");
            return;
        }

        data.sensorsData.forEach((sensor, index) => {
            const tr = document.createElement("tr");

            // Serial Number
            const tdSrNo = document.createElement("td");
            tdSrNo.textContent = index + 1;
            tr.appendChild(tdSrNo);

            // Sensor Name
            const tdSensorName = document.createElement("td");
            tdSensorName.textContent = sensor.sensorName || "N/A";
            tr.appendChild(tdSensorName);

            // Sensor Description
            const tdSensorDescription = document.createElement("td");
            tdSensorDescription.textContent = sensor.sensorDescription || "N/A";
            tr.appendChild(tdSensorDescription);

            // Room Name (matched by roomId)
            const tdRoom = document.createElement("td");
            const room = data.roomsData.find(r => r.roomId === sensor.roomId);
            tdRoom.textContent = room ? room.roomName : "Unknown Room";
            tr.appendChild(tdRoom);

            // Price
            const tdPrice = document.createElement("td");
            tdPrice.textContent = "$" + (sensor.sensorPrice || "0.00");
            tr.appendChild(tdPrice);

            tableBody.appendChild(tr);
        });

        // Set total price from API
        totalPriceCell.textContent = "$" + (data.totalPrice || "0.00");
    }

    // Draw the floor plan image, rooms (as polygons) and sensor dots on the canvas
    function drawFloorPlan(data) {
        const canvas = document.getElementById("estimationCanvas");
        const ctx = canvas.getContext("2d");
        const floorImage = new Image();
        floorImage.crossOrigin = "Anonymous";
        floorImage.src = data.image;

        floorImage.onload = function () {
            // Set canvas dimensions
            canvas.width = floorImage.width;
            canvas.height = floorImage.height;
            // Draw the background image
            ctx.drawImage(floorImage, 0, 0);

            // Draw rooms as polygons
            if (data.roomsData && data.roomsData.length > 0) {
                data.roomsData.forEach(room => {
                    drawRoomPolygon(ctx, room.coordinates);
                });
            } else {
                console.warn("No room data available.");
            }

            // Draw sensor dots
            sensorDots = [];  // Reset sensor dots array
            if (data.sensorsData && data.sensorsData.length > 0) {
                    data.sensorsData.forEach(sensor => {
                    const x = parseFloat(sensor.sensorCoordinates.x);
                    const y = parseFloat(sensor.sensorCoordinates.y);

                    // Save dot info for click detection
                    sensorDots.push({
                        x: x,
                        y: y,
                        radius: 5,
                        sensorInfo: sensor
                    });

                    // Draw the sensor dot
                    ctx.beginPath();
                    ctx.arc(x, y, 5, 0, 2 * Math.PI);
                    ctx.fillStyle = "red";
                    ctx.fill();
                });
            } else {
                console.warn("No sensor data available.");
            }
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

        modalBody.innerHTML = `
            <p><strong>Sensor Name:</strong> ${sensor.sensorName}</p>
            <p><strong>Description:</strong> ${sensor.sensorDescription}</p>
            <p><strong>Room:</strong> ${roomName}</p>
            <p><strong>Price:</strong> $${sensor.sensorPrice}</p>
        `;

        const modalElement = document.getElementById("dotInfoModal");
        const sensorModal = new bootstrap.Modal(modalElement, {});
        sensorModal.show();
    }
</script>
