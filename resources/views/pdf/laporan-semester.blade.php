<!doctype html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <title>Laporan Perkembangan Semester</title>
        <style>
            @font-face {
                font-family: 'Inter';
                font-style: normal;
                font-weight: 400;
                src: url('{{ public_path('fonts/Inter-Regular.ttf') }}') format('truetype');
            }
            @font-face {
                font-family: 'Inter';
                font-style: normal;
                font-weight: 600;
                src: url('{{ public_path('fonts/Inter-SemiBold.ttf') }}') format('truetype');
            }
            @page { margin: 26px 28px; }
            body { font-family: 'Inter', DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #111827; }
            h1 { font-size: 18px; margin: 0 0 14px 0; text-align: center; letter-spacing: 0.2px; font-weight: 600; }
            h2 { font-size: 13px; margin: 0; font-weight: 600; }
            .muted { color: #6b7280; }
            .section { border: 1px solid #e5e7eb; margin-bottom: 12px; border-radius: 6px; }
            .section-body { padding: 10px 12px; }
            .section-title { background: #f9fafb; border-bottom: 1px solid #e5e7eb; padding: 8px 12px; }
            .kv-table { width: 100%; border-collapse: collapse; }
            .kv-table td { padding: 6px 6px; vertical-align: top; width: 50%; }
            .label { color: #6b7280; font-size: 10px; text-transform: uppercase; letter-spacing: 0.3px; }
            .value { font-size: 12px; font-weight: 600; }
            .badge { display: inline-block; padding: 2px 8px; border-radius: 999px; font-size: 10px; font-weight: 600; }
            .badge-success { background: #dcfce7; color: #166534; }
            .badge-warning { background: #ffedd5; color: #9a3412; }
            .badge-danger { background: #fee2e2; color: #991b1b; }
            .badge-info { background: #dbeafe; color: #1d4ed8; }
            .paragraph { text-align: justify; line-height: 1.7; margin: 6px 0; text-indent: 2em; }
            .paragraph-left { text-align: left; }
            .no-indent { text-indent: 0; }
            .indicator { border-left: 3px solid #e5e7eb; padding-left: 8px; margin-bottom: 12px; }
            .indicator-title { font-weight: 600; margin-bottom: 4px; }
            .photo-section { margin-top: 8px; }
            .photo-note { font-size: 11px; color: #111827; margin-bottom: 6px; }
            .photo-empty { font-size: 11px; color: #6b7280; }
            .photo-wrap { text-align: center; margin-top: 6px; }
            .photo-box { display: inline-block; width: 100%; max-width: 420px; background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 8px; padding: 8px; }
            .photo-img { display: block; width: 100%; max-height: 260px; object-fit: contain; border-radius: 6px; }
        </style>
    </head>
    <body>
        <h1>Laporan Perkembangan Semester</h1>
        <div style="text-align: right; font-size: 11px; color: #6b7280; margin-bottom: 12px;">
            Nomor Laporan: <strong style="color: #111827;">{{ $report['nomor_laporan'] }}</strong><br>
            <span style="font-size: 10px;">Nomor laporan ini dihasilkan secara otomatis oleh sistem.</span>
        </div>

        <div class="section">
            <div class="section-title"><h2>Informasi Laporan</h2></div>
            <div class="section-body">
                <table class="kv-table">
                    <tr>
                        <td>
                            <div class="label">Nama Anak</div>
                            <div class="value">{{ $report['siswa']['nama'] }}</div>
                        </td>
                        <td>
                            <div class="label">NISN</div>
                            <div class="value">{{ $report['siswa']['nis'] }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="label">Kelas</div>
                            <div class="value">{{ $report['siswa']['kelas'] }}</div>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="label">Nama Guru Kelas</div>
                            <div class="value">{{ $report['siswa']['guru_kelas'] }}</div>
                        </td>
                        <td>
                            <div class="label">Nama Wali Murid</div>
                            <div class="value">{{ $report['siswa']['wali'] }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="label">Tahun Ajaran</div>
                            <div class="value">{{ $report['tahun_ajaran'] }}</div>
                        </td>
                        <td>
                            <div class="label">Tanggal Cetak</div>
                            <div class="value">{{ $report['tanggal_cetak'] }}</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="section">
            <div class="section-title"><h2>Rekap Presensi Semester</h2></div>
            <div class="section-body">
                <table class="kv-table">
                    <tr>
                        <td><span class="badge badge-success">Hadir: {{ $report['presensi']['hadir'] }}</span></td>
                        <td><span class="badge badge-danger">Alfa: {{ $report['presensi']['alfa'] }}</span></td>
                    </tr>
                    <tr>
                        <td><span class="badge badge-warning">Izin: {{ $report['presensi']['izin'] }}</span></td>
                        <td><span class="badge badge-info">Sakit: {{ $report['presensi']['sakit'] }}</span></td>
                    </tr>
                    <tr>
                        <td colspan="2">Persentase Hadir: {{ $report['presensi']['persentase_hadir'] }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="section">
            <div class="section-title"><h2>Ringkasan Perkembangan Fisik Semester</h2></div>
            <div class="section-body">
                <div class="paragraph no-indent"><strong>Status Terakhir:</strong> {{ $report['fisik']['status'] }}</div>
                <table class="kv-table">
                    <tr>
                        <td>
                            <div class="label">Tanggal Terakhir</div>
                            <div class="value">{{ $report['fisik']['tanggal'] }}</div>
                        </td>
                        <td>
                            <div class="label">TB Terakhir</div>
                            <div class="value">{{ $report['fisik']['tb'] }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="label">BB Terakhir</div>
                            <div class="value">{{ $report['fisik']['bb'] }}</div>
                        </td>
                        <td>
                            <div class="label">LK Terakhir</div>
                            <div class="value">{{ $report['fisik']['lk'] }}</div>
                        </td>
                    </tr>
                </table>
                <p class="paragraph paragraph-left no-indent">{{ $report['fisik']['rekomendasi'] }}</p>
                <p class="muted">Catatan: Rekomendasi ini merupakan hasil pengolahan sistem dan digunakan sebagai bahan pertimbangan pendukung.</p>
            </div>
        </div>

        <div class="section">
            <div class="section-title"><h2>Ringkasan Perkembangan Kognitif Semester</h2></div>
            <div class="section-body">
                @foreach ($report['kognitif'] as $indikator)
                    <div class="indicator">
                        <div class="indicator-title">{{ $indikator['label'] }}</div>
                        @php
                            $record = $indikator['record'];
                        @endphp
                        @if ($record)
                            @php
                                $paragraphs = preg_split("/\r?\n\r?\n/", trim($record->narasi));
                            @endphp
                            @foreach ($paragraphs as $paragraph)
                                @if (trim($paragraph) !== '')
                                    <p class="paragraph">{!! nl2br(e(trim($paragraph))) !!}</p>
                                @endif
                            @endforeach
                            @php
                                $fotoPath = trim((string) ($record->foto ?? ''));
                                $fotoDataUri = '';

                                if ($fotoPath !== '') {
                                    if (str_starts_with($fotoPath, 'http://') || str_starts_with($fotoPath, 'https://')) {
                                        try {
                                            $contents = file_get_contents($fotoPath);
                                        } catch (\Throwable $e) {
                                            $contents = null;
                                        }

                                        if ($contents) {
                                            $mime = (new finfo(FILEINFO_MIME_TYPE))->buffer($contents) ?: 'image/jpeg';
                                            $fotoDataUri = 'data:' . $mime . ';base64,' . base64_encode($contents);
                                        } else {
                                            $fotoDataUri = $fotoPath;
                                        }
                                    } else {
                                        $diskName = config('filesystems.default', 'local');
                                        $disk = \Illuminate\Support\Facades\Storage::disk($diskName);

                                        if ($disk->exists($fotoPath)) {
                                            $absolutePath = $disk->path($fotoPath);
                                        } elseif (str_starts_with($fotoPath, '/')) {
                                            $absolutePath = public_path(ltrim($fotoPath, '/'));
                                        } else {
                                            $absolutePath = public_path($fotoPath);
                                        }

                                        if (isset($absolutePath) && is_file($absolutePath)) {
                                            $contents = file_get_contents($absolutePath);
                                            if ($contents !== false) {
                                                $mime = (new finfo(FILEINFO_MIME_TYPE))->file($absolutePath) ?: 'image/jpeg';
                                                $fotoDataUri = 'data:' . $mime . ';base64,' . base64_encode($contents);
                                            }
                                        }
                                    }
                                }
                            @endphp
                            <div class="photo-section">
                                <div class="photo-note">Dokumentasi perkembangan untuk indikator ini.</div>
                                @if ($fotoDataUri !== '')
                                    <div class="photo-wrap">
                                        <div class="photo-box">
                                            <img class="photo-img" src="{{ $fotoDataUri }}" alt="Foto perkembangan {{ $indikator['label'] }}">
                                        </div>
                                    </div>
                                @else
                                    <div class="photo-empty">Belum ada foto yang diunggah.</div>
                                @endif
                            </div>
                        @else
                            <p class="muted">Belum ada catatan pada periode ini.</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="section">
            <div class="section-title"><h2>Penutup</h2></div>
            <div class="section-body">
                <p class="paragraph">
                    Laporan ini disusun secara otomatis oleh sistem berdasarkan catatan perkembangan anak yang diinput oleh guru
                    selama satu semester dan digunakan sebagai bahan informasi bagi wali murid.
                </p>
            </div>
        </div>
    </body>
</html>
