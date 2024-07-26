
@if ($action == 'readwrite' || $action == 'readonly')
  @include('pages.form.documentcommentlog')
@endif

<!-- Header Document Name -->
<div class="d-flex">
  <div class="p-2 w-100">
    <h5 class="text-center">รายงานปริมาณน้ำเสียโดยติดตั้งอุปกรณ์วัดปริมาณน้ำเสียจากแหล่งกำเนิดน้ำเสียที่ใช้น้ำประปา</h5>
  </div>
  <div class="p-2 flex-shrink-1">
    <span class="text-nowrap border border-black p-2">แบบ นท.2</span>
  </div>
</div>

<hr>
<h5>ชื่อผู้ยื่นแบบ/ผู้รับมอบอำนาจ</h5>

@include('pages.form.userprofile', ['action' => 'readonly'])

<form id="fm_document_nt2">
  <input type="hidden" name="doc_nt2_id">
  <input type="hidden" name="doc_no">
  <hr>
  <h5>ขอยื่นรายงานปริมาณน้ำเสียโดยติดตั้งอุปกรณ์วัดปริมาณน้ำเสียจากแหล่งกำเนิดน้ำเสียที่ใช้น้ำประปา ต่อพนักงานเจ้าหน้าที่ ดังต่อไปนี้</h5>
  <h5 class="mt-5">ชื่อสถานประกอบการ</h5>
  <div>
    @include('pages.form.addressprofile', ['action' => $action])
  </div>


  <h5 class="mt-5">1. ข้อมูลปริมาณการระบายน้ำเสียโดยติดตั้งอุปกรณ์วัดน้ำเสีย</h5>
  <hr>
  <div>
    <div class="mb-2 row align-items-center">
      <label class="col-6 col-form-label ps-4">จำนวนจุดระบายน้ำเสีย <span class="text-danger">*</span></label>
      <div class="col-5">
        <div class="input-group">
          <input type="number" class="form-control" name="wastewater_total_checkpoint" @if($action == 'readonly') readonly @endif>
          <span class="input-group-text">จุด</span>
        </div>
      </div>
    </div>
    <div class="mb-2 row align-items-center">
      <label class="col-6 col-form-label ps-4">จุดระบายน้ำเสียที่ 1</label>
      <div class="col-5">
        <div class="input-group">
          <input type="number" class="form-control" name="wastewater_test_checkpoint_1_per_month" @if($action == 'readonly') readonly @endif>
          <span class="input-group-text">ลูกบาศก์เมตร/เดือน</span>
        </div>
      </div>
    </div>
    <div class="mb-2 row align-items-center">
      <label class="col-6 col-form-label ps-4">จุดระบายน้ำเสียที่ 2</label>
      <div class="col-5">
        <div class="input-group">
          <input type="number" class="form-control" name="wastewater_test_checkpoint_2_per_month" @if($action == 'readonly') readonly @endif>
          <span class="input-group-text">ลูกบาศก์เมตร/เดือน</span>
        </div>
      </div>
    </div>
    <div class="mb-2 row align-items-center">
      <label class="col-6 col-form-label ps-4">จุดระบายน้ำเสียที่ 3</label>
      <div class="col-5">
        <div class="input-group">
          <input type="number" class="form-control" name="wastewater_test_checkpoint_3_per_month" @if($action == 'readonly') readonly @endif>
          <span class="input-group-text">ลูกบาศก์เมตร/เดือน</span>
        </div>
      </div>
    </div>
    <div class="mb-2 row align-items-center">
      <label class="col-6 col-form-label ps-4">จุดระบายน้ำเสียที่ 4</label>
      <div class="col-5">
        <div class="input-group">
          <input type="number" class="form-control" name="wastewater_test_checkpoint_4_per_month" @if($action == 'readonly') readonly @endif>
          <span class="input-group-text">ลูกบาศก์เมตร/เดือน</span>
        </div>
      </div>
    </div>
    <div class="mb-2 row align-items-center">
      <label class="col-6 col-form-label ps-4">จุดระบายน้ำเสียที่ 5</label>
      <div class="col-5">
        <div class="input-group">
          <input type="number" class="form-control" name="wastewater_test_checkpoint_5_per_month" @if($action == 'readonly') readonly @endif>
          <span class="input-group-text">ลูกบาศก์เมตร/เดือน</span>
        </div>
      </div>
    </div>
    <div class="mb-2 row align-items-center">
      <label class="col-6 col-form-label ps-4">ปริมาณน้ำเสียที่ระบายออกจากแหล่งกำเนิดน้ำเสีย <span class="text-danger">*</span></label>
      <div class="col-5">
        <div class="input-group">
          <input type="number" class="form-control" name="wastewater_total_per_month" @if($action == 'readonly') readonly @endif>
          <span class="input-group-text">ลูกบาศก์เมตร/เดือน</span>
        </div>
      </div>
    </div>
  </div>


  <h5 class="mt-5">2. หลักฐานที่นำมาประกอบแบบรายงาน <span class="text-danger">(ชนิดไฟล์ที่รองรับได้แก่ PDF/JPEG/PNG และขนาดไม่เกิน 20 MB ต่อไฟล์)</span></h5>
  <hr>
  <div class="mb-3 row align-items-center">
    <div class="col-11 offset-1">
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="cb_doc_attach_1" name="cb_doc_attach_1" attr-layout-target="layout_doc_attach_1" @if($action == 'readonly') disabled @endif>
        <label class="form-check-label" for="cb_doc_attach_1">ภาพถ่ายหรือหลักฐานอื่นใดที่แสดงถึงเลขที่จดแจ้งของอุปกรณ์วัดปริมาณน้ำ 1</label>
        <button type="button" class="text-primary opacity-100 d-none px-3" id="txt_doc_attach_1">แสดงเอกสารแนบ</button>
      </div>
      <div class="upload-container mb-2 d-none" id="layout_doc_attach_1">
        <input name="doc_attach_1" type="file" accept="image/png, image/jpeg, application/pdf" size="40" />
      </div>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="cb_doc_attach_2" name="cb_doc_attach_2" attr-layout-target="layout_doc_attach_2" @if($action == 'readonly') disabled @endif>
        <label class="form-check-label" for="cb_doc_attach_2">ภาพถ่ายหรือหลักฐานอื่นใดที่แสดงถึงเลขที่จดแจ้งของอุปกรณ์วัดปริมาณน้ำ 2</label>
        <button type="button" class="text-primary opacity-100 d-none px-3" id="txt_doc_attach_2">แสดงเอกสารแนบ</button>
      </div>
      <div class="upload-container mb-2 d-none" id="layout_doc_attach_2">
        <input name="doc_attach_2" type="file" accept="image/png, image/jpeg, application/pdf" size="40" />
      </div>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="cb_doc_attach_3" name="cb_doc_attach_3" attr-layout-target="layout_doc_attach_3" @if($action == 'readonly') disabled @endif>
        <label class="form-check-label" for="cb_doc_attach_3">ภาพถ่ายหรือหลักฐานอื่นใดที่แสดงถึงเลขที่จดแจ้งของอุปกรณ์วัดปริมาณน้ำ 3</label>
        <button type="button" class="text-primary opacity-100 d-none px-3" id="txt_doc_attach_3">แสดงเอกสารแนบ</button>
      </div>
      <div class="upload-container mb-2 d-none" id="layout_doc_attach_3">
        <input name="doc_attach_3" type="file" accept="image/png, image/jpeg, application/pdf" size="40" />
      </div>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="cb_doc_attach_4" name="cb_doc_attach_4" attr-layout-target="layout_doc_attach_4" @if($action == 'readonly') disabled @endif>
        <label class="form-check-label" for="cb_doc_attach_4">ภาพถ่ายหรือหลักฐานอื่นใดที่แสดงถึงเลขที่จดแจ้งของอุปกรณ์วัดปริมาณน้ำ 4</label>
        <button type="button" class="text-primary opacity-100 d-none px-3" id="txt_doc_attach_4">แสดงเอกสารแนบ</button>
      </div>
      <div class="upload-container mb-2 d-none" id="layout_doc_attach_4">
        <input name="doc_attach_4" type="file" accept="image/png, image/jpeg, application/pdf" size="40" />
      </div>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="cb_doc_attach_5" name="cb_doc_attach_5" attr-layout-target="layout_doc_attach_5" @if($action == 'readonly') disabled @endif>
        <label class="form-check-label" for="cb_doc_attach_5">ภาพถ่ายหรือหลักฐานอื่นใดที่แสดงถึงเลขที่จดแจ้งของอุปกรณ์วัดปริมาณน้ำ 5</label>
        <button type="button" class="text-primary opacity-100 d-none px-3" id="txt_doc_attach_5">แสดงเอกสารแนบ</button>
      </div>
      <div class="upload-container mb-2 d-none" id="layout_doc_attach_5">
        <input name="doc_attach_5" type="file" accept="image/png, image/jpeg, application/pdf" size="40" />
      </div>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="cb_doc_attach_6" name="cb_doc_attach_6" attr-layout-target="layout_doc_attach_6" @if($action == 'readonly') disabled @endif>
        <label class="form-check-label" for="cb_doc_attach_6">ภาพถ่ายหรือหลักฐานอื่นใดที่แสดงถึงเลขที่จดแจ้งของอุปกรณ์วัดปริมาณน้ำ 6</label>
        <button type="button" class="text-primary opacity-100 d-none px-3" id="txt_doc_attach_6">แสดงเอกสารแนบ</button>
      </div>
      <div class="upload-container mb-2 d-none" id="layout_doc_attach_6">
        <input name="doc_attach_6" type="file" accept="image/png, image/jpeg, application/pdf" size="40" />
      </div>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="cb_doc_attach_7" name="cb_doc_attach_7" attr-layout-target="layout_doc_attach_7" @if($action == 'readonly') disabled @endif>
        <label class="form-check-label" for="cb_doc_attach_7">อื่น ๆ</label>
        <button type="button" class="text-primary opacity-100 d-none px-3" id="txt_doc_attach_7">แสดงเอกสารแนบ</button>
      </div>
      <div class="upload-container mb-2 d-none" id="layout_doc_attach_7">
        <input name="doc_attach_7" type="file" accept="image/png, image/jpeg, application/pdf" size="40" />
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
  <script src="{{ asset('js/form-document-nt2.js') }}"></script>
  <script src="{{ asset('js/document-nt2-read.js') }}"></script>
@endif
@if ($action == 'freeform')
  <script src="{{ asset('js/form-userprofile.js') }}"></script>
  <script src="{{ asset('js/user-profile.js') }}"></script>
  <script src="{{ asset('js/form-document-nt2.js') }}"></script>
  <script src="{{ asset('js/document-nt2-write.js') }}"></script>
@endif
<!-- ================== END PAGE LEVEL JS ================== -->
