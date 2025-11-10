<script>
// Form Submission and Success Modal
document.getElementById('jobsheet-wizard').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const customerName = document.getElementById('displayCustomerName').textContent || 'John Doe';
    const jobsheetId = 'JS' + String(Math.floor(Math.random() * 9999) + 1).padStart(4, '0');
    
    document.getElementById('success_customer_name').textContent = customerName;
    document.getElementById('success_jobsheet_id').textContent = jobsheetId;
    
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    successModal.show();
});

function printLabel() {
    const customerName = document.getElementById('success_customer_name').textContent;
    const jobsheetId = document.getElementById('success_jobsheet_id').textContent;
    
    const printWindow = window.open('', '', 'width=400,height=300');
    printWindow.document.write(`
        <html>
        <head>
            <title>Print Label</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 20px; text-align: center; }
                .label { border: 2px solid #000; padding: 20px; margin: 20px; }
                h2 { margin: 10px 0; }
                .jobsheet-id { font-size: 24px; font-weight: bold; margin: 15px 0; }
                .customer-name { font-size: 18px; margin: 10px 0; }
            </style>
        </head>
        <body>
            <div class="label">
                <h2>Mobile Repair Shop</h2>
                <div class="jobsheet-id">${jobsheetId}</div>
                <div class="customer-name">${customerName}</div>
                <div>${new Date().toLocaleDateString()}</div>
            </div>
        </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.focus();
    setTimeout(() => { printWindow.print(); printWindow.close(); }, 250);
    alert('Label printed successfully!');
}

function printJobsheet() {
    const customerName = document.getElementById('success_customer_name').textContent;
    const jobsheetId = document.getElementById('success_jobsheet_id').textContent;
    const company = document.getElementById('company').value;
    const model = document.getElementById('model').value;
    const problem = document.getElementById('problem_description').value;
    const estimatedCost = document.getElementById('estimated_cost').value;
    const advance = document.getElementById('advance').value;
    const balance = document.getElementById('balance').value;
    
    const printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.write(`
        <html>
        <head>
            <title>JobSheet - ${jobsheetId}</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 30px; }
                .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 20px; margin-bottom: 20px; }
                .section { margin-bottom: 20px; }
                .info-row { display: flex; margin-bottom: 8px; }
                .info-label { font-weight: bold; width: 150px; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Mobile Repair Shop</h1>
                <p>JobSheet ID: ${jobsheetId} | Date: ${new Date().toLocaleDateString()}</p>
            </div>
            <div class="section">
                <div class="info-row"><div class="info-label">Customer:</div><div>${customerName}</div></div>
                <div class="info-row"><div class="info-label">Company:</div><div>${company}</div></div>
                <div class="info-row"><div class="info-label">Model:</div><div>${model}</div></div>
                <div class="info-row"><div class="info-label">Problem:</div><div>${problem}</div></div>
                <div class="info-row"><div class="info-label">Estimated Cost:</div><div>₹${estimatedCost}</div></div>
                <div class="info-row"><div class="info-label">Advance:</div><div>₹${advance}</div></div>
                <div class="info-row"><div class="info-label">Balance:</div><div>₹${balance}</div></div>
            </div>
        </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.focus();
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
        const successModal = bootstrap.Modal.getInstance(document.getElementById('successModal'));
        successModal.hide();
    }, 250);
}
</script>
