<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-800 leading-tight">
            {{ __('Nuovo Preventivo') }}
        </h2>
    </x-slot>

    <!-- Custom CSS for modal and styling -->
    <style>
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10000;
        }

        .modal-content {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 10px 20px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.3s ease;
            position: relative;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            font-size: 20px;
            margin-bottom: 15px;
            font-weight: bold;
            color: #333;
        }

        .close-modal {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 24px;
            color: #888;
            cursor: pointer;
            border: none;
            background: transparent;
            outline: none;
        }

        .modal-body {
            margin-bottom: 20px;
        }

        .modal-body label {
            font-weight: 500;
            margin-bottom: 5px;
            display: block;
            color: #555;
        }

        .modal-body input,
        .modal-body select {
            width: 100%;
            padding: 8px 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .modal-footer {
            text-align: right;
        }

        .modal-footer button {
            padding: 8px 16px;
            font-size: 14px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            margin-left: 8px;
        }

        .modal-footer .btn-secondary {
            background-color: #ccc;
            color: #333;
        }

        .modal-footer .btn-primary {
            background-color: #007bff;
            color: #fff;
        }

        /* Table styling */
        #sensorTable {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }

        #sensorTable th,
        #sensorTable td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        /* Loader style for Generate PDF Button */
        .loading {
            opacity: 0.7;
            pointer-events: none;
            position: relative;
        }

        .loading:after {
            content: "Please wait...";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border-radius: 4px;
        }

        /* Hide mode buttons initially */
        .mode-btn {
            display: none;
        }

        /* Active mode button styling */
        .mode-active {
            background-color: #007bff !important;
        }
    </style>

    <!-- Mode Switching Buttons & Other UI -->
    <div style="padding-top: 30px; padding-bottom: 30px;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white shadow p-2 rounded mt-4">
            <!-- Image Upload -->
            <div class="mb-2 mt-4 grid grid-cols-1 lg:grid-cols-2 gap-4">
                <input type="text" class="form-control border p-2 floor-name w-full" placeholder="Nome della piantina"
                    required />
                <input type="text" class="form-control border p-2 w-full" id="forUserName"
                    placeholder="Nome dell'utente" />
                <input type="text" class="form-control border p-2 w-full" id="forUserAddress"
                    placeholder="Indirizzo dell'utente" />
                <input type="hidden" value="{{ $roleId }}" id="RoleId" />
                @if($roleId == 1 || $roleId == 2)
                <select class="form-control border p-2 w-full" name="user_id" id="user_id">
                    <option value="" selected>Select User</option>
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</option>
                    @endforeach
                </select>
                @endif

                <input type="file" id="imageUpload" accept="image/*" class="form-control border p-2 w-full">
            </div>
            <!-- Mode buttons: initially hidden, will be shown after picture upload -->
            <div id="modeButtons" class="my-4 flex items-center gap-4">
                <button id="floorModeBtn" class="mode-btn bg-dark rounded-md text-white font-medium px-4 py-2">Floor
                    Mode</button>
                <button id="deviceModeBtn" class="mode-btn bg-dark rounded-md text-white font-medium px-4 py-2">Device
                    Mode</button>
                <button id="deleteModeBtn" class="mode-btn bg-dark rounded-md text-white font-medium px-4 py-2">Delete
                    Mode</button>
            </div>

            <!-- Image Crop Area (hidden until image is selected) -->
            <div id="cropContainer" class="mb-3" style="display: none;">
                <img id="imageToCrop" src="" alt="To Crop" style="max-width: 100%;">
                <button id="cropButton" class="btn btn-primary mt-2">Crop Image</button>
            </div>

            <!-- Container for final image (canvas) & SVG overlay -->
            <div id="canvasContainer" style="position: relative; display: inline-block;">
                <img id="finalImage" src="" alt="Final Image" style="max-width: 100%; display: none;">
                <!-- SVG overlay with two groups: finishedPolygons and tempDrawing -->
                <svg id="svgOverlay"
                    style="position:absolute; top:0; left:0; width:100%; height:100%; pointer-events:none;">
                    <g id="finishedPolygons"></g>
                    <g id="tempDrawing"></g>
                </svg>
                <!-- Dots for sensors are appended to canvasContainer -->
            </div>

            <!-- Sensor List Table -->
            <div id="sensorListContainer" style="display: none;">
                <<div class="mt-4 table-responsive">
                    <table id="sensorTable">
                        <thead>
                            <tr>
                                <th>Sr. No</th>
                                <th>Room</th>
                                <th>Name</th>
                                <th>Image</th>
                                <th>Installation Notes</th>
                                <th>Code</th>
                                <th>Sensor</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr id="totalCountRow">
                                <td colspan="8" class="text-end">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div><strong>Total Sensors:</strong></div>
                                        <div id="totalCount">0</div>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                            <tr id="totalRow">
                                <td colspan="8" class="text-end">
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

            <!-- New Sensor Summary Table -->
            <<div class="mt-4 table-responsive">
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
    </div>

    <!-- Generate PDF Button: initially hidden -->
    <div id="pdfBtnContainer" class="mt-3 mb-2" style="display: none;">
        <button id="generatePDF" class="btn btn-success">Generate PDF Estimation</button>
    </div>
    </div>
    </div>

    <!-- Sensor (Device) Modal -->
    <div id="dotModal" class="modal-overlay">
        <div class="modal-content">
            <span class="close-modal" id="closeModal">&times;</span>
            <div class="modal-header">Sensor Information</div>
            <div class="modal-body">
                <form id="dotForm">
                    <div class="mb-3">
                        <label for="dotName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="dotName" required>
                    </div>
                    <div class="mb-3">
                        <label for="sensorSelect" class="form-label">Sensor</label>
                        <select class="form-select" id="sensorSelect">
                            <option value="">Select Sensor</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="dotNote" class="form-label">Installation Notes</label>
                        <textarea id="dotNote" cols="2" rows="2" class="form-control" required></textarea>
                    </div>
                    <!-- Hidden fields to store coordinates and room id -->
                    <input type="hidden" id="dotX">
                    <input type="hidden" id="dotY">
                    <input type="hidden" id="dotRoomId">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="cancelModal" class="btn btn-secondary">Cancel</button>
                <button type="button" id="saveDot" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>

    <!-- New Polygon (Room) Modal -->
    <div id="polygonModal" class="modal-overlay">
        <div class="modal-content">
            <span class="close-modal" id="closePolygonModal">&times;</span>
            <div class="modal-header">Room Information</div>
            <div class="modal-body">
                <form id="polygonForm">
                    <div class="mb-3">
                        <label for="polygonName" class="form-label">Room Name</label>
                        <select class="form-select" id="polygonName" required>
                            <option value="">Select Room</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="cancelPolygonModal" class="btn btn-secondary">Cancel</button>
                <button type="button" id="savePolygon" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Cropper.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <!-- jsPDF and AutoTable -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const {
            jsPDF
        } = window.jspdf;
        // Global Variables
        let cropper;
        const imageUpload = document.getElementById('imageUpload');
        const imageToCrop = document.getElementById('imageToCrop');
        const cropContainer = document.getElementById('cropContainer');
        const cropButton = document.getElementById('cropButton');
        const finalImage = document.getElementById('finalImage');
        const canvasContainer = document.getElementById('canvasContainer');
        const svgOverlay = document.getElementById('svgOverlay');
        const finishedGroup = document.getElementById("finishedPolygons");
        const tempGroup = document.getElementById("tempDrawing");
        const dotModal = document.getElementById('dotModal');
        const closeModal = document.getElementById('closeModal');
        const cancelModal = document.getElementById('cancelModal');
        const sensorTableBody = document.querySelector('#sensorTable tbody');
        const generatePDFBtn = document.getElementById('generatePDF');
        const sensorListContainer = document.getElementById('sensorListContainer');
        const pdfBtnContainer = document.getElementById('pdfBtnContainer');
        const sensorSelectTag = document.getElementById('sensorSelect');
        const floorNameInput = document.querySelector('.floor-name');
        const user_id = document.querySelector('#user_id');
        const forUserName = document.querySelector('#forUserName');
        const forUserAddress = document.querySelector('#forUserAddress');
        // Mode buttons (initially hidden; will be shown after picture upload)
        const floorModeBtn = document.getElementById('floorModeBtn');
        const deviceModeBtn = document.getElementById('deviceModeBtn');
        const deleteModeBtn = document.getElementById('deleteModeBtn');
        const modeButtons = document.getElementById('modeButtons');
        // Polygon Modal elements
        const polygonModal = document.getElementById('polygonModal');
        const closePolygonModal = document.getElementById('closePolygonModal');
        const cancelPolygonModal = document.getElementById('cancelPolygonModal');
        const savePolygonBtn = document.getElementById('savePolygon');
        const polygonNameInput = document.getElementById('polygonName');
        // Data storage
        let sensorPrices = {};
        let productsData = []; // Array to hold sensor data
        let totalPrice = 0;
        let currentMode = 'floor'; // 'floor' or 'device'
        let currentPolygon = null; // { id, vertices: [ {x, y} ], name }
        const polygons = []; // Completed room polygons
        let dotCount = 0;
        let nextSensorNumber = 1; // Global counter for sensor numbering
        let temporaryDotId = null;

        // Helper functions for responsive coordinates
        function toRelativeCoords(absX, absY) {
            const imgWidth = finalImage.width;
            const imgHeight = finalImage.height;
            return {
                x: (absX / imgWidth) * 100,
                y: (absY / imgHeight) * 100
            };
        }

        function toAbsoluteCoords(relX, relY) {
            const imgWidth = finalImage.width;
            const imgHeight = finalImage.height;
            return {
                x: (relX * imgWidth) / 100,
                y: (relY * imgHeight) / 100
            };
        }

        // UI Mode Switching
        function setMode(mode) {
            currentMode = mode;

            floorModeBtn.classList.remove('mode-active');
            deviceModeBtn.classList.remove('mode-active');
            deleteModeBtn.classList.remove('mode-active');

            if (mode === 'floor') {
                floorModeBtn.classList.add('mode-active');
            } else if (mode === 'device') {
                deviceModeBtn.classList.add('mode-active');
            } else if (mode === 'delete') {
                deleteModeBtn.classList.add('mode-active');
            }
        }
        // Default is Floor Mode
        setMode('floor');
        floorModeBtn.addEventListener('click', () => setMode('floor'));
        deviceModeBtn.addEventListener('click', () => setMode('device'));
        deleteModeBtn.addEventListener('click', () => setMode('delete'));
        // Fetch sensor data
        const fetchSensors = async () => {
            try {
                const res = await fetch("{{ route('estimations.sensor') }}");
                if (!res.ok) {
                    throw new Error("Error while fetching sensors");
                }
                const data = await res.json();
                if (data?.sensors?.length > 0) {
                    sensorPrices = {};
                    data.sensors.forEach((sensor) => {
                        const {
                            name: sensorName,
                            price,
                            code,
                            id,
                            image = '', // Default to empty string if image doesn't exist
                        } = sensor;

                        // Create proper image URL from the relative path
                        const imageUrl = image ? "{{ asset('storage') }}/" + image : '';

                        sensorPrices[sensorName] = price;
                        // Store sensor data including image
                        sensorSelectTag.innerHTML +=
                            `<option data-id="${id}" data-code="${code}" data-image="${imageUrl}" value="${sensorName}">${sensorName}</option>`;
                    });
                }
            } catch (error) {
                console.error("Error while fetching sensors", error.message);
            }
        }
        fetchSensors();

        const fetchRooms = async () => {
            try {
                const result = await fetch("{{ route('estimations.room') }}");
                if (!result.ok) {
                    throw new Error("Error while fetching rooms");
                }
                const data = await result.json();
                const rooms = data.rooms.map((room) => room.name);

                const select = document.getElementById('polygonName');
                rooms.forEach((roomName) => {
                    const option = document.createElement('option');
                    option.value = roomName;
                    option.textContent = roomName;
                    select.appendChild(option);
                });
            } catch (error) {
                console.error("Error while fetching rooms", error.message);
            }
        };
        fetchRooms();

        // Close handlers for sensor modal
        function hideDotModal({ keepDot = false}) 
        {
            if (!keepDot && temporaryDotId) {
                const tempDot = document.getElementById(temporaryDotId);
                if (tempDot) {
                    tempDot.remove();
                }
            }

            temporaryDotId = null;
            dotModal.style.display = 'none';
            document.getElementById('dotForm').reset();
        }
        closeModal.addEventListener('click', hideDotModal);
        cancelModal.addEventListener('click', hideDotModal);
        // Close Polygon Modal
        function hidePolygonModal() {
            polygonModal.style.display = 'none';
            document.getElementById('polygonForm').reset();
            clearTemporaryPolygon();
            currentPolygon = null;
        }
        closePolygonModal.addEventListener('click', hidePolygonModal);
        cancelPolygonModal.addEventListener('click', hidePolygonModal);
        // Image upload & Cropper initialization
        imageUpload.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imageToCrop.onload = function() {
                        if (file.size > 2 * 1024 * 1024) {
                            alert('Image must be less than 2MB.');
                            event.target.value = '';
                            return;
                        }
                        const naturalWidth = imageToCrop.naturalWidth;
                        const naturalHeight = imageToCrop.naturalHeight;
                        const dynamicAspectRatio = naturalWidth / naturalHeight;

                        cropContainer.style.display = 'block';

                        if (cropper) {
                            cropper.destroy();
                        }

                        cropper = new Cropper(imageToCrop, {
                            aspectRatio: dynamicAspectRatio,
                            viewMode: 1
                        });
                    };

                    imageToCrop.src = e.target.result;
                };

                reader.readAsDataURL(file);
            }
        });
        // Crop image and show final image; also display mode buttons now.
        cropButton.addEventListener('click', function() {
            if (cropper) {
                cropper.getCroppedCanvas().toBlob(function(blob) {
                    finalImage.src = URL.createObjectURL(blob);
                    finalImage.style.display = 'block';
                    finalImage.blob = blob;
                    cropContainer.style.display = 'none';
                    sensorListContainer.style.display = 'block';
                    pdfBtnContainer.style.display = 'block';
                    // Show mode buttons now:
                    floorModeBtn.style.display = 'block';
                    deviceModeBtn.style.display = 'block';
                    deleteModeBtn.style.display = 'block';
                    modeButtons.style.display = 'flex';
                }, 'image/png');
            }
        });
        // Helper: Calculate distance between two points
        function distance(p1, p2) {
            return Math.sqrt((p1.x - p2.x) ** 2 + (p1.y - p2.y) ** 2);
        }
        // Helper: Check if point is inside polygon (ray-casting)
        function pointInPolygon(point, vertices) {
            let inside = false;
            for (let i = 0, j = vertices.length - 1; i < vertices.length; j = i++) {
                const xi = vertices[i].x,
                    yi = vertices[i].y;
                const xj = vertices[j].x,
                    yj = vertices[j].y;
                const intersect = ((yi > point.y) !== (yj > point.y)) &&
                    (point.x < (xj - xi) * (point.y - yi) / (yj - yi + 0.0001) + xi);
                if (intersect) inside = !inside;
            }
            return inside;
        }
        // Draw temporary polygon in Floor Mode (only clear temp group)
        function drawTemporaryPolygon() {
            tempGroup.innerHTML = ""; // clear temporary drawings
            if (!currentPolygon) return;

            // Convert relative coordinates to absolute for drawing
            const vertices = currentPolygon.vertices.map(v => toAbsoluteCoords(v.x, v.y));
            const pointsStr = vertices.map(v => `${v.x},${v.y}`).join(" ");

            // Draw dashed polyline
            const polyline = document.createElementNS("http://www.w3.org/2000/svg", "polyline");
            polyline.setAttribute("points", pointsStr);
            polyline.setAttribute("fill", "none");
            polyline.setAttribute("stroke", "blue");
            polyline.setAttribute("stroke-dasharray", "4");
            polyline.setAttribute("stroke-width", "2");
            tempGroup.appendChild(polyline);
            // Draw vertices as circles
            vertices.forEach(v => {
                const circle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
                circle.setAttribute("cx", v.x);
                circle.setAttribute("cy", v.y);
                circle.setAttribute("r", "3");
                circle.setAttribute("fill", "blue");
                tempGroup.appendChild(circle);
            });
        }
        // Helper functions for polygon operations
        function calculatePolygonArea(vertices) {
            let area = 0;
            for (let i = 0; i < vertices.length; i++) {
                const j = (i + 1) % vertices.length;
                area += vertices[i].x * vertices[j].y;
                area -= vertices[j].x * vertices[i].y;
            }
            return Math.abs(area / 2);
        }

        function lineIntersectsLine(line1Start, line1End, line2Start, line2End) {
            const denominator = ((line2End.y - line2Start.y) * (line1End.x - line1Start.x)) -
                ((line2End.x - line2Start.x) * (line1End.y - line1Start.y));

            if (denominator === 0) return false;

            const ua = (((line2End.x - line2Start.x) * (line1Start.y - line2Start.y)) -
                ((line2End.y - line2Start.y) * (line1Start.x - line2Start.x))) / denominator;
            const ub = (((line1End.x - line1Start.x) * (line1Start.y - line2Start.y)) -
                ((line1End.y - line1Start.y) * (line1Start.x - line2Start.x))) / denominator;

            return ua >= 0 && ua <= 1 && ub >= 0 && ub <= 1;
        }

        function polygonsIntersect(poly1, poly2) {
            // Check if any line segment from poly1 intersects with any line segment from poly2
            for (let i = 0; i < poly1.length; i++) {
                const line1Start = poly1[i];
                const line1End = poly1[(i + 1) % poly1.length];

                for (let j = 0; j < poly2.length; j++) {
                    const line2Start = poly2[j];
                    const line2End = poly2[(j + 1) % poly2.length];

                    if (lineIntersectsLine(line1Start, line1End, line2Start, line2End)) {
                        return true;
                    }
                }
            }

            // Check if one polygon is completely inside the other
            if (pointInPolygon(poly1[0], poly2) || pointInPolygon(poly2[0], poly1)) {
                return true;
            }

            return false;
        }

        function checkRoomOverlap(newPolygon) {
            // Convert relative coordinates to absolute for comparison
            const newPolygonAbs = newPolygon.vertices.map(v => toAbsoluteCoords(v.x, v.y));
            
            // Check against all existing polygons
            for (const existingPolygon of polygons) {
                const existingPolygonAbs = existingPolygon.vertices.map(v => toAbsoluteCoords(v.x, v.y));
                
                if (polygonsIntersect(newPolygonAbs, existingPolygonAbs)) {
                    return true;
                }
            }
            return false;
        }

        // Modify the finalImage click handler for floor mode
        finalImage.addEventListener('click', function(e) {
            const rect = finalImage.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const clickPoint = { x, y };
            const relativePoint = toRelativeCoords(x, y);

            if (currentMode === 'floor') {
                if (!currentPolygon) {
                    currentPolygon = {
                        id: 'room-' + Date.now(),
                        vertices: [relativePoint],
                        name: ''
                    };
                } else {
                    const firstVertexAbs = toAbsoluteCoords(currentPolygon.vertices[0].x, currentPolygon.vertices[0].y);

                    if (currentPolygon.vertices.length >= 3 && distance(clickPoint, firstVertexAbs) < 10) {
                        // Close polygon with a copy of first point
                        currentPolygon.vertices.push({...currentPolygon.vertices[0]});
                        
                        // Check for overlap before proceeding
                        if (checkRoomOverlap(currentPolygon)) {
                            alert("This room overlaps with an existing room. Please draw it in a different area.");
                            currentPolygon = null;
                            clearTemporaryPolygon();
                            return;
                        }
                        
                        drawTemporaryPolygon();
                        polygonModal.style.display = 'flex';
                        return;
                    } else {
                        currentPolygon.vertices.push(relativePoint);
                    }
                }
                drawTemporaryPolygon();
            } else if (currentMode === 'device') {
                // Existing device mode code…
                let selectedPolygon = null;

                // Check if click is inside any polygon (using absolute coordinates for the check)
                for (let poly of polygons) {
                    // Convert polygon vertices to absolute for point-in-polygon check
                    const absVertices = poly.vertices.map(v => toAbsoluteCoords(v.x, v.y));
                    if (pointInPolygon(clickPoint, absVertices)) {
                        selectedPolygon = poly;
                        break;
                    }
                }

                if (!selectedPolygon) {
                    alert("Please mark a room first before adding sensors");
                    return;
                }

                document.getElementById('dotRoomId').value = selectedPolygon.id;
                document.getElementById('dotX').value = relativePoint.x; // Store relative X
                document.getElementById('dotY').value = relativePoint.y; // Store relative Y

                // Add a small red dot for visual cue (using absolute coordinates for display)
                const dot = document.createElement('div');
                dot.style.position = 'absolute';
                dot.style.width = '5px';
                dot.style.height = '5px';
                dot.style.background = 'red';
                dot.style.borderRadius = '50%';
                dot.style.left = (x - 2.5) + 'px';
                dot.style.top = (y - 2.5) + 'px';
                const dotId = 'dot-' + dotCount;
                dot.setAttribute('id', dotId);
                canvasContainer.appendChild(dot);
                temporaryDotId = dotId;
                dotModal.style.display = 'flex';
            } else if (currentMode === 'delete') {
                // New delete mode branch: check if click is inside a room polygon
                let selectedPolygon = null;
                for (let poly of polygons) {
                    // Convert polygon vertices to absolute for point-in-polygon check
                    const absVertices = poly.vertices.map(v => toAbsoluteCoords(v.x, v.y));
                    if (pointInPolygon(clickPoint, absVertices)) {
                        selectedPolygon = poly;
                        break;
                    }
                }

                if (!selectedPolygon) {
                    alert("No room selected for deletion. Please click inside a room.");
                    return;
                }
                if (confirm("Are you sure you want to delete this room and all its sensors?")) {
                    deletePolygon(selectedPolygon.id);
                }
            }
        });

        // Save Polygon (Room) details from modal
        savePolygonBtn.addEventListener('click', function() {
            const roomName = polygonNameInput.value.trim();
            if (!roomName) {
                alert("Please add room name");
                return;
            }
            currentPolygon.name = roomName;
            polygons.push(currentPolygon);
            drawFinalPolygon(currentPolygon);
            hidePolygonModal();
            currentPolygon = null;
        });
        // Draw final polygon with a white label at (approximately) top-left of the room
        function drawFinalPolygon(polygon) {
            // Convert relative coordinates to absolute for drawing
            const vertices = polygon.vertices.map(v => toAbsoluteCoords(v.x, v.y));
            const pointsStr = vertices.map(v => `${v.x},${v.y}`).join(" ");

            // Create polygon element with fill opacity 40%
            const polyElem = document.createElementNS("http://www.w3.org/2000/svg", "polygon");
            polyElem.setAttribute("points", pointsStr);
            polyElem.setAttribute("fill", "rgba(0, 123, 255, 0.1)");
            polyElem.setAttribute("stroke", "blue");
            polyElem.setAttribute("stroke-width", "2");
            polyElem.setAttribute("data-id", polygon.id);
            finishedGroup.appendChild(polyElem);
            // Determine label position.
            const firstVertex = vertices[0];
            // Adjust these offsets as needed – for example, +5 for x and -5 for y so the label sits just above the vertex.
            const labelX = firstVertex.x + 5;
            const labelY = firstVertex.y - 5;

            // Create a group for the label elements (a background rect and text)
            const labelGroup = document.createElementNS("http://www.w3.org/2000/svg", "g");
            // Background rectangle
            const rect = document.createElementNS("http://www.w3.org/2000/svg", "rect");
            // Width/height can be adjusted based on your needs
            rect.setAttribute("x", labelX - 5);
            rect.setAttribute("y", labelY - 15);
            rect.setAttribute("width", "100");
            rect.setAttribute("height", "20");
            rect.setAttribute("fill", "white");
            labelGroup.appendChild(rect);
            // Text label (room name)
            const textElem = document.createElementNS("http://www.w3.org/2000/svg", "text");
            textElem.setAttribute("x", labelX);
            textElem.setAttribute("y", labelY);
            textElem.setAttribute("fill", "black");
            textElem.setAttribute("font-size", "12");
            textElem.setAttribute("data-id", polygon.id);
            textElem.textContent = polygon.name;
            labelGroup.appendChild(textElem);
            finishedGroup.appendChild(labelGroup);
        }
        // Clear temporary drawing group only (not finished polygons)
        function clearTemporaryPolygon() {
            tempGroup.innerHTML = "";
        }
        // Delete polygon (room) and its associated sensors
        function deletePolygon(polyId) {
            const index = polygons.findIndex(p => p.id === polyId);
            if (index !== -1) {
                polygons.splice(index, 1);
            }
            // Remove associated sensors from productsData, their dots, and table rows
            productsData = productsData.filter(sensor => {
                if (sensor.roomId === polyId) {
                    const dotElem = document.getElementById(sensor.id);
                    if (dotElem) dotElem.remove();
                    const labelElem = document.getElementById('label-' + sensor.id);
                    if (labelElem) labelElem.remove();

                    const row = document.getElementById('row-' + sensor.id);
                    if (row) row.remove();
                    return false;
                }
                return true;
            });

            // Renumber all remaining sensors starting from 1
            renumberSensors();

            updateTotalPrice();
            // Redraw finished group: clear and draw all finished polygons from polygons array
            finishedGroup.innerHTML = "";
            polygons.forEach(p => drawFinalPolygon(p));
        }

        // Function to renumber all sensors sequentially from 1
        function renumberSensors() {
            // Reset the global counter
            nextSensorNumber = 1;

            // Update each sensor in productsData array with new display number
            productsData.forEach((sensor, index) => {
                const displayNumber = index + 1;
                sensor.displayNumber = displayNumber;

                // Update the label text
                const labelElem = document.getElementById('label-' + sensor.id);
                if (labelElem) {
                    labelElem.innerText = displayNumber + ". " + sensor.name;
                }

                // Update the table row
                const row = document.getElementById('row-' + sensor.id);
                if (row) {
                    const firstCell = row.querySelector('td:first-child');
                    if (firstCell) {
                        firstCell.textContent = displayNumber;
                    }
                }
            });

            // Set nextSensorNumber to next available number
            nextSensorNumber = productsData.length + 1;
        }
        // Save sensor (device) details from sensor modal
        document.getElementById('saveDot').addEventListener('click', function() {
            const name = document.getElementById('dotName').value.trim();
            const description = document.getElementById('dotNote').value.trim();
            const RoleId = document.getElementById('RoleId').value.trim();
            const sensor = document.getElementById('sensorSelect').value;
            const selectedOption = sensorSelectTag.options[sensorSelectTag.selectedIndex];
            const sensorIdVal = selectedOption ? selectedOption.getAttribute("data-id") : "";
            const sensorImage = selectedOption ? selectedOption.getAttribute("data-image") : "";
            const sensorCode = selectedOption ? selectedOption.getAttribute("data-code") : "";

            if (!name || !sensor || !description) {
                alert('Please enter a name, note and select a sensor.');
                return;
            }

            // Get relative coordinates from hidden fields
            const relX = parseFloat(document.getElementById('dotX').value);
            const relY = parseFloat(document.getElementById('dotY').value);

            // Convert to absolute for display
            const {
                x,
                y
            } = toAbsoluteCoords(relX, relY);

            const price = sensorPrices[sensor] || "0";
            const roomId = document.getElementById('dotRoomId').value;
            const currentDotId = 'dot-' + dotCount;

            // Use the global counter for display number
            const displayNumber = nextSensorNumber;

            // Store using relative coordinates
            productsData.push({
                id: currentDotId,
                name, // sensor name from modal input
                description, // description name from modal input
                sensor, // sensor type/attached sensor name
                sensorId: sensorIdVal,
                sensorImage,
                price,
                x: relX, // store relative X
                y: relY, // store relative Y
                roomId,
                displayNumber // Store the display number
            });

            // Update dot element tooltip
            const dot = document.getElementById(currentDotId);
            if (dot) {
                dot.dataset.name = name;
                dot.dataset.description = description;
                dot.dataset.sensor = sensor;
                dot.title = `Name: ${name}, Sensor: ${sensor}`;
            }

            // Create a sensor label element that displays the sensor name with display number
            const sensorLabel = document.createElement('span');
            sensorLabel.setAttribute('id', 'label-' + currentDotId);
            sensorLabel.innerText = displayNumber + ". " + name; // Add display number before name
            sensorLabel.style.position = 'absolute';
            sensorLabel.style.fontSize = '12px';
            sensorLabel.style.background = "white";
            sensorLabel.style.padding = '2px 4px';
            sensorLabel.style.color = 'black';
            sensorLabel.style.left = (x - 10) + 'px';
            sensorLabel.style.top = (y - 25) + 'px';
            canvasContainer.appendChild(sensorLabel);

            // Increment dot count and next sensor number
            dotCount++;
            nextSensorNumber++;

            // Create sensor table row with room name
            const room = polygons.find(p => p.id === roomId);
            const roomName = room ? room.name : "";

            // Create image HTML with proper error handling and styling
            const imageHtml = sensorImage ?
                `<img src="${sensorImage}" alt="${sensor}" style="width:50px; height:50px; object-fit:contain; border-radius:4px;" onerror="this.onerror=null; this.src='https://via.placeholder.com/50?text=No+Image';">` :
                `<div style="width:50px; height:50px; display:flex; align-items:center; justify-content:center; background-color:#f0f0f0; border-radius:4px; font-size:10px; color:#666;">No Image</div>`;

            const tr = document.createElement('tr');
            tr.setAttribute('id', 'row-' + currentDotId);
            tr.innerHTML = `<td>${displayNumber}</td>
                      <td>${roomName}</td>
                      <td>${name}</td>
                      <td>${imageHtml}</td>
                      <td>${description}</td>
                      <td>${sensorCode}</td>
                      <td>${sensor}</td>    
                   <td>
                   <input type="number" value="${parseFloat(price).toFixed(2)}" required ${RoleId===1 || RoleId===2 ? 'readonly' : '' }
                    class="price-input" step="0.01" min="0.01" onblur="enforceTwoDecimalFormat(this)" onchange="TotalPriceChanges(this)"
                    inputmode="decimal" />
                </td>
                      <td><button class="delete-btn" data-dotid="${currentDotId}">✕</button></td>`;
                    sensorTableBody.appendChild(tr);
            // Sensor row delete handler
          
            // Sensor row delete handler
                        tr.querySelector('.delete-btn').addEventListener('click', function() {
                        const dotId = this.getAttribute('data-dotid');
                        const dotElem = document.getElementById(dotId);
                        if (dotElem) dotElem.remove();
                        const labelElem = document.getElementById('label-' + dotId);
                        if (labelElem) labelElem.remove();
                        const row = document.getElementById('row-' + dotId);
                        if (row) row.remove();
                        productsData = productsData.filter(item => item.id !== dotId);
                        
                        // Renumber all remaining sensors after deletion
                        renumberSensors();
                        
                        updateTotalPrice();
                        updateSensorSummary(); // Add this line to update sensor summary
                        });
            hideDotModal({
                keepDot: true
            });
            updateTotalPrice();
            updateSensorSummary();
        });
       function enforceTwoDecimalFormat(input) {
        let value = input.value.trim();
        
        // If empty or invalid number
        if (!value || isNaN(value)) {
        input.value = "0.01";
        return;
        }
        
        let floatVal = parseFloat(value);
        
        // Enforce minimum value
        if (floatVal < 0.01) { input.value="0.01" ; return; } // Round to exactly 2 decimal places
            input.value=floatVal.toFixed(2); // Re-trigger change manually (optional, if onchange doesn't fire) //
            input.dispatchEvent(new Event('change')); }
        // Update total price display and sensor count
        function updateTotalPrice() {
            const total = productsData.reduce((acc, item) => acc + Number(item.price), 0);
            document.getElementById('totalPrice').textContent = total;
            document.getElementById('totalCount').textContent = productsData.length;
            totalPrice = total;
        }
    
        function updateSensorSummary() {
        const summaryTableBody = document.querySelector('#sensorSummaryTable tbody');
        const sensorSummary = {};
        
        // Group sensors by name and calculate quantities and totals using actual table values
        productsData.forEach(sensor => {
        // Get the actual price from the table input instead of stored value
        const tableRow = document.getElementById('row-' + sensor.id);
        const priceInput = tableRow ? tableRow.querySelector('.price-input') : null;
        const actualPrice = priceInput ? parseFloat(priceInput.value) || 0 : sensor.price;
        
        if (!sensorSummary[sensor.sensor]) {
        sensorSummary[sensor.sensor] = {
        name: sensor.sensor,
        quantity: 0,
        unitPrice: actualPrice,
        totalPrice: 0
        };
        }
        
        sensorSummary[sensor.sensor].quantity++;
        // Use the actual price from table for calculations
        sensorSummary[sensor.sensor].unitPrice = actualPrice;
        sensorSummary[sensor.sensor].totalPrice = sensorSummary[sensor.sensor].quantity * actualPrice;
        });
        
        // Clear existing rows
        summaryTableBody.innerHTML = '';
        
        // Add rows for each sensor type
        Object.values(sensorSummary).forEach(sensor => {
        const row = document.createElement('tr');
        row.innerHTML = `
        <td class="border p-2">${sensor.name}</td>
        <td class="border p-2">${sensor.quantity}</td>
        <td class="border p-2">$${sensor.unitPrice.toFixed(2)}</td>
        <td class="border p-2">$${sensor.totalPrice.toFixed(2)}</td>
        `;
        summaryTableBody.appendChild(row);
        });
        
        // Update totals using actual prices from table
        const totalSensors = productsData.length;
        let totalPrice = 0;
        document.querySelectorAll('.price-input').forEach(function(input) {
        totalPrice += parseFloat(input.value) || 0;
        });
        
        document.getElementById('summaryTotalSensors').textContent = totalSensors;
        document.getElementById('summaryTotalPrice').textContent = `$${totalPrice.toFixed(2)}`;
        }
        function TotalPriceChanges(element) {
            var updatePrice = parseFloat(element.value) || 0;
            var totalPrice = 0;
            var DiscountedPrice = 0;
            
            // Get the sensor type from the row
            const row = element.closest('tr');
            const sensorType = row.querySelector('td:nth-child(7)').textContent;
            
            // Update all price inputs for the same sensor type
            document.querySelectorAll('#sensorTable tbody tr').forEach(function(row) {
                const rowSensorType = row.querySelector('td:nth-child(7)').textContent;
                if (rowSensorType === sensorType) {
                    const priceInput = row.querySelector('.price-input');
                    if (priceInput) {
                        priceInput.value = updatePrice;
                        // console.log(priceInput.value);
                    }
                }
            });
            
            // Calculate total from editable price inputs
        document.querySelectorAll('.price-input').forEach(function(input) {
            const price = parseFloat(input.value) || 0;
            totalPrice += price;
            DiscountedPrice += price;
            });
            
            // Round and display to 2 decimal places
            totalPrice = parseFloat(totalPrice.toFixed(2));
            DiscountedPrice = parseFloat(DiscountedPrice.toFixed(2));
            
            // Update the total price display
            document.getElementById('totalPrice').innerHTML = totalPrice;
            
            // Update the global totalPrice variable
            totalPrice = totalPrice;
            
            // Update all prices in productsData array for the same sensor type
            productsData.forEach((item, index) => {
                if (item.sensor === sensorType) {
                    productsData[index].price = updatePrice;
                }
            });

            // Update the sensor summary table
            updateSensorSummary();
        }
        // Add event listener for price input changes
        document.addEventListener('DOMContentLoaded', function() {
            // Delegate event listener for price inputs
            document.querySelector('#sensorTable').addEventListener('change', function(e) {
                if (e.target.classList.contains('price-input')) {
                    TotalPriceChanges(e.target);
                }
            });
            updateSensorSummary();
        });
        // Generate PDF using jsPDF and prepare data in the desired format
        generatePDFBtn.addEventListener('click', function() {

            floorNameInput.value = floorNameInput.value.trim();
            if (!floorNameInput.value) {
                alert("Please enter a floor name.");
                return;
            }

            // Show loading state
            generatePDFBtn.disabled = true;
            generatePDFBtn.classList.add('loading');

            const offscreenCanvas = document.createElement('canvas');
            const ctx = offscreenCanvas.getContext('2d');
            const baseImg = new Image();

            baseImg.onload = function() {
                offscreenCanvas.width = baseImg.naturalWidth;
                offscreenCanvas.height = baseImg.naturalHeight;
                const scaleFactor = offscreenCanvas.width / finalImage.width;

                // Draw base image (floorplan)
                ctx.drawImage(baseImg, 0, 0, offscreenCanvas.width, offscreenCanvas.height);

                // Instead of converting SVG, manually draw polygons on canvas
                // Draw polygons directly to match PDF dimensions
                polygons.forEach(polygon => {
                    // Draw polygon
                    ctx.beginPath();
                    const vertices = polygon.vertices.map(v => ({
                        x: (v.x / 100) * offscreenCanvas.width,
                        y: (v.y / 100) * offscreenCanvas.height
                    }));

                    if (vertices.length > 0) {
                        ctx.moveTo(vertices[0].x, vertices[0].y);
                        for (let i = 1; i < vertices.length; i++) {
                            ctx.lineTo(vertices[i].x, vertices[i].y);
                        }
                        ctx.closePath();
                        ctx.fillStyle = "rgba(0, 123, 255, 0.1)";
                        ctx.fill();
                        ctx.strokeStyle = "blue";
                        ctx.lineWidth = 2;
                        ctx.stroke();

                        // Add room label
                        const labelX = vertices[0].x + 5;
                        const labelY = vertices[0].y - 5;

                        // Label background
                        ctx.fillStyle = "white";
                        ctx.fillRect(labelX - 5, labelY - 15, 100, 20);

                        // Label text
                        ctx.fillStyle = "black";
                        ctx.font = "12px Arial";
                        ctx.fillText(polygon.name, labelX, labelY);
                    }
                });

                // Draw sensor red dots
                productsData.forEach((sensor, index) => {
                    // Convert relative coordinates to canvas absolute coordinates
                    const dotX = (sensor.x / 100) * offscreenCanvas.width;
                    const dotY = (sensor.y / 100) * offscreenCanvas.height;
                    const radius = 2.5 * scaleFactor;
                    const sensorNumber = index + 1; // Get sensor number (1-based index)

                    ctx.beginPath();
                    ctx.arc(dotX, dotY, radius, 0, Math.PI * 2);
                    ctx.fillStyle = 'red';
                    ctx.fill();

                    // Draw sensor label
                    ctx.font = "12px Arial";
                    const text = sensorNumber + ". " + sensor.name; // Add number before name
                    const textMetrics = ctx.measureText(text);
                    const textWidth = textMetrics.width;
                    const textHeight = 12;
                    const padding = 2;

                    // Label background
                    ctx.fillStyle = "white";
                    ctx.fillRect(dotX - 10, dotY - 25, textWidth + padding * 2, textHeight + padding *
                        2);

                    // Label text
                    ctx.fillStyle = "black";
                    ctx.fillText(text, dotX - 10 + padding, dotY - 25 + textHeight);
                });

                // ✅ Create final image from canvas
                offscreenCanvas.toBlob(function(blob) {
                    if (!blob) {
                        console.error("Failed to create image blob from canvas");
                        alert("Error: Failed to create image from canvas. Please try again.");
                        generatePDFBtn.disabled = false;
                        generatePDFBtn.classList.remove('loading');
                        return;
                    }

                    // Create a proper file from the blob with a filename
                    const imageFile = new File([blob], 'canvas-image.png', {
                        type: 'image/png',
                        lastModified: new Date().getTime()
                    });

                    // ✅ Send image & data to server
                    const roomsData = polygons.map(room => ({
                        id: room.id,
                        roomName: room.name,
                        coordinates: room.vertices // Already stored as relative coordinates
                    }));

                    // const sensorsData = productsData.map(sensor => {
                    //     const roomObj = polygons.find(p => p.id === sensor.roomId);

                    //     // Extract image path from sensorImage (could be object or string)
                    //     let imagePath = null;
                    //     if (typeof sensor.sensorImage === 'object' && sensor.sensorImage) {
                    //         imagePath = sensor.sensorImage.image || '';
                    //     } else {
                    //         imagePath = sensor.sensorImage || '';
                    //     }

                    //     // Trim any URL parts to get just the relative path
                    //     if (imagePath && imagePath.includes('/storage/')) {
                    //         const parts = imagePath.split('/storage/');
                    //         imagePath = parts[parts.length -
                    //             1]; // Get the last part after '/storage/'
                    //     }

                    //     return {
                    //         // Properties for backend/PHP template
                    //         name: sensor.name,
                    //         note: sensor.description,
                    //         price: sensor.price,
                    //         room_id: sensor.roomId,
                    //         type: sensor.sensor,
                    //         sensor_id: sensor.sensorId,
                    //         image: imagePath, // Plain path for storage
                    //         raw_image_path: imagePath, // Path without processing
                    //         image_url: "{{ asset('storage') }}/" +
                    //             imagePath, // Full URL for browser
                    //         coordinates: {
                    //             x: sensor.x,
                    //             y: sensor.y
                    //         },

                    //         // Properties for frontend/JavaScript
                    //         sensorName: sensor.name,
                    //         sensorDescription: sensor.description,
                    //         sensorType: sensor.sensor,
                    //         sensorPrice: sensor.price,
                    //         sensorId: sensor.sensorId,
                    //         sensorImage: imagePath, // Pass the actual image path string
                    //         roomName: roomObj ? roomObj.name : '',
                    //         sensorCoordinates: {
                    //             x: sensor.x,
                    //             y: sensor.y
                    //         },
                    //         roomId: sensor.roomId
                    //     };
                    // });
                    const sensorsData = productsData.map((sensor, index) => {
                    const roomObj = polygons.find(p => p.id === sensor.roomId);
                    
                    // Extract image path from sensorImage (could be object or string)
                    let imagePath = null;
                    if (typeof sensor.sensorImage === 'object' && sensor.sensorImage) {
                    imagePath = sensor.sensorImage.image || '';
                    } else {
                    imagePath = sensor.sensorImage || '';
                    }
                    
                    // Trim any URL parts to get just the relative path
                    if (imagePath && imagePath.includes('/storage/')) {
                    const parts = imagePath.split('/storage/');
                    imagePath = parts[parts.length -
                    1]; // Get the last part after '/storage/'
                    }
                    
                    // Get the actual price from the table input instead of stored value
                    const tableRow = document.getElementById('row-' + sensor.id);
                    const priceInput = tableRow ? tableRow.querySelector('.price-input') : null;
                    const actualPrice = priceInput ? parseFloat(priceInput.value) || 0 : sensor.price;
                    
                    return {
                    // Properties for backend/PHP template
                    name: sensor.name,
                    note: sensor.description,
                    price: actualPrice, // Use actual price from table input
                    room_id: sensor.roomId,
                    type: sensor.sensor,
                    sensor_id: sensor.sensorId,
                    image: imagePath, // Plain path for storage
                    raw_image_path: imagePath, // Path without processing
                    image_url: "{{ asset('storage') }}/" +
                    imagePath, // Full URL for browser
                    coordinates: {
                    x: sensor.x,
                    y: sensor.y
                    },
                    
                    // Properties for frontend/JavaScript
                    sensorName: sensor.name,
                    sensorDescription: sensor.description,
                    sensorType: sensor.sensor,
                    sensorPrice: actualPrice, // Use actual price from table input
                    sensorId: sensor.sensorId,
                    sensorImage: imagePath, // Pass the actual image path string
                    roomName: roomObj ? roomObj.name : '',
                    sensorCoordinates: {
                    x: sensor.x,
                    y: sensor.y
                    },
                    roomId: sensor.roomId
                    };
                    });

                    // Calculate final DiscountedPrice before sending
                    let finalDiscountedPrice = 0;
                    document.querySelectorAll('.price-input').forEach(function(input) {
                        finalDiscountedPrice += parseFloat(input.value) || 0;
                    });

                    const formData = new FormData();

                    // Convert object data to strings for FormData
                    formData.append('roomsData', JSON.stringify(roomsData));
                    formData.append('sensorsData', JSON.stringify(sensorsData));
                    formData.append('totalPrice', finalDiscountedPrice);
                    // formData.append('discountedPrice', finalDiscountedPrice);
                    formData.append('floorName', floorNameInput.value);
                    formData.append('forUserName', forUserName.value);
                    formData.append('forUserAddress', forUserAddress.value);
                    
                    if(user_id.value){
                        formData.append('user_id', user_id.value);
                    }else{
                        formData.append('user_id', '');
                    }

                    // Make sure the image blob is valid before sending
                    if (imageFile instanceof Blob) {
                        formData.append('image', imageFile, 'canvas-image.png');
                    } else {
                        console.error("Invalid image file:", imageFile);
                        alert(
                            "Error: Cannot create PDF because the image is invalid. Please try again."
                        );
                        generatePDFBtn.disabled = false;
                        generatePDFBtn.classList.remove('loading');
                        return;
                    }

                    // ✅ Create clean image (no coordinates)
                    const cleanCanvas = document.createElement('canvas');
                    const ctx = cleanCanvas.getContext('2d');
                    cleanCanvas.width = offscreenCanvas.width;
                    cleanCanvas.height = offscreenCanvas.height;

                    // Draw only the base background image
                    ctx.drawImage(baseImg, 0, 0, cleanCanvas.width, cleanCanvas.height);

                    cleanCanvas.toBlob(function(cleanBlob) {
                        if (cleanBlob) {
                            formData.append('image_clean', cleanBlob, 'canvas-image-clean.png');

                            // Now send the AJAX request after clean image is ready
                            $.ajax({
                                url: `{{ route('estimations.store') }}`,
                                type: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                success: function(data) {
                                    // Remove loading state
                                    generatePDFBtn.disabled = false;
                                    generatePDFBtn.classList.remove('loading');

                                    if (data && data.success) {
                                        if (data.download_url) {
                                            try {
                                                var link = document.createElement(
                                                    'a');
                                                link.href = data.download_url;
                                                link.download = data.filename ||
                                                    'estimation.pdf';
                                                link.target = '_blank';

                                                document.body.appendChild(link);
                                                link.click();

                                                setTimeout(function() {
                                                    document.body
                                                        .removeChild(link);
                                                    alert(data.message ||
                                                        "PDF generated successfully!" +
                                                        "\n\nIf download doesn't start automatically, click OK to open in a new tab."
                                                    );
                                                    window.open(data
                                                        .download_url,
                                                        '_blank');
                                                    setTimeout(function() {
                                                        window
                                                            .location
                                                            .href =
                                                            `{{ route('estimations.index') }}`;
                                                    }, 2000);
                                                }, 500);
                                            } catch (e) {
                                                console.error("Download error:", e);
                                                alert(
                                                    "Error automatically downloading the PDF. Click OK to open it in a new tab."
                                                );
                                                window.open(data.download_url,
                                                    '_blank');
                                            }
                                        } else {
                                            alert(
                                                "PDF generation succeeded but no download URL was provided."
                                            );
                                            console.error(
                                                "Missing download_url in response:",
                                                data);
                                        }
                                    } else {
                                        alert(
                                            "Failed to generate PDF. See console for details."
                                        );
                                        console.error(
                                            "Server returned error or invalid response:",
                                            data);
                                    }
                                },
                                error: function(xhr, status, error) {
                                    generatePDFBtn.disabled = false;
                                    generatePDFBtn.classList.remove('loading');

                                    console.error("AJAX error:", error);
                                    console.error("Response status:", status);
                                    console.error("Response text:", xhr
                                        .responseText);

                                    try {
                                        const errorData = JSON.parse(xhr
                                            .responseText);
                                        alert("Error: " + (errorData.message ||
                                            "Failed to generate PDF"));
                                    } catch (e) {
                                        alert(
                                            "Error: Failed to generate PDF. See console for details."
                                        );
                                    }
                                }
                            });

                        } else {
                            alert("Failed to generate clean image.");
                            generatePDFBtn.disabled = false;
                            generatePDFBtn.classList.remove('loading');
                        }
                    });

                }, 'image/png', 0.95); // Specify format and quality
            };

            baseImg.src = finalImage.src;
        });

        // Responsive handling: update polygon and dot positions when window resized
        window.addEventListener('resize', function() {
            // Redraw all polygons when window size changes
            if (polygons.length > 0) {
                finishedGroup.innerHTML = "";
                polygons.forEach(p => drawFinalPolygon(p));
            }

            // Update all sensor dots and their labels
            if (productsData.length > 0) {
                productsData.forEach((sensor, index) => {
                    const dot = document.getElementById(sensor.id);
                    const label = document.getElementById('label-' + sensor.id);
                    const sensorNumber = index + 1; // Get 1-based index

                    if (dot && label) {
                        // Convert relative to absolute coordinates
                        const {
                            x,
                            y
                        } = toAbsoluteCoords(sensor.x, sensor.y);

                        // Update dot position
                        dot.style.left = (x - 2.5) + 'px';
                        dot.style.top = (y - 2.5) + 'px';

                        // Update label position and ensure it has the number prefix
                        label.style.left = (x - 10) + 'px';
                        label.style.top = (y - 25) + 'px';

                        // Make sure the label text includes the number
                        if (!label.innerText.startsWith(sensorNumber + ".")) {
                            label.innerText = sensorNumber + ". " + sensor.name;
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>