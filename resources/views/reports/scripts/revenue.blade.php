<script>
    let estimatedCostMap = {};

    // Show PIN modal on page load
    document.addEventListener('DOMContentLoaded', function() {
        const pinModal = new bootstrap.Modal(document.getElementById('pinModal'));
        pinModal.show();
    });

    function verifyPin() {
        const pin = document.getElementById('pinInput').value;

        if (!pin) {
            showPinError('Please enter PIN');
            return;
        }

        fetch('{{ route("reports.verifyPin") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ pin: pin })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('pinModal')).hide();
                document.getElementById('revenueContent').classList.remove('d-none');
                loadRevenueData();
            } else {
                showPinError(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showPinError('An error occurred');
        });
    }

    function showPinError(message) {
        const errorDiv = document.getElementById('pinError');
        errorDiv.textContent = message;
        errorDiv.classList.remove('d-none');
        setTimeout(() => {
            errorDiv.classList.add('d-none');
        }, 3000);
    }

    function loadRevenueData() {
        fetch('{{ route("reports.getData") }}', {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('todayProfit').textContent = data.todayProfit.toFixed(2);
                document.getElementById('weeklyProfit').textContent = data.weeklyProfit.toFixed(2);
                document.getElementById('monthlyProfit').textContent = data.monthlyProfit.toFixed(2);
                document.getElementById('yearlyProfit').textContent = data.yearlyProfit.toFixed(2);

                populateTable(data.profitDetails, data.deliveredJobs);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function populateTable(profitDetails, deliveredJobs) {
        const tableBody = document.getElementById('profitTableBody');
        tableBody.innerHTML = '';

        // Create map of jobsheet IDs with profit data
        const profitMap = {};
        profitDetails.forEach(profit => {
            profitMap[profit.jobsheet_id] = profit;
        });

        // Create map of estimated costs
        deliveredJobs.forEach(job => {
            estimatedCostMap[job.id] = job.estimated_cost;
        });

        deliveredJobs.forEach(job => {
            const profit = profitMap[job.id] || {
                service_charge: 0,
                spare_parts_charge: 0,
                other_charge: 0,
                profit: 0
            };

            const totalCharges = parseFloat(profit.service_charge) + parseFloat(profit.spare_parts_charge) + parseFloat(profit.other_charge);

            const row = `
                <tr>
                    <td><strong>${job.jobsheet_id}</strong></td>
                    <td>${job.customer.full_name}</td>
                    <td>₹${parseFloat(job.estimated_cost).toFixed(2)}</td>
                    <td>${job.problem_description.substring(0, 30)}...</td>
                    <td>₹${parseFloat(profit.service_charge).toFixed(2)}</td>
                    <td>₹${parseFloat(profit.spare_parts_charge).toFixed(2)}</td>
                    <td>₹${parseFloat(profit.other_charge).toFixed(2)}</td>
                    <td><strong class="text-success">₹${(parseFloat(job.estimated_cost) - totalCharges).toFixed(2)}</strong></td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-soft-primary" onclick="editProfit(${job.id}, '${job.jobsheet_id}', ${profit.service_charge}, ${profit.spare_parts_charge}, ${profit.other_charge})">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });
    }

    function editProfit(jobsheetId, jobsheetIdDisplay, serviceCharge, sparePartsCharge, otherCharge) {
        document.getElementById('editJobsheetId').value = jobsheetId;
        document.getElementById('editJobsheetIdDisplay').value = jobsheetIdDisplay;
        document.getElementById('editServiceCharge').value = serviceCharge;
        document.getElementById('editSparePartsCharge').value = sparePartsCharge;
        document.getElementById('editOtherCharge').value = otherCharge;

        calculateProfit();

        const modal = new bootstrap.Modal(document.getElementById('editProfitModal'));
        modal.show();
    }

    function calculateProfit() {
        const serviceCharge = parseFloat(document.getElementById('editServiceCharge').value) || 0;
        const sparePartsCharge = parseFloat(document.getElementById('editSparePartsCharge').value) || 0;
        const otherCharge = parseFloat(document.getElementById('editOtherCharge').value) || 0;
        const jobsheetId = document.getElementById('editJobsheetId').value;
        const estimatedCost = estimatedCostMap[jobsheetId] || 0;

        const totalCharges = serviceCharge + sparePartsCharge + otherCharge;
        const profit = estimatedCost - totalCharges;

        document.getElementById('totalCharges').textContent = totalCharges.toFixed(2);
        document.getElementById('calculatedProfit').textContent = profit.toFixed(2);
    }

    // Recalculate on input change
    document.getElementById('editServiceCharge')?.addEventListener('input', calculateProfit);
    document.getElementById('editSparePartsCharge')?.addEventListener('input', calculateProfit);
    document.getElementById('editOtherCharge')?.addEventListener('input', calculateProfit);

    function saveProfitData() {
        const jobsheetId = document.getElementById('editJobsheetId').value;
        const serviceCharge = parseFloat(document.getElementById('editServiceCharge').value) || 0;
        const sparePartsCharge = parseFloat(document.getElementById('editSparePartsCharge').value) || 0;
        const otherCharge = parseFloat(document.getElementById('editOtherCharge').value) || 0;

        fetch('{{ route("reports.updateProfit") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                jobsheet_id: jobsheetId,
                service_charge: serviceCharge,
                spare_parts_charge: sparePartsCharge,
                other_charge: otherCharge
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
               
                bootstrap.Modal.getInstance(document.getElementById('editProfitModal')).hide();
                loadRevenueData();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>