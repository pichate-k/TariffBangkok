
@if ($action == 'readwrite' || $action == 'readonly')
  @include('pages.form.documentcommentlog')
@endif

<!-- Header Document Name -->
<div class="d-flex">
  <div class="p-2 w-100">
    <h5 class="text-center">คำขอติดตั้งอุปกรณ์วัดปริมาณน้ำเสียจากแหล่งกำเนิดน้ำเสียที่ใช้น้ำประปา</h5>
  </div>
  <div class="p-2 flex-shrink-1">
    <span class="text-nowrap border border-black p-2">แบบ นท.1</span>
  </div>
</div>

<hr>
<h5>ชื่อผู้ยื่นแบบ/ผู้รับมอบอำนาจ</h5>

@include('pages.form.userprofile', ['action' => 'readonly'])

<form id="fm_document_nt1">
  <input type="hidden" name="doc_nt1_id">
  <input type="hidden" name="doc_no">
  <hr>
  <h5>ขอยื่นคำขอติดตั้งอุปกรณ์วัดปริมาณน้ำเสียจากแหล่งกำเนิดน้ำเสียที่ใช้น้ำประปา ต่อพนักงานเจ้าหน้าที่ ดังต่อไปนี้</h5>
  <h5 class="mt-5">1. ชื่อแหล่งกำเนิดน้ำเสีย</h5>
  <div>
    @include('pages.form.addressprofile', ['action' => $action])
  </div>


  <h5 class="mt-5">2. หลักฐานที่นำมาประกอบแบบคำขอ (กรณีบุคคลธรรมดา/นิติบุคคล) <span class="text-danger">(ชนิดไฟล์ที่รองรับได้แก่ PDF/JPEG/PNG และขนาดไม่เกิน 20 MB ต่อไฟล์)</span></h5>
  <hr>
  <div class="mb-3 row align-items-center">
    <div class="col-11 offset-1">
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="cb_doc_attach_1" name="cb_doc_attach_1" attr-layout-target="layout_doc_attach_1" @if($action == 'readonly') disabled @endif>
        <label class="form-check-label" for="cb_doc_attach_1">สำเนาหนังสือรับรองการจดทะเบียนนิติบุคคลออกให้ไม่เกิน 6 เดือน (กรณีนิติบุคคล)</label>
        <button type="button" class="text-primary opacity-100 d-none px-3" id="txt_doc_attach_1">แสดงเอกสารแนบ</button>
      </div>
      <div class="upload-container mb-2 d-none" id="layout_doc_attach_1">
        <input name="doc_attach_1" type="file" accept="image/png, image/jpeg, application/pdf" size="40" />
      </div>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="cb_doc_attach_2" name="cb_doc_attach_2" attr-layout-target="layout_doc_attach_2" @if($action == 'readonly') disabled @endif>
        <label class="form-check-label" for="cb_doc_attach_2">หนังสือมอบอำนาจที่ถูกต้องตามกฎหมาย (ถ้ามี)</label>
        <button type="button" class="text-primary opacity-100 d-none px-3" id="txt_doc_attach_2">แสดงเอกสารแนบ</button>
      </div>
      <div class="upload-container mb-2 d-none" id="layout_doc_attach_2">
        <input name="doc_attach_2" type="file" accept="image/png, image/jpeg, application/pdf" size="40" />
      </div>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="cb_doc_attach_3" name="cb_doc_attach_3" attr-layout-target="layout_doc_attach_3" @if($action == 'readonly') disabled @endif>
        <label class="form-check-label" for="cb_doc_attach_3">หนังสือเดินทางและใบอนุญาตทำงาน (กรณีบุคคลต่างด้าว)</label>
        <button type="button" class="text-primary opacity-100 d-none px-3" id="txt_doc_attach_3">แสดงเอกสารแนบ</button>
      </div>
      <div class="upload-container mb-2 d-none" id="layout_doc_attach_3">
        <input name="doc_attach_3" type="file" accept="image/png, image/jpeg, application/pdf" size="40" />
      </div>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="cb_doc_attach_4" name="cb_doc_attach_4" attr-layout-target="layout_doc_attach_4" @if($action == 'readonly') disabled @endif>
        <label class="form-check-label" for="cb_doc_attach_4">ผังแสดงตำแหน่งอุปกรณ์วัดปริมาณน้ำเสีย</label>
        <button type="button" class="text-primary opacity-100 d-none px-3" id="txt_doc_attach_4">แสดงเอกสารแนบ</button>
      </div>
      <div class="d-none" id="layout_doc_attach_4">
        <div class="upload-container mb-2">
          <input name="doc_attach_4" type="file" accept="image/png, image/jpeg, application/pdf" size="40" />
        </div>
      </div>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="cb_doc_attach_5" name="cb_doc_attach_5" attr-layout-target="layout_doc_attach_5" @if($action == 'readonly') disabled @endif>
        <label class="form-check-label" for="cb_doc_attach_5">ภาพถ่ายหรือหลักฐานอื่นใดที่แสดงถึงอุปกรณ์วัดปริมาณน้ำเสีย</label>
        <button type="button" class="text-primary opacity-100 d-none px-3" id="txt_doc_attach_5">แสดงเอกสารแนบ</button>
      </div>
      <div class="d-none" id="layout_doc_attach_5">
        <div class="upload-container mb-2">
          <input name="doc_attach_5" type="file" accept="image/png, image/jpeg, application/pdf" size="40" />
        </div>
      </div>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="cb_doc_attach_6" name="cb_doc_attach_6" attr-layout-target="layout_doc_attach_6" @if($action == 'readonly') disabled @endif>
        <label class="form-check-label" for="cb_doc_attach_6">อื่น ๆ</label>
        <button type="button" class="text-primary opacity-110 d-none px-3" id="txt_doc_attach_6">แสดงเอกสารแนบ</button>
      </div>
      <div class="d-none" id="layout_doc_attach_6">
        <div class="upload-container mb-2">
          <input name="doc_attach_6" type="file" accept="image/png, image/jpeg, application/pdf" size="40" />
        </div>
      </div>
    </div>
  </div>


  <div class="mb-2 row align-items-center">
    <div class="col-10 offset-2">
      <h5>ข้าพเจ้าขอรับรองว่าข้อความและเอกสารนี้เป็นจริงทุกประการ</h5>
    </div>
  </div>
</form>


<!-- ================== BEGIN PAGE LEVEL JS ================== -->
@if ($action == 'readwrite' || $action == 'readonly')
  <script src="{{ asset('js/form-userprofile.js') }}"></script>
  <script src="{{ asset('js/user-profile.js') }}"></script>
  <script src="{{ asset('js/document-comment-log.js') }}"></script>
  <script src="{{ asset('js/form-document-nt1.js') }}"></script>
  <script src="{{ asset('js/document-nt1-read.js') }}"></script>
@endif
@if ($action == 'freeform')
  <script src="{{ asset('js/form-userprofile.js') }}"></script>
  <script src="{{ asset('js/user-profile.js') }}"></script>
  <script src="{{ asset('js/form-document-nt1.js') }}"></script>
  <script src="{{ asset('js/document-nt1-write.js') }}"></script>
@endif
<!-- ================== END PAGE LEVEL JS ================== -->
