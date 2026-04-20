<div class="modal-header border-bottom px-4 py-3">
  <h6 class="modal-title fw-bold">{{ $title }}</h6>
  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body px-4 py-4">
  <div class="row g-3">

    <div class="col-md-8">
      <label class="form-label fw-semibold" style="font-size:.875rem;">Judul Baris 1 <span class="text-danger">*</span></label>
      <input type="text" name="title_1" class="form-control" required value="{{ old('title_1', $slide?->title_1) }}" placeholder="Contoh: SENI UNTUK" style="border-radius:8px;" />
    </div>

    <div class="col-md-4">
      <label class="form-label fw-semibold" style="font-size:.875rem;">Warna Background</label>
      <div class="input-group">
        <input type="color" name="bg_color" class="form-control form-control-color" value="{{ old('bg_color', $slide?->bg_color ?? '#0066cc') }}" style="width:48px;border-radius:8px 0 0 8px;" />
        <input type="text" id="{{ isset($title) && str_contains($title,'Edit') ? 'edit' : 'add' }}BgText" class="form-control form-control-sm" value="{{ old('bg_color', $slide?->bg_color ?? '#0066cc') }}" readonly style="border-radius:0 8px 8px 0;font-size:.8rem;" />
      </div>
    </div>

    <div class="col-md-8">
      <label class="form-label fw-semibold" style="font-size:.875rem;">Judul Baris 2 (warna kuning) <span class="text-danger">*</span></label>
      <input type="text" name="title_2" class="form-control" required value="{{ old('title_2', $slide?->title_2) }}" placeholder="Contoh: KEHIDUPAN" style="border-radius:8px;" />
    </div>

    <div class="col-md-4">
      <label class="form-label fw-semibold" style="font-size:.875rem;">Status</label><br>
      <div class="form-check form-switch mt-2">
        <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $slide?->is_active ?? true) ? 'checked' : '' }}>
        <label class="form-check-label" style="font-size:.875rem;">Aktif ditampilkan</label>
      </div>
    </div>

    <div class="col-md-8">
      <label class="form-label fw-semibold" style="font-size:.875rem;">Label Tag</label>
      <input type="text" name="tag" class="form-control" value="{{ old('tag', $slide?->tag) }}" placeholder="Contoh: Tahun Tanah — Triennale 2025" style="border-radius:8px;" />
    </div>

    <div class="col-md-4">
      <label class="form-label fw-semibold" style="font-size:.875rem;">Warna Tag</label>
      <input type="color" name="tag_color" class="form-control form-control-color w-100" value="{{ old('tag_color', $slide?->tag_color ?? '#E55A00') }}" style="border-radius:8px;height:38px;" />
    </div>

    <div class="col-12">
      <label class="form-label fw-semibold" style="font-size:.875rem;">Deskripsi</label>
      <textarea name="description" class="form-control" rows="2" style="border-radius:8px;" placeholder="Teks deskripsi singkat di bawah judul...">{{ old('description', $slide?->description) }}</textarea>
    </div>

    <div class="col-12">
      <label class="form-label fw-semibold" style="font-size:.875rem;">Kutipan / Quote</label>
      <textarea name="quote" class="form-control" rows="2" style="border-radius:8px;" placeholder='"Seni memiliki vitalitas untuk..."'>{{ old('quote', $slide?->quote) }}</textarea>
    </div>

    <div class="col-md-6">
      <label class="form-label fw-semibold" style="font-size:.875rem;">Penulis Kutipan</label>
      <input type="text" name="author" class="form-control" value="{{ old('author', $slide?->author) }}" placeholder="— Jatiwangi Art Factory" style="border-radius:8px;" />
    </div>

    <div class="col-md-3">
      <label class="form-label fw-semibold" style="font-size:.875rem;">Teks Tombol CTA</label>
      <input type="text" name="cta_text" class="form-control" value="{{ old('cta_text', $slide?->cta_text ?? 'Dukung Sekarang') }}" style="border-radius:8px;" />
    </div>

    <div class="col-md-3">
      <label class="form-label fw-semibold" style="font-size:.875rem;">URL Tombol CTA</label>
      <input type="text" name="cta_url" class="form-control" value="{{ old('cta_url', $slide?->cta_url ?? '/pages/donasi.html') }}" style="border-radius:8px;" />
    </div>

    <div class="col-12">
      <label class="form-label fw-semibold" style="font-size:.875rem;">Foto Background <span class="text-muted fw-normal">(opsional, maks. 3MB)</span></label>
      @if($slide?->image)
        <img id="editImagePreview" src="{{ asset('uploads/hero/'.$slide->image) }}" class="d-block mb-2 rounded" style="max-height:100px;object-fit:cover;" />
      @else
        <img id="editImagePreview" src="" class="d-none d-block mb-2 rounded" style="max-height:100px;object-fit:cover;" />
      @endif
      <input type="file" name="image" class="form-control form-control-sm" accept="image/*" style="border-radius:8px;" />
      <div class="form-text">Jika dikosongkan, warna background akan digunakan.</div>
    </div>

  </div>
</div>
<div class="modal-footer border-top px-4 py-3">
  <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal" style="border-radius:8px;">Batal</button>
  <button type="submit" class="btn btn-primary btn-sm" style="border-radius:8px;">
    <i class="bi bi-floppy me-1"></i>Simpan Slide
  </button>
</div>
