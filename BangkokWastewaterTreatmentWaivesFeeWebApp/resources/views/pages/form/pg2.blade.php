
@if ($action == 'readwrite' || $action == 'readonly')
  @include('pages.form.documentcommentlog')
@endif

<!-- Header Document Name -->
<div class="d-flex">
  <div class="p-2 w-100">
    <h5 class="text-center">รายงานปริมาณการใช้น้ำหรือปริมาณน้ำเสียโดยติดตั้งอุปกรณ์วัดปริมาณน้ำ</h5>
  </div>
  <div class="p-2 flex-shrink-1">
    <span class="text-nowrap border border-black p-2">แบบ ปก.2</span>
  </div>
</div>

<hr>
<h5>ชื่อผู้ยื่นแบบ/ผู้รับมอบอำนาจ</h5>

@include('pages.form.userprofile', ['action' => 'readonly'])

<form id="fm_document_pg2">
  <input type="hidden" name="doc_pg2_id">
  <input type="hidden" name="doc_no">
  <hr>
  <h5>ขอยื่นรายงานปริมาณการใช้น้ำหรือปริมาณน้ำเสียโดยติดตั้งอุปกรณ์วัดปริมาณน้ำ ต่อพนักงานเจ้าหน้าที่ ดังต่อไปนี้</h5>
  <h5 class="mt-5">ชื่อสถานประกอบการ</h5>
  <div>
    @include('pages.form.addressprofile', ['action' => $action])
  </div>


  <h5 class="mt-5 text-danger">1. การรายงานข้อมูล</h5>
  <hr>
  <h5 class="mx-5 text-danger">1.1 การรายงานข้อมูลปริมาณการใช้น้ำ กรอกข้อมูลเฉพาะข้อ 2</h5>
  <h5 class="mx-5 text-danger">1.2 การรายงานข้อมูลปริมาณน้ำเสียโดยติดตั้งอุปกรณ์วัดปริมาณน้ำเสีย กรอกข้อมูลเฉพาะข้อ 3</h5>

  <h5 class="mt-5">2. รายงานปริมาณการใช้น้ำ (ข้อมูลปริมาณการใช้น้ำบาดาล)</h5>
  <hr>
  <div>
    <div class="mb-2 row align-items-center">
      <label class="col-3 col-form-label ps-4">วันที่บันทึกข้อมูล <span class="text-danger">*</span></label>
      <div class="col-3">
        <div class="input-group">
          <input type="text" class="form-control datetimepicker" name="badan_test_date" @if($action == 'readonly') readonly @endif>
          <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
        </div>
      </div>
      <label class="col-3 col-form-label ps-4">จำนวนบ่อน้ำบาดาล</label>
      <div class="col-3">
        <div class="input-group">
          <input type="number" class="form-control" name="badan_total_pool" @if($action == 'readonly') readonly @endif>
          <span class="input-group-text">บ่อ</span>
        </div>
      </div>
    </div>

    <h5 class="m-3">น้ำบาดาล - มีอุปกรณ์วัดปริมาณน้ำ</h5>
    <hr>
    <div class="mb-2 row align-items-center">
      <label class="col-5 col-form-label ps-4">อุปกรณ์วัดปริมาณน้ำที่ 1 เลขที่จดครั้งก่อน</label>
      <div class="col-3">
        <input type="number" class="form-control" name="badan_test_checkpoint_1_before" @if($action == 'readonly') readonly @endif>
      </div>
      <label class="col-2 col-form-label ps-4">เลขที่จดครั้งนี้</label>
      <div class="col-2">
        <input type="number" class="form-control" name="badan_test_checkpoint_1_after" @if($action == 'readonly') readonly @endif>
      </div>
    </div>
    <div class="mb-2 row align-items-center">
      <label class="col-5 col-form-label ps-4">อุปกรณ์วัดปริมาณน้ำที่ 2 เลขที่จดครั้งก่อน</label>
      <div class="col-3">
        <input type="number" class="form-control" name="badan_test_checkpoint_2_before" @if($action == 'readonly') readonly @endif>
      </div>
      <label class="col-2 col-form-label ps-4">เลขที่จดครั้งนี้</label>
      <div class="col-2">
        <input type="number" class="form-control" name="badan_test_checkpoint_2_after" @if($action == 'readonly') readonly @endif>
      </div>
    </div>
    <div class="mb-2 row align-items-center">
      <label class="col-5 col-form-label ps-4">อุปกรณ์วัดปริมาณน้ำที่ 3 เลขที่จดครั้งก่อน</label>
      <div class="col-3">
        <input type="number" class="form-control" name="badan_test_checkpoint_3_before" @if($action == 'readonly') readonly @endif>
      </div>
      <label class="col-2 col-form-label ps-4">เลขที่จดครั้งนี้</label>
      <div class="col-2">
        <input type="number" class="form-control" name="badan_test_checkpoint_3_after" @if($action == 'readonly') readonly @endif>
      </div>
    </div>
    <div class="mb-2 row align-items-center">
      <label class="col-5 col-form-label ps-4">อุปกรณ์วัดปริมาณน้ำที่ 4 เลขที่จดครั้งก่อน</label>
      <div class="col-3">
        <input type="number" class="form-control" name="badan_test_checkpoint_4_before" @if($action == 'readonly') readonly @endif>
      </div>
      <label class="col-2 col-form-label ps-4">เลขที่จดครั้งนี้</label>
      <div class="col-2">
        <input type="number" class="form-control" name="badan_test_checkpoint_4_after" @if($action == 'readonly') readonly @endif>
      </div>
    </div>
    <div class="mb-2 row align-items-center">
      <label class="col-5 col-form-label ps-4">อุปกรณ์วัดปริมาณน้ำที่ 5 เลขที่จดครั้งก่อน</label>
      <div class="col-3">
        <input type="number" class="form-control" name="badan_test_checkpoint_5_before" @if($action == 'readonly') readonly @endif>
      </div>
      <label class="col-2 col-form-label ps-4">เลขที่จดครั้งนี้</label>
      <div class="col-2">
        <input type="number" class="form-control" name="badan_test_checkpoint_5_after" @if($action == 'readonly') readonly @endif>
      </div>
    </div>
    <div class="mb-2 row align-items-center">
      <label class="col-5 col-form-label ps-4">อุปกรณ์วัดปริมาณน้ำที่ 6 เลขที่จดครั้งก่อน</label>
      <div class="col-3">
        <input type="number" class="form-control" name="badan_test_checkpoint_6_before" @if($action == 'readonly') readonly @endif>
      </div>
      <label class="col-2 col-form-label ps-4">เลขที่จดครั้งนี้</label>
      <div class="col-2">
        <input type="number" class="form-control" name="badan_test_checkpoint_6_after" @if($action == 'readonly') readonly @endif>
      </div>
    </div>
    <div class="mb-2 row align-items-center">
      <label class="col-5 col-form-label ps-4">ปริมาณน้ำใช้รวมที่อ่านได้</label>
      <div class="col-4">
        <div class="input-group">
          <input type="number" class="form-control" name="badan_water_capacity_per_month" @if($action == 'readonly') readonly @endif>
          <span class="input-group-text">ลูกบาศก์เมตร/เดือน</span>
        </div>
      </div>
    </div>

    <h5 class="m-3">น้ำแหล่งอื่นนอกจากน้ำบาดาล</h5>
    <hr>
    <div class="mb-2 row align-items-center">
      <label class="col-5 col-form-label ps-4">แหล่งที่มาของน้ำใช้</label>
      <div class="col-7">
        <input type="text" class="form-control" name="non_badan_source" @if($action == 'readonly') readonly @endif>
      </div>
    </div>
    <div class="mb-2 row align-items-center">
      <label class="col-5 col-form-label ps-4">อุปกรณ์วัดปริมาณน้ำที่ 1 เลขที่จดครั้งก่อน</label>
      <div class="col-3">
        <input type="number" class="form-control" name="non_badan_test_checkpoint_1_before" @if($action == 'readonly') readonly @endif>
      </div>
      <label class="col-2 col-form-label ps-4">เลขที่จดครั้งนี้</label>
      <div class="col-2">
        <input type="number" class="form-control" name="non_badan_test_checkpoint_1_after" @if($action == 'readonly') readonly @endif>
      </div>
    </div>
    <div class="mb-2 row align-items-center">
      <label class="col-5 col-form-label ps-4">อุปกรณ์วัดปริมาณน้ำที่ 2 เลขที่จดครั้งก่อน</label>
      <div class="col-3">
        <input type="number" class="form-control" name="non_badan_test_checkpoint_2_before" @if($action == 'readonly') readonly @endif>
      </div>
      <label class="col-2 col-form-label ps-4">เลขที่จดครั้งนี้</label>
      <div class="col-2">
        <input type="number" class="form-control" name="non_badan_test_checkpoint_2_after" @if($action == 'readonly') readonly @endif>
      </div>
    </div>
    <div class="mb-2 row align-items-center">
      <label class="col-5 col-form-label ps-4">อุปกรณ์วัดปริมาณน้ำที่ 3 เลขที่จดครั้งก่อน</label>
      <div class="col-3">
        <input type="number" class="form-control" name="non_badan_test_checkpoint_3_before" @if($action == 'readonly') readonly @endif>
      </div>
      <label class="col-2 col-form-label ps-4">เลขที่จดครั้งนี้</label>
      <div class="col-2">
        <input type="number" class="form-control" name="non_badan_test_checkpoint_3_after" @if($action == 'readonly') readonly @endif>
      </div>
    </div>
    <div class="mb-2 row align-items-center">
      <label class="col-5 col-form-label ps-4">อุปกรณ์วัดปริมาณน้ำที่ 4 เลขที่จดครั้งก่อน</label>
      <div class="col-3">
        <input type="number" class="form-control" name="non_badan_test_checkpoint_4_before" @if($action == 'readonly') readonly @endif>
      </div>
      <label class="col-2 col-form-label ps-4">เลขที่จดครั้งนี้</label>
      <div class="col-2">
        <input type="number" class="form-control" name="non_badan_test_checkpoint_4_after" @if($action == 'readonly') readonly @endif>
      </div>
    </div>
    <div class="mb-2 row align-items-center">
      <label class="col-5 col-form-label ps-4">อุปกรณ์วัดปริมาณน้ำที่ 5 เลขที่จดครั้งก่อน</label>
      <div class="col-3">
        <input type="number" class="form-control" name="non_badan_test_checkpoint_5_before" @if($action == 'readonly') readonly @endif>
      </div>
      <label class="col-2 col-form-label ps-4">เลขที่จดครั้งนี้</label>
      <div class="col-2">
        <input type="number" class="form-control" name="non_badan_test_checkpoint_5_after" @if($action == 'readonly') readonly @endif>
      </div>
    </div>
    <div class="mb-2 row align-items-center">
      <label class="col-5 col-form-label ps-4">อุปกรณ์วัดปริมาณน้ำที่ 6 เลขที่จดครั้งก่อน</label>
      <div class="col-3">
        <input type="number" class="form-control" name="non_badan_test_checkpoint_6_before" @if($action == 'readonly') readonly @endif>
      </div>
      <label class="col-2 col-form-label ps-4">เลขที่จดครั้งนี้</label>
      <div class="col-2">
        <input type="number" class="form-control" name="non_badan_test_checkpoint_6_after" @if($action == 'readonly') readonly @endif>
      </div>
    </div>
    <div class="mb-2 row align-items-center">
      <label class="col-5 col-form-label ps-4">ปริมาณน้ำใช้รวมที่อ่านได้</label>
      <div class="col-4">
        <div class="input-group">
          <input type="number" class="form-control" name="non_badan_water_capacity_per_month" @if($action == 'readonly') readonly @endif>
          <span class="input-group-text">ลูกบาศก์เมตร/เดือน</span>
        </div>
      </div>
    </div>
  </div>


  <h5 class="mt-5">3. รายงานปริมาณน้ำเสียโดยติดตั้งอุปกรณ์วัดปริมาณน้ำเสีย (ปริมาณการระบายน้ำเสีย)</h5>
  <hr>
  <div>
    <div class="mb-2 row align-items-center">
      <label class="col-5 col-form-label ps-4">จุดระบายน้ำเสียที่ 1</label>
      <div class="col-4">
        <div class="input-group">
          <input type="number" class="form-control" name="wastewater_test_checkpoint_1_per_month" @if($action == 'readonly') readonly @endif>
          <span class="input-group-text">ลูกบาศก์เมตร/เดือน</span>
        </div>
      </div>
    </div>
    <div class="mb-2 row align-items-center">
      <label class="col-5 col-form-label ps-4">จุดระบายน้ำเสียที่ 2</label>
      <div class="col-4">
        <div class="input-group">
          <input type="number" class="form-control" name="wastewater_test_checkpoint_2_per_month" @if($action == 'readonly') readonly @endif>
          <span class="input-group-text">ลูกบาศก์เมตร/เดือน</span>
        </div>
      </div>
    </div>
    <div class="mb-2 row align-items-center">
      <label class="col-5 col-form-label ps-4">จุดระบายน้ำเสียที่ 3</label>
      <div class="col-4">
        <div class="input-group">
          <input type="number" class="form-control" name="wastewater_test_checkpoint_3_per_month" @if($action == 'readonly') readonly @endif>
          <span class="input-group-text">ลูกบาศก์เมตร/เดือน</span>
        </div>
      </div>
    </div>
    <div class="mb-2 row align-items-center">
      <label class="col-5 col-form-label ps-4">จุดระบายน้ำเสียที่ 4</label>
      <div class="col-4">
        <div class="input-group">
          <input type="number" class="form-control" name="wastewater_test_checkpoint_4_per_month" @if($action == 'readonly') readonly @endif>
          <span class="input-group-text">ลูกบาศก์เมตร/เดือน</span>
        </div>
      </div>
    </div>
    <div class="mb-2 row align-items-center">
      <label class="col-5 col-form-label ps-4">จุดระบายน้ำเสียที่ 5</label>
      <div class="col-4">
        <div class="input-group">
          <input type="number" class="form-control" name="wastewater_test_checkpoint_5_per_month" @if($action == 'readonly') readonly @endif>
          <span class="input-group-text">ลูกบาศก์เมตร/เดือน</span>
        </div>
      </div>
    </div>
  </div>


  <h5 class="mt-5">4. หลักฐานที่นำมาประกอบแบบรายงาน <span class="text-danger">(ชนิดไฟล์ที่รองรับได้แก่ PDF/JPEG/PNG และขนาดไม่เกิน 20 MB ต่อไฟล์)</span></h5>
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
  <script src="{{ asset('js/form-document-pg2.js') }}"></script>
  <script src="{{ asset('js/document-pg2-read.js') }}"></script>
@endif
@if ($action == 'freeform')
  <script src="{{ asset('js/form-userprofile.js') }}"></script>
  <script src="{{ asset('js/user-profile.js') }}"></script>
  <script src="{{ asset('js/form-document-pg2.js') }}"></script>
  <script src="{{ asset('js/document-pg2-write.js') }}"></script>
@endif
<!-- ================== END PAGE LEVEL JS ================== -->
