<script>
// Pattern Lock Variables
let patternCanvas = null;
let patternCtx = null;
let dots = [];
let selectedDots = [];
let isDrawing = false;
let currentX = 0;
let currentY = 0;

// Initialize pattern canvas
window.addEventListener('DOMContentLoaded', function() {
    initializePatternCanvas();
});

function initializePatternCanvas() {
    patternCanvas = document.getElementById('patternCanvas');
    if (!patternCanvas) return;
    
    patternCtx = patternCanvas.getContext('2d');
    const spacing = 100, offsetX = 50, offsetY = 50;
    
    dots = [];
    for (let row = 0; row < 3; row++) {
        for (let col = 0; col < 3; col++) {
            dots.push({ x: offsetX + col * spacing, y: offsetY + row * spacing, index: row * 3 + col });
        }
    }
    
    drawPattern();
    
    patternCanvas.addEventListener('mousedown', startDrawing);
    patternCanvas.addEventListener('mousemove', draw);
    patternCanvas.addEventListener('mouseup', stopDrawing);
    patternCanvas.addEventListener('mouseleave', stopDrawing);
    patternCanvas.addEventListener('touchstart', handleTouchStart);
    patternCanvas.addEventListener('touchmove', handleTouchMove);
    patternCanvas.addEventListener('touchend', stopDrawing);
}

function drawPattern() {
    if (!patternCtx) return;
    patternCtx.clearRect(0, 0, patternCanvas.width, patternCanvas.height);
    
    if (selectedDots.length > 0) {
        patternCtx.strokeStyle = '#4e73df';
        patternCtx.lineWidth = 3;
        patternCtx.lineCap = 'round';
        patternCtx.beginPath();
        patternCtx.moveTo(selectedDots[0].x, selectedDots[0].y);
        for (let i = 1; i < selectedDots.length; i++) {
            patternCtx.lineTo(selectedDots[i].x, selectedDots[i].y);
        }
        if (isDrawing) patternCtx.lineTo(currentX, currentY);
        patternCtx.stroke();
    }
    
    dots.forEach((dot) => {
        const isSelected = selectedDots.some(d => d.index === dot.index);
        patternCtx.beginPath();
        patternCtx.arc(dot.x, dot.y, 15, 0, Math.PI * 2);
        patternCtx.fillStyle = isSelected ? '#4e73df' : '#e3e6f0';
        patternCtx.fill();
        patternCtx.strokeStyle = isSelected ? '#2e59d9' : '#d1d3e2';
        patternCtx.lineWidth = 2;
        patternCtx.stroke();
        
        if (isSelected) {
            const order = selectedDots.findIndex(d => d.index === dot.index) + 1;
            patternCtx.fillStyle = '#ffffff';
            patternCtx.font = 'bold 12px Arial';
            patternCtx.textAlign = 'center';
            patternCtx.textBaseline = 'middle';
            patternCtx.fillText(order, dot.x, dot.y);
        }
    });
}

function getMousePos(e) {
    const rect = patternCanvas.getBoundingClientRect();
    return { x: e.clientX - rect.left, y: e.clientY - rect.top };
}

function getTouchPos(e) {
    const rect = patternCanvas.getBoundingClientRect();
    return { x: e.touches[0].clientX - rect.left, y: e.touches[0].clientY - rect.top };
}

function startDrawing(e) {
    isDrawing = true;
    const pos = getMousePos(e);
    checkDotSelection(pos.x, pos.y);
}

function handleTouchStart(e) {
    e.preventDefault();
    isDrawing = true;
    const pos = getTouchPos(e);
    checkDotSelection(pos.x, pos.y);
}

function draw(e) {
    if (!isDrawing) return;
    const pos = getMousePos(e);
    currentX = pos.x;
    currentY = pos.y;
    checkDotSelection(pos.x, pos.y);
    drawPattern();
}

function handleTouchMove(e) {
    e.preventDefault();
    if (!isDrawing) return;
    const pos = getTouchPos(e);
    currentX = pos.x;
    currentY = pos.y;
    checkDotSelection(pos.x, pos.y);
    drawPattern();
}

function stopDrawing() {
    isDrawing = false;
    drawPattern();
}

function checkDotSelection(x, y) {
    dots.forEach(dot => {
        const distance = Math.sqrt(Math.pow(x - dot.x, 2) + Math.pow(y - dot.y, 2));
        if (distance < 20 && !selectedDots.some(d => d.index === dot.index)) {
            selectedDots.push(dot);
            drawPattern();
        }
    });
}

function resetPattern() {
    selectedDots = [];
    isDrawing = false;
    drawPattern();
    const preview = document.getElementById('patternPreview');
    preview.innerHTML = '<p class="text-muted mb-0">No pattern captured yet</p>';
    document.getElementById('pattern_image').value = '';
}

function capturePattern() {
    if (selectedDots.length < 4) {
        alert('Please draw a pattern with at least 4 dots');
        return;
    }
    const imageData = patternCanvas.toDataURL('image/png');
    document.getElementById('pattern_image').value = imageData;
    const preview = document.getElementById('patternPreview');
    preview.innerHTML = `<img src="${imageData}" style="max-width: 100%; border-radius: 8px; border: 2px solid #4e73df;">`;
    const patternSequence = selectedDots.map((d, i) => i + 1).join(' → ');
    //alert(`Pattern captured successfully!\nSequence: ${patternSequence}`);
    
}
</script>
