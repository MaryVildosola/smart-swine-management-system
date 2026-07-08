@extends('layouts.master')
@section('contents')
<style>
.qr-wrap {
    padding: 32px;
}
.page-title { font-size: 1.6rem; font-weight: 700; color: #111827; margin-bottom: 8px; }
.page-subtitle { color: #6b7280; font-size: 0.95rem; margin-bottom: 24px; }
.grid-three { display: grid; grid-template-columns: 1fr 1fr 1.1fr; gap: 28px; }
.panel { background: #ffffff; border: 1px solid #e5e7eb; border-radius: 20px; padding: 26px; }
.panel-title { font-size: 1.1rem; font-weight: 700; color: #111827; margin-bottom: 6px; }
.panel-subtitle { color: #6b7280; font-size: 0.92rem; margin-bottom: 18px; }
.form-row { margin-bottom: 18px; }
.form-row label { display: block; color: #374151; font-size: 0.92rem; margin-bottom: 8px; }
.form-row input, .form-row select { width: 100%; border: 1px solid #d1d5db; border-radius: 14px; padding: 12px 14px; font-size: 0.95rem; }
.btn-secondary, .btn-primary { display: inline-flex; align-items: center; gap: 10px; padding: 12px 18px; border-radius: 14px; border: none; cursor: pointer; transition: all 0.2s ease; font-weight: 600; }
.btn-secondary { background: #e2e8f0; color: #1f2937; }
.btn-secondary:hover { background: #cbd5e1; }
.btn-primary { background: #22c55e; color: #fff; }
.btn-primary:hover { background: #16a34a; }
.preview-card { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 18px; height: 100%; text-align: center; }
.preview-box { width: 100%; max-width: 320px; background: #f8fafc; border-radius: 24px; border: 1px solid #e5e7eb; padding: 22px; }
.preview-title { color: #111827; font-size: 0.95rem; font-weight: 700; margin-bottom: 12px; }
.preview-text { color: #475569; font-size: 0.92rem; line-height: 1.5; }
.preview-image { width: 100%; max-width: 240px; margin: 0 auto 10px; }
.preview-label { color: #111827; font-size: 1rem; font-weight: 700; }
.helper-note { margin-top: 20px; color: #475569; font-size: 0.92rem; }
@media (max-width: 1024px) { .grid-three { grid-template-columns: 1fr; } }
</style>

<div class="qr-wrap">
    <div class="page-header">
        <h1 class="page-title">QR Code Label Generator</h1>
        <p class="page-subtitle">Generate printable pen or pig tags with one click. Download as PNG or PDF for fast printing.</p>
    </div>

    <div class="grid-three">
        <section class="panel">
            <div class="panel-title">Generate Pen QR</div>
            <div class="panel-subtitle">Create a QR label linked to an existing pen.</div>

            <div class="form-row">
                <label for="pen_id">Select Pen</label>
                <select id="pen_id">
                    @foreach($pens as $pen)
                        <option value="{{ $pen->id }}">{{ $pen->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-row">
                <label for="pen_note">Label Note</label>
                <input id="pen_note" type="text" placeholder="Example: Feed Station A1">
            </div>
            <button type="button" class="btn-primary" id="generate-pen">Generate Pen QR</button>
        </section>

        <section class="panel">
            <div class="panel-title">New Pig Tag</div>
            <div class="panel-subtitle">Generate a tag for a pig with a unique QR payload.</div>

            <div class="form-row">
                <label for="pig_tag_select">Select Animal</label>
                <select id="pig_tag_select">
                    @foreach($pigs as $pig)
                        <option value="{{ $pig->tag }}" data-pen="{{ $pig->pen->name }}" data-pen-id="{{ $pig->pen_id }}">
                            {{ $pig->tag }} ({{ $pig->pen->name }})
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="button" class="btn-primary" id="generate-pig">Generate Pig Tag</button>
        </section>

        <section class="panel preview-card">
            <div class="preview-box" id="qr-label-preview">
                <div class="preview-title">Label Preview</div>
                <canvas id="qr-canvas" class="preview-image"></canvas>
                <div class="preview-label" id="label-type">Select a generation option</div>
                <div class="preview-text" id="label-text">The QR will be ready here for download or printing.</div>
            </div>

            <button type="button" class="btn-secondary" id="download-image">Download PNG</button>
            <button type="button" class="btn-primary" id="download-pdf">Download PDF</button>

            <p class="helper-note">Use the preview to make sure the pen or pig label contains the right identifier before printing.</p>
        </section>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/qrious@4.0.2/dist/qrious.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
<script>
    // Initialize QRious
    let qr;
    try {
        qr = new QRious({
            element: document.getElementById('qr-canvas'),
            size: 260,
            value: 'PORCI-QR-INITIAL',
            background: 'white',
            foreground: '#111827',
            level: 'H' // High error correction for better scanning on physical tags
        });
    } catch (e) {
        console.error("QRious failed to initialize", e);
    }

    const labelType = document.getElementById('label-type');
    const labelText = document.getElementById('label-text');
    const penSelect = document.getElementById('pen_id');
    const pigTag = document.getElementById('pig_tag');
    const pigPenSelect = document.getElementById('pig_pen_id');

    function updatePreview(type, text, payload) {
        if (!qr) return;
        qr.value = payload;
        labelType.textContent = type;
        labelText.textContent = text;
        
        // Add a subtle "pop" animation to the preview box
        const previewBox = document.getElementById('qr-label-preview');
        previewBox.style.transform = 'scale(1.02)';
        previewBox.style.transition = 'transform 0.2s ease';
        setTimeout(() => previewBox.style.transform = 'scale(1)', 200);
    }

    // Generate initial preview if pens exist
    window.addEventListener('load', () => {
        if (penSelect && penSelect.options.length > 0) {
            const penName = penSelect.options[0].text;
            const payload = JSON.stringify({ type: 'pen', id: penSelect.value, name: penName });
            updatePreview('Pen QR Label', penName, payload);
        }
    });

    document.getElementById('generate-pen').addEventListener('click', () => {
        if (!penSelect.value) {
            Swal.fire('Error', 'Please select a pen first', 'error');
            return;
        }
        const penName = penSelect.options[penSelect.selectedIndex].text;
        const note = document.getElementById('pen_note').value.trim();
        const payload = JSON.stringify({ type: 'pen', id: penSelect.value, name: penName, note: note });
        updatePreview('Pen QR Label', `${penName}${note ? ' · ' + note : ''}`.trim(), payload);
    });

    document.getElementById('generate-pig').addEventListener('click', () => {
        const pigSelect = document.getElementById('pig_tag_select');
        const selectedOption = pigSelect.options[pigSelect.selectedIndex];
        
        if (!selectedOption) {
            Swal.fire('Error', 'Please select an animal first', 'error');
            return;
        }

        const tagValue = selectedOption.value;
        const penName = selectedOption.getAttribute('data-pen');
        const penId = selectedOption.getAttribute('data-pen-id');
        
        const payload = JSON.stringify({ 
            type: 'pig', 
            tag: tagValue, 
            pen_id: penId, 
            pen_name: penName 
        });
        
        updatePreview('Pig Tag', `${tagValue} — ${penName}`, payload);
    });

    document.getElementById('download-image').addEventListener('click', () => {
        const canvas = document.getElementById('qr-canvas');
        if (qr.value === 'PORCI-QR-INITIAL') {
            Swal.fire('Notice', 'Please generate a specific pen or pig QR first', 'info');
            return;
        }
        const link = document.createElement('a');
        link.href = canvas.toDataURL('image/png');
        link.download = `porci-qr-${labelType.textContent.toLowerCase().replace(/\s+/g, '-')}.png`;
        link.click();
    });

    document.getElementById('download-pdf').addEventListener('click', async () => {
        if (qr.value === 'PORCI-QR-INITIAL') {
            Swal.fire('Notice', 'Please generate a specific pen or pig QR first', 'info');
            return;
        }
        
        try {
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF({ orientation: 'portrait', unit: 'px', format: [400, 520] });
            const dataUrl = document.getElementById('qr-canvas').toDataURL('image/png');

            pdf.setFillColor(255, 255, 255);
            pdf.rect(0, 0, 400, 520, 'F');
            
            pdf.setTextColor(17, 24, 39);
            pdf.setFontSize(22);
            pdf.setFont("helvetica", "bold");
            pdf.text("PORCITRACK", 200, 40, { align: 'center' });
            
            pdf.setFontSize(16);
            pdf.setFont("helvetica", "normal");
            pdf.text(labelType.textContent, 200, 65, { align: 'center' });
            
            // Draw QR
            pdf.addImage(dataUrl, 'PNG', 50, 90, 300, 300);
            
            // Draw Label
            pdf.setFontSize(18);
            pdf.setFont("helvetica", "bold");
            const splitText = pdf.splitTextToSize(labelText.textContent, 360);
            pdf.text(splitText, 200, 420, { align: 'center' });
            
            pdf.setFontSize(10);
            pdf.setTextColor(107, 114, 128);
            pdf.text("Generated via Porcitrack Farm Management", 200, 490, { align: 'center' });
            
            pdf.save(`porci-label-${Date.now()}.pdf`);
        } catch (e) {
            console.error("PDF Generation failed", e);
            Swal.fire('Error', 'Failed to generate PDF. Please try PNG download instead.', 'error');
        }
    });
</script>
@endsection
