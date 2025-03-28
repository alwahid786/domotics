<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-800 leading-tight">
          {{ __('Nuovo Preventivo') }}
      </h2>
  </x-slot>

  <!-- Custom CSS for the modal -->
  <style>
      .modal-overlay {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background: rgba(0,0,0,0.5);
          display: none;
          align-items: center;
          justify-content: center;
          z-index: 9999;
      }
      .modal-content {
          background: #fff;
          padding: 20px;
          border-radius: 4px;
          width: 300px;
          position: relative;
          box-shadow: 0 5px 15px rgba(0,0,0,0.3);
      }
      .modal-header {
          font-size: 18px;
          margin-bottom: 15px;
      }
      .close-modal {
          position: absolute;
          top: 8px;
          right: 10px;
          font-size: 20px;
          cursor: pointer;
      }
      .modal-footer {
          text-align: right;
          margin-top: 15px;
      }
      .modal-footer button {
          margin-left: 5px;
      }
      /* Table styling */
      #sensorTable {
          margin-top: 20px;
          width: 100%;
          border-collapse: collapse;
      }
      #sensorTable th, #sensorTable td {
          border: 1px solid #ddd;
          padding: 8px;
          text-align: center;
      }
      /* Loader style for Generate PDF Button */
      .loading {
          opacity: 0.7;
          pointer-events: none;
      }
  </style>

