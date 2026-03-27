@extends('layouts.admin')

@section('title', 'Data Lookbook IT | Lookbook')
@section('page_title', 'Data Aktivitas IT')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('lookbook.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Data Lookbook</li>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .dynamic-section {
            display: none;
        }

        .signature-img {
            max-height: 40px;
        }

        .evidence-img {
            max-height: 40px;
            cursor: pointer;
            border-radius: 4px;
        }

        .qr-container {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            display: inline-block;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary">
                <div class="card-header border-bottom-0 pb-0">
                    <h3 class="card-title mt-2"><i class="fas fa-list mr-2"></i>Daftar Aktivitas Perbaikan IT</h3>
                </div>

                <!-- Toolbar Filter + Tombol Aksi -->
                <div class="card-body bg-light border-top border-bottom py-2 mb-3">
                    <form action="{{ route('lookbook.data') }}" method="GET" class="form-inline flex-wrap">
                        <label class="mr-2 text-sm text-secondary"><i class="fas fa-filter mr-1"></i> Tanggal:</label>
                        <input type="date" name="start_date" class="form-control form-control-sm mr-2 mb-1"
                            value="{{ request('start_date') }}">
                        <span class="mr-2 mb-1">-</span>
                        <input type="date" name="end_date" class="form-control form-control-sm mr-2 mb-1"
                            value="{{ request('end_date') }}">
                        <button type="submit" class="btn btn-sm btn-secondary mr-2 mb-1"><i class="fas fa-search"></i>
                            Filter</button>
                        @if(request()->filled('start_date'))
                            <a href="{{ route('lookbook.data') }}" class="btn btn-sm btn-outline-danger mr-2 mb-1"><i
                                    class="fas fa-times"></i> Reset</a>
                        @endif

                        <div class="ml-auto d-flex mb-1">
                            <button type="button" class="btn btn-sm btn-primary mr-2" data-toggle="modal"
                                data-target="#modal-add">
                                <i class="fas fa-plus"></i> Tambah Data
                            </button>
                            <a href="{{ route('lookbook.pdf', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                                class="btn btn-sm btn-danger" target="_blank">
                                <i class="fas fa-file-pdf"></i> Ekspor PDF
                            </a>
                        </div>
                    </form>
                </div>

                <div class="card-body pt-0">
                    <table id="lookbookTable" class="table table-bordered table-striped table-hover">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th style="width:5%">No</th>
                                <th>Tanggal</th>
                                <th>Petugas</th>
                                <th>Kategori</th>
                                <th>Item/Unit</th>
                                <th>Aktivitas</th>
                                <th>Status</th>
                                <th>Bukti Foto</th>
                                <th>TTD</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $index => $log)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                    <td><i
                                            class="fas fa-user-tie text-muted mr-1"></i>{{ $log->user ? Str::words($log->user->name, 2, '') : '-' }}
                                    </td>
                                    <td><span class="badge badge-secondary">{{ $log->kategori }}</span></td>
                                    <td>
                                        @if($log->kategori == 'Pembersihan')
                                            <strong><i class="fas fa-building text-info mr-1"></i>{{ $log->unit }}</strong>
                                        @else
                                            {{ Str::limit($log->item, 38) }}
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($log->aktivitas, 42) }}</td>
                                    <td>
                                        @if($log->status == 'Selesai') <span class="badge badge-success">Selesai</span>
                                        @elseif($log->status == 'Proses') <span class="badge badge-warning">Proses</span>
                                        @else <span class="badge badge-danger">{{ $log->status }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($log->foto)
                                            <img src="{{ Storage::url($log->foto) }}" class="evidence-img shadow-sm" alt="Bukti"
                                                onclick="showImageModal('{{ Storage::url($log->foto) }}')">
                                        @else
                                            <span class="text-muted text-sm">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($log->kategori == 'Pembersihan')
                                            @if($log->ttd)
                                                <img src="{{ $log->ttd }}" class="signature-img img-thumbnail" alt="TTD">
                                            @else
                                                <button type="button" class="btn btn-xs btn-warning btn-qr"
                                                    data-url="{{ request()->getSchemeAndHttpHost() }}/lookbook/sign/{{ $log->id }}">
                                                    <i class="fas fa-qrcode"></i> Scan TTD
                                                </button>
                                            @endif
                                        @else -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-info btn-edit m-1" data-id="{{ $log->id }}"><i
                                                class="fas fa-edit"></i></button>
                                        <button class="btn btn-sm btn-danger btn-delete m-1"
                                            data-url="{{ route('lookbook.destroy', $log->id) }}"><i
                                                class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Foto Preview -->
    <div class="modal fade" id="modal-image">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-0 text-center">
                    <img src="" id="preview-img-full" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL ADD -->
    <div class="modal fade" id="modal-add">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title"><i class="fas fa-plus-circle mr-2"></i>Tambah Data Lookbook</h4>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form action="{{ route('lookbook.store') }}" method="POST" id="form-add" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Kategori Pekerjaan <span class="text-danger">*</span></label>
                            <select name="kategori" id="add_kategori" class="custom-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Hardware">Hardware (Perangkat Keras)</option>
                                <option value="Software">Software (Perangkat Lunak)</option>
                                <option value="Pembersihan">Pembersihan</option>
                            </select>
                        </div>

                        <div id="add_section-hardware" class="dynamic-section form-group p-3 bg-light rounded border">
                            <label><i class="fas fa-desktop mr-1"></i> Checklist Komponen</label>
                            <div class="row">
                                @foreach($hardwareParts as $part)
                                    <div class="col-md-6">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input add-hw-item" type="checkbox" name="item[]"
                                                value="{{ $part }}" id="add_hw_{{ Str::slug($part) }}">
                                            <label class="custom-control-label"
                                                for="add_hw_{{ Str::slug($part) }}">{{ $part }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div id="add_section-software" class="dynamic-section form-group">
                            <label><i class="fas fa-code mr-1"></i> Kategori Aplikasi</label>
                            <select name="item" id="add_sw_item" class="custom-select" disabled>
                                <option value="">-- Pilih Aplikasi --</option>
                                <option value="RME">RME</option>
                                <option value="Billing kasir">Billing Kasir</option>
                                <option value="billing farmasi">Billing Farmasi</option>
                                <option value="esdm">ESDM</option>
                                <option value="lainya">Lainya</option>
                            </select>
                        </div>

                        <div id="add_section-pembersihan" class="dynamic-section">
                            <div class="form-group">
                                <label><i class="fas fa-building mr-1"></i> Unit / Ruangan</label>
                                <select name="unit" id="add_unit" class="form-control select2-ajax" style="width:100%;"
                                    disabled></select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Detail Aktivitas / Keterangan <span class="text-danger">*</span></label>
                            <textarea name="aktivitas" class="form-control" rows="3" required
                                placeholder="Jelaskan tindakan yang dilakukan..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Foto Bukti Pekerjaan <small class="text-muted">(Opsional)</small></label>
                                    <input type="file" name="foto" class="form-control" style="padding-bottom:35px;"
                                        accept="image/*">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status <span class="text-danger">*</span></label>
                                    <select name="status" class="custom-select" required>
                                        <option value="Proses">Proses</option>
                                        <option value="Selesai">Selesai</option>
                                        <option value="Batal">Batal</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT -->
    <div class="modal fade" id="modal-edit">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title"><i class="fas fa-edit mr-2"></i>Edit Data Lookbook</h4>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form action="" method="POST" id="form-edit" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="text-center" id="edit-loading" style="display:none;">
                            <i class="fas fa-spinner fa-spin fa-2x"></i> Memuat...
                        </div>
                        <div id="edit-content">
                            <div class="form-group">
                                <label>Kategori <span class="text-danger">*</span></label>
                                <select name="kategori" id="edit_kategori" class="custom-select" required>
                                    <option value="Hardware">Hardware</option>
                                    <option value="Software">Software</option>
                                    <option value="Pembersihan">Pembersihan</option>
                                </select>
                            </div>
                            <div id="edit_section-hardware" class="dynamic-section form-group p-3 bg-light rounded border">
                                <label><i class="fas fa-desktop mr-1"></i> Checklist Komponen</label>
                                <div class="row">
                                    @foreach($hardwareParts as $part)
                                        <div class="col-md-6">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input edit-hw-item" type="checkbox" name="item[]"
                                                    value="{{ $part }}" id="edit_hw_{{ Str::slug($part) }}">
                                                <label class="custom-control-label"
                                                    for="edit_hw_{{ Str::slug($part) }}">{{ $part }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div id="edit_section-software" class="dynamic-section form-group">
                                <label>Nama Aplikasi</label>
                                <select name="item" id="edit_sw_item" class="custom-select" disabled>
                                    <option value="">-- Pilih Aplikasi --</option>
                                    <option value="RME">RME</option>
                                    <option value="Billing kasir">Billing kasir</option>
                                    <option value="billing farmasi">billing farmasi</option>
                                    <option value="esdm">esdm</option>
                                    <option value="lainya">lainya</option>
                                </select>
                            </div>
                            <div id="edit_section-pembersihan" class="dynamic-section">
                                <div class="form-group">
                                    <label>Unit / Ruangan</label>
                                    <select name="unit" id="edit_unit" class="form-control select2-ajax" style="width:100%;"
                                        disabled></select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Detail Aktivitas <span class="text-danger">*</span></label>
                                <textarea name="aktivitas" id="edit_aktivitas" class="form-control" rows="3"
                                    required></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ganti Foto <small class="text-muted">(Opsional)</small></label>
                                        <input type="file" name="foto" class="form-control" style="padding-bottom:35px;"
                                            accept="image/*">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status <span class="text-danger">*</span></label>
                                        <select name="status" id="edit_status" class="custom-select" required>
                                            <option value="Proses">Proses</option>
                                            <option value="Selesai">Selesai</option>
                                            <option value="Batal">Batal</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-info" id="btn-update-submit"><i class="fas fa-save"></i>
                            Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL QR CODE -->
    <div class="modal fade" id="modal-qr">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h4 class="modal-title text-dark"><i class="fas fa-qrcode mr-2"></i>Scan QR TTD</h4>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body text-center">
                    <p class="text-sm text-muted mb-2">Scan QR berikut menggunakan HP Penanggung Jawab Ruangan.</p>
                    <div class="qr-container shadow-sm mb-3">
                        <img id="qr-img" src="" style="width:200px; height:200px;">
                    </div>
                    <a href="" id="qr-link-direct" target="_blank" class="d-block mt-2 font-weight-bold">
                        Buka Link Manual <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function showImageModal(src) {
            $('#preview-img-full').attr('src', src);
            $('#modal-image').modal('show');
        }

        // Global event delegation (persists across Turbo visits)
        $(document).on('click', '.btn-edit', function () {
            let id = $(this).data('id');
            $('#form-edit').attr('action', "{{ url('/lookbook') }}/" + id);
            $('#modal-edit').modal('show');
            $('#edit-content').hide(); $('#edit-loading').show(); $('#btn-update-submit').prop('disabled', true);

            $.get("{{ url('/lookbook') }}/" + id + "/json", function (data) {
                $('#edit_kategori').val(data.kategori);
                $('#edit_aktivitas').val(data.aktivitas);
                $('#edit_status').val(data.status);
                $('.edit-hw-item').prop('checked', false);
                $('#edit_sw_item').val('');
                if (data.kategori === 'Hardware' && data.item) {
                    data.item.split(', ').forEach(p => $(`input.edit-hw-item[value="${p}"]`).prop('checked', true));
                } else if (data.kategori === 'Software') {
                    $('#edit_sw_item').val(data.item);
                } else if (data.kategori === 'Pembersihan' && data.unit) {
                    let opt = new Option(data.unit, data.unit, true, true);
                    $('#edit_unit').append(opt).trigger('change');
                }
                if (typeof handleCategoryChange === 'function') handleCategoryChange('edit');
                $('#edit-loading').hide(); $('#edit-content').fadeIn(); $('#btn-update-submit').prop('disabled', false);
            }).fail(() => { $('#modal-edit').modal('hide'); Swal.fire({ icon: 'error', title: 'Gagal mengambil data.' }); });
        });

        $(document).on('click', '.btn-delete', function () {
            let url = $(this).data('url');
            Swal.fire({
                title: 'Hapus Data ini?', icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#d33', confirmButtonText: 'Ya, Hapus!'
            }).then(res => {
                if (res.isConfirmed) {
                    $.ajax({
                        url: url, type: 'DELETE', data: { _token: '{{ csrf_token() }}' },
                        success: () => {
                            if (typeof Turbo !== 'undefined') Turbo.visit(window.location.href, { action: 'replace' });
                            else location.reload();
                        }
                    });
                }
            });
        });

        $(document).on('click', '.btn-qr', function () {
            let url = $(this).data('url');
            $('#qr-img').attr('src', 'https://quickchart.io/qr?text=' + encodeURIComponent(url) + '&size=250');
            $('#qr-link-direct').attr('href', url);
            $('#modal-qr').modal('show');
        });

        function handleCategoryChange(prefix) {
            let cat = $(`#${prefix}_kategori`).val();
            $(`[id^="${prefix}_section-"]`).hide();
            $(`.${prefix}-hw-item, #${prefix}_sw_item, #${prefix}_unit`).prop('disabled', true);
            if (cat === 'Hardware') { $(`#${prefix}_section-hardware`).fadeIn(); $(`.${prefix}-hw-item`).prop('disabled', false); }
            else if (cat === 'Software') { $(`#${prefix}_section-software`).fadeIn(); $(`#${prefix}_sw_item`).prop('disabled', false); }
            else if (cat === 'Pembersihan') { $(`#${prefix}_section-pembersihan`).fadeIn(); $(`#${prefix}_unit`).prop('disabled', false); }
        }

        $(document).on('turbo:load', function () {
            if ($('#lookbookTable').length > 0) {
                $('#lookbookTable').DataTable({
                    "responsive": true, "autoWidth": false, "destroy": true, // Critical for Turbo
                    "language": { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' }
                });

                const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                        @if(session('success')) Toast.fire({ icon: 'success', title: '{{ session('success') }}' }); @endif

                function initSelectAjax(selector, modalId) {
                    $(selector).select2({
                        theme: 'bootstrap4', dropdownParent: $(modalId), placeholder: 'Ketik nama Unit/Ruangan...',
                        ajax: {
                            url: '/api/units', dataType: 'json', delay: 250,
                            processResults: function (data) { return { results: data }; }, cache: true
                        }
                    });
                }
                initSelectAjax('#add_unit', '#modal-add');
                initSelectAjax('#edit_unit', '#modal-edit');

                $('#add_kategori').off('change').on('change', () => handleCategoryChange('add'));
                $('#edit_kategori').off('change').on('change', () => handleCategoryChange('edit'));

                $('#form-add').off('submit').on('submit', function (e) {
                    if ($('#add_kategori').val() === 'Hardware' && $('.add-hw-item:checked').length === 0) {
                        e.preventDefault();
                        Toast.fire({ icon: 'warning', title: 'Pilih minimal 1 komponen hardware!' });
                    }
                });
            }
        });
    </script>
@endsection