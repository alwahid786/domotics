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
      <div class="mb-2 mt-4 flex items-center gap-4">
        <input type="text" class="form-control border p-2 floor-name" placeholder="Floor name" required />
        <input type="file" id="imageUpload" accept="image/*" class="form-control border p-2 w-full">
      </div>
      <!-- Mode buttons: initially hidden, will be shown after picture upload -->
      <div id="modeButtons" class="my-4 flex items-center gap-4">
        <button id="floorModeBtn" class="mode-btn bg-dark rounded-md text-white font-medium px-4 py-2">Floor Mode</button>
        <button id="deviceModeBtn" class="mode-btn bg-dark rounded-md text-white font-medium px-4 py-2">Device Mode</button>
        <button id="deleteModeBtn" class="mode-btn bg-dark rounded-md text-white font-medium px-4 py-2">Delete Mode</button>
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
        <svg id="svgOverlay" style="position:absolute; top:0; left:0; width:100%; height:100%; pointer-events:none;">
          <g id="finishedPolygons"></g>
          <g id="tempDrawing"></g>
        </svg>
        <!-- Dots for sensors are appended to canvasContainer -->
      </div>

      <!-- Sensor List Table -->
      <div id="sensorListContainer" style="display: none;">
        <table id="sensorTable">
          <thead>
            <tr>
              <th>Sr. No</th>
              <th>Name</th>
              <th>Installation Notes</th>
              <th>Sensor</th>
              <th>Room</th>
              <th>Price</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody></tbody>
          <tfoot>
            <tr id="totalRow">
              <td colspan="5">Total Price</td>
              <td>$<span id="totalPrice">0</span></td>
              <td></td>
            </tr>
          </tfoot>
        </table>
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
    let temporaryDotId = null;

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
              id,
            } = sensor;
            sensorPrices[sensorName] = price;
            sensorSelectTag.innerHTML += `<option data-id="${id}" value="${sensorName}">${sensorName}</option>`;
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
    function hideDotModal({keepDot = false}) {
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
      const pointsStr = currentPolygon.vertices.map(v => `${v.x},${v.y}`).join(" ");
      // Draw dashed polyline
      const polyline = document.createElementNS("http://www.w3.org/2000/svg", "polyline");
      polyline.setAttribute("points", pointsStr);
      polyline.setAttribute("fill", "none");
      polyline.setAttribute("stroke", "blue");
      polyline.setAttribute("stroke-dasharray", "4");
      polyline.setAttribute("stroke-width", "2");
      tempGroup.appendChild(polyline);
      // Draw vertices as circles
      currentPolygon.vertices.forEach(v => {
        const circle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
        circle.setAttribute("cx", v.x);
        circle.setAttribute("cy", v.y);
        circle.setAttribute("r", "3");
        circle.setAttribute("fill", "blue");
        tempGroup.appendChild(circle);
      });
    }
    // Final image click handler: different behavior for floor vs device mode
    finalImage.addEventListener('click', function(e) {
      const rect = finalImage.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;
      const clickPoint = { x, y };

      if (currentMode === 'floor') {
        // Existing floor mode code…
        if (!currentPolygon) {
          currentPolygon = {
            id: 'room-' + Date.now(),
            vertices: [clickPoint],
            name: ''
          };
        } else {
          // Auto-complete if close to the first vertex and at least 3 vertices exist
          if (currentPolygon.vertices.length >= 3 && distance(clickPoint, currentPolygon.vertices[0]) < 10) {
            currentPolygon.vertices.push(currentPolygon.vertices[0]); // close polygon
            drawTemporaryPolygon();
            polygonModal.style.display = 'flex';
            return;
          } else {
            currentPolygon.vertices.push(clickPoint);
          }
        }
        drawTemporaryPolygon();
      } else if (currentMode === 'device') {
        // Existing device mode code…
        let selectedPolygon = null;
        for (let poly of polygons) {
          if (pointInPolygon(clickPoint, poly.vertices)) {
            selectedPolygon = poly;
            break;
          }
        }
        if (!selectedPolygon) {
          alert("Please mark a room first before adding sensors");
          return;
        }
        document.getElementById('dotRoomId').value = selectedPolygon.id;
        document.getElementById('dotX').value = x;
        document.getElementById('dotY').value = y;
        // Add a small red dot for visual cue
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
          if (pointInPolygon(clickPoint, poly.vertices)) {
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
      const pointsStr = polygon.vertices.map(v => `${v.x},${v.y}`).join(" ");
      // Create polygon element with fill opacity 40%
      const polyElem = document.createElementNS("http://www.w3.org/2000/svg", "polygon");
      polyElem.setAttribute("points", pointsStr);
      polyElem.setAttribute("fill", "rgba(0, 123, 255, 0.1)");
      polyElem.setAttribute("stroke", "blue");
      polyElem.setAttribute("stroke-width", "2");
      polyElem.setAttribute("data-id", polygon.id);
      finishedGroup.appendChild(polyElem);
      // Determine label position.
      const firstVertex = polygon.vertices[0];
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
      updateTotalPrice();
      // Redraw finished group: clear and draw all finished polygons from polygons array
      finishedGroup.innerHTML = "";
      polygons.forEach(p => drawFinalPolygon(p));
    }
    // Save sensor (device) details from sensor modal
    document.getElementById('saveDot').addEventListener('click', function() {
      const name = document.getElementById('dotName').value.trim();
      const description = document.getElementById('dotNote').value.trim();
      const sensor = document.getElementById('sensorSelect').value;
      const selectedOption = sensorSelectTag.options[sensorSelectTag.selectedIndex];
      const sensorIdVal = selectedOption ? selectedOption.getAttribute("data-id") : "";
      if (!name || !sensor || !description) {
        alert('Please enter a name, note and select a sensor.');
        return;
      }
      const x = parseFloat(document.getElementById('dotX').value);
      const y = parseFloat(document.getElementById('dotY').value);
      const price = sensorPrices[sensor] || "0";
      const roomId = document.getElementById('dotRoomId').value;
      const currentDotId = 'dot-' + dotCount;
      productsData.push({
        id: currentDotId,
        name, // sensor name from modal input
        description, // description name from modal input
        sensor, // sensor type/attached sensor name
        sensorId: sensorIdVal,
        price,
        x,
        y,
        roomId
      });
      // Update dot element tooltip
      const dot = document.getElementById(currentDotId);
      if (dot) {
        dot.dataset.name = name;
        dot.dataset.description = description;
        dot.dataset.sensor = sensor;
        dot.title = `Name: ${name}, Sensor: ${sensor}`;
      }

      // Create a sensor label element that displays the sensor name
      // This label will be positioned relative to the dot
      const sensorLabel = document.createElement('span');
      sensorLabel.setAttribute('id', 'label-' + currentDotId);
      sensorLabel.innerText = name;
      sensorLabel.style.position = 'absolute';
      sensorLabel.style.fontSize = '12px';
      sensorLabel.style.background = "white";
      sensorLabel.style.padding = '2px 4px';
      sensorLabel.style.color = 'black';
      sensorLabel.style.left = (x - 10) + 'px';
      sensorLabel.style.top = (y - 25) + 'px';
      canvasContainer.appendChild(sensorLabel);

      dotCount++;
      // Create sensor table row with room name
      const room = polygons.find(p => p.id === roomId);
      const roomName = room ? room.name : "";
      const tr = document.createElement('tr');
      tr.setAttribute('id', 'row-' + currentDotId);
      tr.innerHTML = `<td>${dotCount}</td>
                      <td>${name}</td>
                      <td>${description}</td>
                      <td>${sensor}</td>
                      <td>${roomName}</td>
                      <td>$${price}</td>
                      <td><button class="delete-btn" data-dotid="${currentDotId}">✕</button></td>`;
      sensorTableBody.appendChild(tr);
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
        updateTotalPrice();
      });
      hideDotModal({keepDot: true});
      updateTotalPrice();
    });
    // Update total price display
    function updateTotalPrice() {
      const total = productsData.reduce((acc, item) => acc + Number(item.price), 0);
      document.getElementById('totalPrice').textContent = total;
      totalPrice = total;
    }
    // Generate PDF using jsPDF and prepare data in the desired format
    generatePDFBtn.addEventListener('click', function () {

      floorNameInput.value = floorNameInput.value.trim();
      if (!floorNameInput.value) {
        alert("Please enter a floor name.");
        return;
      }

    generatePDFBtn.disabled = true;

    const offscreenCanvas = document.createElement('canvas');
    const ctx = offscreenCanvas.getContext('2d');
    const baseImg = new Image();

    baseImg.onload = function () {
        offscreenCanvas.width = baseImg.naturalWidth;
        offscreenCanvas.height = baseImg.naturalHeight;
        const scaleFactor = offscreenCanvas.width / finalImage.width;

        // Draw base image (floorplan)
        ctx.drawImage(baseImg, 0, 0, offscreenCanvas.width, offscreenCanvas.height);

        // Convert SVG overlay to image
        const svgData = new XMLSerializer().serializeToString(svgOverlay);
        const svgBlob = new Blob([svgData], { type: 'image/svg+xml;charset=utf-8' });
        const url = URL.createObjectURL(svgBlob);

        const svgImg = new Image();
        svgImg.onload = function () {
            ctx.drawImage(svgImg, 0, 0, offscreenCanvas.width, offscreenCanvas.height);
            URL.revokeObjectURL(url);

            // Draw sensor red dots
            const dots = canvasContainer.querySelectorAll('div');
            dots.forEach(dot => {
                let dotLeft = parseFloat(dot.style.left) * scaleFactor;
                let dotTop = parseFloat(dot.style.top) * scaleFactor;
                const radius = 2.5 * scaleFactor;

                ctx.beginPath();
                ctx.arc(dotLeft + radius, dotTop + radius, radius, 0, Math.PI * 2);
                ctx.fillStyle = 'red';
                ctx.fill();
            });

            // Draw sensor labels
            const sensorLabels = canvasContainer.querySelectorAll('span');
            sensorLabels.forEach(label => {
                let labelLeft = parseFloat(label.style.left) * scaleFactor;
                let labelTop = parseFloat(label.style.top) * scaleFactor;
                ctx.font = "12px Arial";
                const text = label.innerText;
                const textMetrics = ctx.measureText(text);
                const textWidth = textMetrics.width;
                const textHeight = 12;
                const padding = 2;

                // Label background
                ctx.fillStyle = "white";
                ctx.fillRect(labelLeft - padding, labelTop - textHeight - padding, textWidth + padding * 2, textHeight + padding * 2);

                // Label text
                ctx.fillStyle = "black";
                ctx.fillText(text, labelLeft, labelTop);
            });

            // ✅ Create final image from canvas
            offscreenCanvas.toBlob(function (blob) {
                const imageFile = new File([blob], 'canvas-image.png', { type: 'image/png' });

                // ✅ Send image & data to server
                const roomsData = polygons.map(room => ({
                    id: room.id,
                    roomName: room.name,
                    coordinates: room.vertices
                }));

                const sensorsData = productsData.map(sensor => {
                    const roomObj = polygons.find(p => p.id === sensor.roomId);
                    return {
                        sensorName: sensor.name,
                        sensorDescription: sensor.description,
                        sensorType: sensor.sensor,
                        sensorPrice: sensor.price,
                        sensorId: sensor.sensorId,
                        roomName: roomObj ? roomObj.name : '',
                        sensorCoordinates: {
                            x: sensor.x,
                            y: sensor.y
                        },
                        roomId: sensor.roomId
                    };
                });

                const formData = new FormData();
                formData.append('roomsData', JSON.stringify(roomsData));
                formData.append('sensorsData', JSON.stringify(sensorsData));
                formData.append('totalPrice', totalPrice);
                formData.append('image', imageFile);
                formData.append('floorName', floorNameInput.value);

                $.ajax({
                    url: '{{ route('estimations.store') }}',  
                    type: 'POST',                            
                    data: formData,                         
                    processData: false,                     
                    contentType: false,                   
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.success) {
                            alert(data.message);
                            setTimeout(() => {
                                window.location.href = "{{ route('estimations.index') }}";
                            }, 1000);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX error:", error);
                    }
                });

                // ✅ Also download PDF locally (but don’t send to backend)
                const pdf = new jsPDF('p', 'mm', 'a4');
                const pageWidth = pdf.internal.pageSize.getWidth();
                const margin = 10;
                const pdfImgWidth = pageWidth - (2 * margin);
                const pdfImgHeight = (offscreenCanvas.height * pdfImgWidth) / offscreenCanvas.width;

                const reader = new FileReader();
                reader.onloadend = function () {
                    const imgData = reader.result;
                    pdf.addImage(imgData, 'PNG', margin, margin, pdfImgWidth, pdfImgHeight);

                    // Add table
                    let tableData = [];
                    sensorTableBody.querySelectorAll('tr').forEach(row => {
                        const cols = row.querySelectorAll('td');
                        tableData.push([
                            cols[0].innerText,
                            cols[1].innerText,
                            cols[2].innerText,
                            cols[3].innerText,
                            cols[4].innerText,
                            cols[5].innerText,
                        ]);
                    });

                    const total = document.getElementById('totalPrice').innerText;
                    tableData.push(["", "", "", "Total Price", "$" + total]);

                    pdf.autoTable({
                        startY: pdfImgHeight + margin + 5,
                        head: [['Sr. No', 'Name', 'Installation Notes', 'Sensor', 'Room', 'Price']],
                        body: tableData,
                        theme: 'grid',
                        styles: { fontSize: 10 }
                    });

                    pdf.save('estimation.pdf');
                };
                reader.readAsDataURL(blob);

                generatePDFBtn.disabled = false;
            }, 'image/png');
        };
        svgImg.src = url;
    };

    baseImg.src = finalImage.src;
});


  </script>
</x-app-layout>