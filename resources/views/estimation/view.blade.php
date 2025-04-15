<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Stime') }}
            </h2>
        </div>
    </x-slot>

    <input type="hidden" id="estimate" value="{{ $estimate }}">

    <!-- Bootstrap CSS (if not already included) -->
    <!-- If you're using Laravel Mix or another bundler, make sure Bootstrap is properly imported -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />

    <style>
        body {
            overflow-x: hidden !important;
        }
    </style>

    <main class="mt-6">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 bg-white shadow p-4 rounded">

                <!-- Canvas Container -->
                <div class="mb-4 text-center">
                    <canvas id="estimationCanvas" style="border:1px solid #ccc; max-width: 100%;"></canvas>
                </div>

                <!-- Table Container -->
                <div class="table-responsive">
                    <table class="table table-bordered" id="estimationTable">
                        <thead>
                            <tr>
                                <th>Sr. No</th>
                                <th>Name</th>
                                <th>Sensor</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody id="estimationTableBody">
                            <!-- Rows will be appended by JS -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Total Price</td>
                                <td id="totalPrice"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </main>

    <!-- Bootstrap Modal for Dot Click -->
    <div class="modal fade" id="dotInfoModal" tabindex="-1" aria-labelledby="dotInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dotInfoModalLabel">Sensor Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="dotInfoContent">
                    <!-- Dot data will be dynamically inserted here -->
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

<!-- Bootstrap JS (if not already included) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    let estimationData = []; // Will hold the fetched data
    let dots = []; // Will hold the dot positions for click detection

    document.addEventListener("DOMContentLoaded", function() {
        fetchEstimations();
    });

    function fetchEstimations() {
        var estimateInput = document.getElementById("estimate");
        var estimate = estimateInput ? estimateInput.value : "";

        fetch("{{ route('estimations.fetch') }}?estimate=" + encodeURIComponent(estimate))
            .then(response => response.json())
            .then(data => {
                estimationData = data;
                console.log("Fetched data:", data);
                
                buildEstimationTable(data);
                drawCanvas(data);
            })
            .catch(error => console.error("Error fetching estimations:", error));
    }

    function buildEstimationTable(data) {
        const tableBody = document.getElementById("estimationTableBody");
        const totalPriceCell = document.getElementById("totalPrice");
        tableBody.innerHTML = ""; // clear existing rows

        let totalPrice = 0;
        data.forEach((item, index) => {
            const tr = document.createElement("tr");

            // Sr. No
            const tdSrNo = document.createElement("td");
            tdSrNo.textContent = index + 1;
            tr.appendChild(tdSrNo);

            // Name
            const tdName = document.createElement("td");
            tdName.textContent = item.product_name || "N/A";
            tr.appendChild(tdName);

            // Sensor (hard-coded or from item if available)
            const tdSensor = document.createElement("td");
            // If you have item.sensor, replace "product.one" with item.sensor
            tdSensor.textContent = "product.one";
            tr.appendChild(tdSensor);

            // Price
            const tdPrice = document.createElement("td");
            tdPrice.textContent = "$" + (item.product_price || "0.00");
            tr.appendChild(tdPrice);

            tableBody.appendChild(tr);

            // Accumulate total price
            if (item.product_price) {
                totalPrice += parseFloat(item.product_price);
            }
        });

        // Show total price (or use data[0].total if each set has a single total)
        totalPriceCell.textContent = "$" + totalPrice.toFixed(2);
    }

    function drawCanvas(data) {    
        const canvas = document.getElementById("estimationCanvas");
        const ctx = canvas.getContext("2d");

        if (!data || data.length === 0) return;

        // Load the background image from the first item (adjust as needed)
        const backgroundImage = new Image();
        backgroundImage.src = `{{ asset('${data[0].image}') }}`;
        backgroundImage.onload = function() {
            // Set canvas size to the image size (you can adjust to fit container)
            canvas.width = backgroundImage.width;
            canvas.height = backgroundImage.height;
            ctx.drawImage(backgroundImage, 0, 0);

            // Now place dots
            dots = [];
            data.forEach((item, index) => {
                // Convert the stored x_position / y_position to float
                // If they are percentages, you may need: 
                //   let x = parseFloat(item.x_position) * canvas.width / 100;
                //   let y = parseFloat(item.y_position) * canvas.height / 100;
                let x = parseFloat(item.x_position);
                let y = parseFloat(item.y_position);

                // Save dot info for click detection
                dots.push({
                    x: x,
                    y: y,
                    radius: 5,
                    data: item
                });

                // Draw the dot
                ctx.beginPath();
                ctx.arc(x, y, 5, 0, 2 * Math.PI);
                ctx.fillStyle = "red";
                ctx.fill();
            });
        };

        // Add event listener for clicks
        canvas.addEventListener("click", function(e) {
            const rect = canvas.getBoundingClientRect();
            // Mouse position in canvas coordinates
            const mouseX = e.clientX - rect.left;
            const mouseY = e.clientY - rect.top;

            // Check if user clicked any dot
            dots.forEach(dot => {
                const dx = mouseX - dot.x;
                const dy = mouseY - dot.y;
                const distance = Math.sqrt(dx * dx + dy * dy);
                if (distance <= dot.radius) {
                    // Show the Bootstrap modal with this dot's info
                    showDotInfo(dot.data);
                }
            });
        });
    }

    function showDotInfo(dotData) {
        const modalBody = document.getElementById("dotInfoContent");
        modalBody.innerHTML = `
            <p><strong>Name:</strong> ${dotData.product_name}</p>
            <p><strong>Sensor:</strong> product.one</p>
            <p><strong>Price:</strong> $${dotData.product_price}</p>
        `;

        // Show the modal (Bootstrap 5)
        const dotInfoModal = new bootstrap.Modal(document.getElementById("dotInfoModal"), {});
        dotInfoModal.show();
    }
</script>