<div style="padding-top: 30px; padding-bottom: 30px;">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white shadow p-2 rounded mt-4">
        <!-- Image Upload -->
        <div class="mb-2 mt-4">
            <input type="file" id="imageUpload" accept="image/*" class="form-control border p-2 w-full">
        </div>
    
        <!-- Image Crop Area (hidden until image is selected) -->
        <div id="cropContainer" class="mb-3" style="display: none;">
            <img id="imageToCrop" src="" alt="To Crop" style="max-width: 100%;">
            <button id="cropButton" class="btn btn-primary mt-2">Crop Image</button>
        </div>
    
        <!-- Container for final image (canvas) & dots -->
        <div id="canvasContainer" style="position: relative; display: inline-block;">
            <img id="finalImage" src="" alt="Final Image" style="max-width: 100%; display: none;">
            <!-- Dots will be appended here as <div> elements -->
        </div>
    
        <!-- Sensor List Table -->
        <div id="sensorListContainer" style="display: none;">
            <table id="sensorTable">
                <thead>
                    <tr>
                        <th>Sr. No</th>
                        <th>Name</th>
                        <th>Sensor</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr id="totalRow">
                        <td>Total Price</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>$<span id="totalPrice">0</span></td>
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

  <!-- Custom Modal for dot popup -->
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
                  <!-- Hidden fields to store dot coordinates -->
                  <input type="hidden" id="dotX">
                  <input type="hidden" id="dotY">
              </form>
          </div>
          <div class="modal-footer">
              <button type="button" id="cancelModal" class="btn btn-secondary">Cancel</button>
              <button type="button" id="saveDot" class="btn btn-primary">Save</button>
          </div>
      </div>
  </div>

  <!-- Scripts -->
  <!-- Bootstrap JS (if needed) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Cropper.js -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
  <!-- jsPDF and jsPDF AutoTable Plugin -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>

  <script>
      const { jsPDF } = window.jspdf;

      let cropper;
      const imageUpload = document.getElementById('imageUpload');
      const imageToCrop = document.getElementById('imageToCrop');
      const cropContainer = document.getElementById('cropContainer');
      const cropButton = document.getElementById('cropButton');
      const finalImage = document.getElementById('finalImage');
      const canvasContainer = document.getElementById('canvasContainer');
      const dotModal = document.getElementById('dotModal');
      const closeModal = document.getElementById('closeModal');
      const cancelModal = document.getElementById('cancelModal');
      const dotForm = document.getElementById('dotForm');
      const sensorTableBody = document.querySelector('#sensorTable tbody');
      const generatePDFBtn = document.getElementById('generatePDF');
      const sensorListContainer = document.getElementById('sensorListContainer');
      const pdfBtnContainer = document.getElementById('pdfBtnContainer');
      const sensorSelectTag = document.getElementById('sensorSelect');
      
      let sensorPrices = {};
      let selectedSensors = new Set();
      let lastDot = null;
      let dotCount = 0;
      let productsData = [];

      const fetchSensors = async () => {
        try {
            const res = await fetch("{{ route('estimations.sensor') }}");            
            if(!res.ok) {
             throw new Error("Error while fetching sensors")
            }
            const data = await res.json();
            console.log('product data', data)
            if (data?.sensors?.length > 0) {
                sensorPrices = {};
                data.sensors.forEach((sensor) => {
                    const { name: sensorName, price, id } = sensor;
                    sensorPrices[sensorName] = price;

                    sensorSelectTag.innerHTML += `<option data-id="${id}" value="${sensorName}">${sensorName}</option>`;
                });
            }
        } catch (error) {
            console.error("Error while fetching sensors", error.message)
        }
      }

      fetchSensors();

      function showModal(dot) {
          lastDot = dot;
          dotModal.style.display = 'flex';
      }
      function hideModal() {
          dotModal.style.display = 'none';
          dotForm.reset();

          if(lastDot) {
            lastDot = null;
          }
      }

      closeModal.addEventListener('click', hideModal);
      cancelModal.addEventListener('click', hideModal);

      // Image Upload & Cropper initialization
      imageUpload.addEventListener('change', function(event) {
          const file = event.target.files[0];
          if (file) {
              const reader = new FileReader();
              reader.onload = function(e) {
                  imageToCrop.src = e.target.result;
                  cropContainer.style.display = 'block';
                  if (cropper) { cropper.destroy(); }
                  cropper = new Cropper(imageToCrop, {
                      aspectRatio: 16 / 9,
                      viewMode: 1,
                  });
              };
              reader.readAsDataURL(file);
          }
      });

      // Crop Image & display final image with dots container active
      cropButton.addEventListener('click', function() {
          if (cropper) {
              const canvas = cropper.getCroppedCanvas();
              finalImage.src = canvas.toDataURL();
              finalImage.style.display = 'block';
              cropContainer.style.display = 'none';
              // Show sensor table and PDF button once image is available
              sensorListContainer.style.display = 'block';
              pdfBtnContainer.style.display = 'block';
          }
      });

      // When user clicks on final image, add a red dot and show modal for details
      finalImage.addEventListener('click', function(e) {
          const rect = finalImage.getBoundingClientRect();
          const x = e.clientX - rect.left;
          const y = e.clientY - rect.top;

          const dot = document.createElement('div');
          dot.style.position = 'absolute';
          dot.style.width = '5px';
          dot.style.height = '5px';
          dot.style.background = 'red';
          dot.style.borderRadius = '50%';
          dot.style.left = (x - 2.5) + 'px';
          dot.style.top = (y - 2.5) + 'px';
          dot.setAttribute('id', 'dot-' + dotCount);
          canvasContainer.appendChild(dot);

          document.getElementById('dotX').value = x;
          document.getElementById('dotY').value = y;

          showModal(dot);
      });

      function updateTotalPrice() {
        const totalPriceEl = document.getElementById('totalPrice');
        const total = productsData.reduce((acc, item) => acc + Number(item.price), 0)
        totalPriceEl.textContent = total;
      }

      // Save dot details and add sensor info to table
      document.getElementById('saveDot').addEventListener('click', function() {
          const name = document.getElementById('dotName').value.trim();
          const sensor = document.getElementById('sensorSelect').value;
          const sensorSelectTag = document.getElementById('sensorSelect');
          const selectedOption = sensorSelectTag.options[sensorSelectTag.selectedIndex];
          const sensorId = selectedOption.getAttribute("data-id");

          if (!name || !sensor) {
              alert('Please enter a name and select a sensor.');
              return;
          }

          sensorSelectTag.querySelector(`option[value="${sensor}"]`).remove();

          // Retrieve coordinates from hidden inputs
          const x = parseFloat(document.getElementById('dotX').value);
          const y = parseFloat(document.getElementById('dotY').value);
          const price = sensorPrices[sensor] || "$0";
          const currentDotId = 'dot-' + dotCount;

          // Save the dot details into ProductsData array
          productsData.push({
              id: currentDotId,
              name,
              sensor,
              sensorId,
              price,
              x,
              y
          });

          // Increase dot count and set attributes on the dot element
          const dot = document.getElementById(currentDotId);
          dot.dataset.name = name;
          dot.dataset.sensor = sensor;
          dot.title = `Name: ${name}, Sensor: ${sensor}`;
          dotCount++;
          
          // Create a new table row with a delete button (✕)
          const tr = document.createElement('tr');
          tr.setAttribute('id', 'row-' + currentDotId);
          tr.innerHTML = `<td>${dotCount}</td>
                          <td>${name}</td>
                          <td>${sensor}</td>
                          <td>$${price}</td>
                          <td><button class="delete-btn" data-dotid="${currentDotId}">✕</button></td>`;
          sensorTableBody.appendChild(tr);

          // Add event listener to the delete button to remove the dot and row
          tr.querySelector('.delete-btn').addEventListener('click', function() {
              const dotId = this.getAttribute('data-dotid');
              // Remove dot from canvas
              const dotElem = document.getElementById(dotId);
              if(dotElem) dotElem.remove();
              // Remove table row
              const row = document.getElementById('row-' + dotId);
              if(row) row.remove();
              const removedProduct = productsData.find(item => item.id === dotId)
              // Remove the corresponding entry from ProductsData
              productsData = productsData.filter(item => item.id !== dotId);

              console.log('removed product', removedProduct);

              if(removedProduct) {
                const newOption = document.createElement("option");
                newOption.value = removedProduct.sensor;
                newOption.setAttribute('data-id', removedProduct.sensorId);
                newOption.textContent = removedProduct.sensor;
                sensorSelectTag.appendChild(newOption)
              }
              updateTotalPrice()
          });

          hideModal();
          updateTotalPrice()
          console.log('products data', productsData)
          console.log('final image', finalImage)
        });
        
        // Generate PDF instantly using composed image and sensor table
        generatePDFBtn.addEventListener('click', function() {
    generatePDFBtn.disabled = true;

    // Create an offscreen canvas using the natural dimensions of the final image
    const offscreenCanvas = document.createElement('canvas');
    const baseImg = new Image();
    baseImg.onload = function() {
        offscreenCanvas.width = baseImg.naturalWidth;
        offscreenCanvas.height = baseImg.naturalHeight;
        const ctx = offscreenCanvas.getContext('2d');
        // Draw the base image
        ctx.drawImage(baseImg, 0, 0, offscreenCanvas.width, offscreenCanvas.height);

        // Calculate scale factor: finalImage is displayed scaled, so compare its width to natural width
        const scaleFactor = offscreenCanvas.width / finalImage.width;

        // Draw each dot from the canvasContainer onto the offscreen canvas
        const dots = canvasContainer.querySelectorAll('div');
        dots.forEach(dot => {
            let dotLeft = parseFloat(dot.style.left);
            let dotTop = parseFloat(dot.style.top);
            // Scale coordinates
            dotLeft *= scaleFactor;
            dotTop *= scaleFactor;
            const radius = 2.5 * scaleFactor;
            ctx.beginPath();
            ctx.arc(dotLeft + radius, dotTop + radius, radius, 0, Math.PI * 2);
            ctx.fillStyle = 'red';
            ctx.fill();
        });

        // Convert the composed canvas to data URL
        const combinedImgData = offscreenCanvas.toDataURL('image/png');

        // Create a new PDF document and add the combined image
        const pdf = new jsPDF('p', 'mm', 'a4');
        const pageWidth = pdf.internal.pageSize.getWidth();
        const margin = 10;
        const pdfImgWidth = pageWidth - (2 * margin);
        const pdfImgHeight = (offscreenCanvas.height * pdfImgWidth) / offscreenCanvas.width;
        pdf.addImage(combinedImgData, 'PNG', margin, margin, pdfImgWidth, pdfImgHeight);

        // Gather table data from sensor table (only tbody rows)
        let tableData = [];
        sensorTableBody.querySelectorAll('tr').forEach(row => {
            const cols = row.querySelectorAll('td');
            tableData.push([cols[0].innerText, cols[1].innerText, cols[2].innerText, cols[3].innerText]);
        });
        // Append the total price row (the tfoot is outside tbody)
        const total = document.getElementById('totalPrice').innerText;
        tableData.push(["", "", "Total Price", "$" + total]);

        // Add sensor table below the image using autoTable
        pdf.autoTable({
            startY: pdfImgHeight + margin + 5,
            head: [['Sr. No', 'Name', 'Sensor', 'Price']],
            body: tableData,
            theme: 'grid',
            styles: { fontSize: 10 }
        });

        pdf.save('estimation.pdf');
        generatePDFBtn.disabled = false;
    };
    baseImg.src = finalImage.src;
});
  </script>
</x-app-layout>
