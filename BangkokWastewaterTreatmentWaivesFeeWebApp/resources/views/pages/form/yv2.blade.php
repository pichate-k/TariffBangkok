
@if ($action == 'readwrite' || $action == 'readonly')
  @include('pages.form.documentcommentlog')
@endif

<!-- Header Document Name -->
<div class="d-flex">
  <div class="p-2 w-100">
    <h5 class="text-center">คำร้องขอยกเลิกการขอยกเว้นค่าธรรมเนียมบำบัดน้ำเสีย</h5>
  </div>
  <div class="p-2 flex-shrink-1">
    <span class="text-nowrap border border-black p-2">แบบ ยว.2</span>
  </div>
</div>

<hr>
<h5>ชื่อผู้ยื่นแบบ/ผู้รับมอบอำนาจ</h5>

@include('pages.form.userprofile', ['action' => 'readonly'])

<form id="fm_document_yv2">
  <input type="hidden" name="doc_yv2_id">
  <input type="hidden" name="doc_no">
  <hr>
  <h5>ขอยื่นแบบขอยกเลิกการขอยกเว้นค่าธรรมเนียมบำบัดน้ำเสีย พนักงานเจ้าหน้าที่ ดังต่อไปนี้</h5>
  <h5 class="mt-5">ชื่อสถานประกอบการ</h5>
  <div>
    @include('pages.form.addressprofile', ['action' => $action])
  </div>


  <h5 class="mt-5">ขอยกเลิกการขอยกเว้นค่าธรรมเนียมบำบัดน้ำเสียสำหรับแหล่งกำเนิดน้ำเสียที่มีระบบบำบัดน้ำเสียซึ่งสามารถบำบัดน้ำเสียได้จนทิ้ง</h5>
  <hr>
  <div>
    <div class="mb-2 row align-items-center">
      <label class="col-3 col-form-label ps-4">เนื่องจาก <span class="text-danger">*</span></label>
      <div class="col-3">
         <textarea class="form-control" name="cancel_reason" @if($action == 'readonly') disabled @endif rows="5"></textarea>
      </div>
      <label class="col-3 col-form-label ps-4">ตั้งแต่วันที่ <span class="text-danger">*</span></label>
      <div class="col-3">
        <div class="input-group">
          <input type="text" class="form-control datetimepicker" name="cancel_date" @if($action == 'readonly') readonly @endif>
          <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
        </div>
      </div>
    </div>
  </div>

  <h5 class="mt-5">พร้อมทั้งได้แนบหลักฐานที่นำมาประกอบการพิจารณา ดังนี้ <span class="text-danger">(ชนิดไฟล์ที่รองรับได้แก่ PDF/JPEG/PNG และขนาดไม่เกิน 20 MB ต่อไฟล์)</span></h5>
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
        <label class="form-check-label" for="cb_doc_attach_2">หนังสือเดินทางและใบอนุญาตทำงาน (กรณีบุคคลต่างด้าว)</label>
        <button type="button" class="text-primary opacity-100 d-none px-3" id="txt_doc_attach_2">แสดงเอกสารแนบ</button>
      </div>
      <div class="upload-container mb-2 d-none" id="layout_doc_attach_2">
        <input name="doc_attach_2" type="file" accept="image/png, image/jpeg, application/pdf" size="40" />
      </div>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="cb_doc_attach_3" name="cb_doc_attach_3" attr-layout-target="layout_doc_attach_3" @if($action == 'readonly') disabled @endif>
        <label class="form-check-label" for="cb_doc_attach_3">หนังสือมอบอำนาจที่ถูกต้องตามกฎหมาย (ถ้ามี)</label>
        <button type="button" class="text-primary opacity-100 d-none px-3" id="txt_doc_attach_3">แสดงเอกสารแนบ</button>
      </div>
      <div class="upload-container mb-2 d-none" id="layout_doc_attach_3">
        <input name="doc_attach_3" type="file" accept="image/png, image/jpeg, application/pdf" size="40" />
      </div>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="cb_doc_attach_4" name="cb_doc_attach_4" attr-layout-target="layout_doc_attach_4" @if($action == 'readonly') disabled @endif>
        <label class="form-check-label" for="cb_doc_attach_4">อื่น ๆ</label>
        <button type="button" class="text-primary opacity-100 d-none px-3" id="txt_doc_attach_4">แสดงเอกสารแนบ</button>
      </div>
      <div class="d-none" id="layout_doc_attach_4">
        <div class="upload-container mb-2">
          <input name="doc_attach_4" type="file" accept="image/png, image/jpeg, application/pdf" size="40" />
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
  <script src="{{ asset('js/form-document-yv2.js') }}"></script>
  <script src="{{ asset('js/document-yv2-read.js') }}"></script>
@endif
@if ($action == 'freeform')
  <script src="{{ asset('js/form-userprofile.js') }}"></script>
  <script src="{{ asset('js/user-profile.js') }}"></script>
  <script src="{{ asset('js/form-document-yv2.js') }}"></script>
  <script src="{{ asset('js/document-yv2-write.js') }}"></script>
@endif
<!-- ================== END PAGE LEVEL JS ================== -->
