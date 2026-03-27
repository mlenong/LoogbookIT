<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Tanda Tangan Elektronik</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f0f2f5; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 15px; }
        .sign-container { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); width: 100%; max-width: 500px; text-align: center; }
        canvas.signature-pad { border: 2px dashed #0d6efd; border-radius: 8px; width: 100%; height: 60vh; touch-action: none; margin-bottom: 15px; background: #fafafa; }
    </style>
</head>
<body>

<div class="sign-container" id="sign-box">
    <h5 class="fw-bold mb-1 text-primary"><i class="fas fa-file-signature"></i> Tanda Tangan Digital</h5>
    <p class="text-muted small mb-3">Unit: <strong>{{ $log->unit }}</strong></p>

    <canvas id="signature-pad" class="signature-pad"></canvas>

    <div class="d-flex justify-content-between">
        <button class="btn btn-outline-danger" id="clear"><i class="fas fa-eraser"></i> Ulang</button>
        <button class="btn btn-primary px-4" id="save"><i class="fas fa-save"></i> Simpan TTD</button>
    </div>
</div>

<div class="sign-container" id="success-box" style="display: none;">
    <div class="text-success mb-3" style="font-size: 5rem;"><i class="fas fa-check-circle"></i></div>
    <h4 class="fw-bold text-success">Berhasil!</h4>
    <p class="text-muted">Tanda tangan telah tersimpan. Layar ini dapat ditutup.</p>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Signature Pad -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // Init canvas
        var canvas = document.getElementById('signature-pad');
        var signaturePad = new SignaturePad(canvas, { backgroundColor: 'rgb(250, 250, 250)' });

        // Responsive resizing
        function resizeCanvas() {
            var ratio =  Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear();
        }
        window.addEventListener("resize", resizeCanvas);
        resizeCanvas();

        $('#clear').click(function() {
            signaturePad.clear();
        });

        $('#save').click(function() {
            if(signaturePad.isEmpty()) {
                Swal.fire({icon: 'warning', title: 'Perhatian', text: 'Tanda tangan tidak boleh kosong!'});
                return;
            }

            let dataUrl = signaturePad.toDataURL('image/png');
            let btn = $(this);
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

            $.ajax({
                url: "{{ route('lookbook.sign.save', $log->id) }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    ttd: dataUrl
                },
                success: function(res) {
                    $('#sign-box').hide();
                    $('#success-box').fadeIn();
                },
                error: function() {
                    btn.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan TTD');
                    Swal.fire({icon: 'error', title: 'Gagal Menyimpan'});
                }
            });
        });
    });
</script>
</body>
</html>
