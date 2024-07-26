
@if ($action == 'readwrite' || $action == 'readonly')
  @include('pages.form.documentcommentlog')
@endif

<!-- Header Document Name -->
<div class="d-flex">
  <div class="p-2 w-100">
    <h5 class="text-center">คำขอรายงานปริมาณการใช้น้ำหรือปริมาณน้ำเสีย</h5>
  </div>
  <div class="p-2 flex-shrink-1">
    <span class="text-nowrap border border-black p-2">แบบ ปก.1</span>
  </div>
</div>

<hr>
<h5>ชื่อผู้ยื่นแบบ/ผู้รับมอบอำนาจ</h5>

@include('pages.form.userprofile', ['action' => 'readonly'])

<form id="fm_document_pg1">
  <input type="hidden" name="doc_pg1_id">
  <input type="hidden" name="doc_no">
  <hr>
  <h5>ขอยื่นรายงานปริมาณการใช้น้ำหรือปริมาณน้ำเสีย ต่อพนักงานเจ้าหน้าที่ ดังต่อไปนี้</h5>
  <h5 class="mt-5">1. ชื่อแหล่งกำเนิดน้ำเสีย</h5>
  <div>
    @include('pages.form.addressprofile', ['action' => $action])
  </div>


  <h5 class="mt-5">2. ประเภทการใช้น้ำ</h5>
  <div>
    <div class="mb-3 row align-items-center">
      <label class="col-6 col-form-label ps-4">แหล่งกำเนิดน้ำเสียที่ใช้น้ำบาดาล <span class="text-danger">*</span></label>
      <div class="col-5">
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="badan_install_sensor" id="badan_install_sensor_1" value="1" checked @if($action == 'readonly') disabled @endif>
          <label class="form-check-label" for="badan_install_sensor_1">ติดตั้งอุปกรณ์วัดปริมาณน้ำบาดาล</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="badan_install_sensor" id="badan_install_sensor_2" value="2" @if($action == 'readonly') disabled @endif>
          <label class="form-check-label" for="badan_install_sensor_2">ไม่ติดตั้งอุปกรณ์วัดปริมาณน้ำบาดาล</label>
        </div>
      </div>
    </div>
    <div class="mb-3 row align-items-center">
      <label for="inputPassword" class="col-6 col-form-label ps-4">ปริมาณน้ำบาดาลสูงสุดที่กำหนดไว้ในใบอนุญาตใช้น้ำบาดาล <span class="text-danger">*</span></label>
      <div class="col-4">
        <div class="input-group">
          <input type="number" class="form-control" name="badan_max_capacity_per_month" @if($action == 'readonly') readonly @endif>
          <span class="input-group-text">ลูกบาศก์เมตร/เดือน</span>
        </div>
      </div>
    </div>
  </div>

  <!-- <h5>3. แหล่งกำเนิดน้ำเสียอื่นที่ไม่ใช้น้ำบาดาล</h5>
  <hr>
  <div class="mb-3 row align-items-center">
    <label for="inputPassword" class="col-2 col-form-label ps-4">ประเภทอาคาร <span class="text-danger">*</span></label>
    <div class="col-4">
      <input type="text" class="form-control" name="non_badan_building_type" @if($action == 'readonly') readonly @endif>
    </div>
  </div>
  <h5>3.1 ข้อมูลพื้นฐานสำหรับใช้ในการคำนวณ</h5>
  <hr>
  <div class="mb-3 row align-items-center">
    <label for="inputPassword" class="col-2 col-form-label ps-4">จำนวนคน <span class="text-danger">*</span></label>
    <div class="col-4">
      <div class="input-group">
        <input type="number" class="form-control" name="non_badan_calculate_people" @if($action == 'readonly') readonly @endif>
        <span class="input-group-text">คน</span>
      </div>
    </div>
    <label for="inputPassword" class="col-2 col-form-label ps-4">จำนวนแผง <span class="text-danger">*</span></label>
    <div class="col-4">
      <div class="input-group">
        <input type="number" class="form-control" name="non_badan_calculate_panel" @if($action == 'readonly') readonly @endif>
        <span class="input-group-text">แผง</span>
      </div>
    </div>
  </div>
  <div class="mb-3 row align-items-center">
    <label for="inputPassword" class="col-2 col-form-label ps-4">จำนวนห้อง <span class="text-danger">*</span></label>
    <div class="col-4">
      <div class="input-group">
        <input type="number" class="form-control" name="non_badan_calculate_room" @if($action == 'readonly') readonly @endif>
        <span class="input-group-text">ห้อง</span>
      </div>
    </div>
  </div>
  <div class="mb-3 row align-items-center">
    <label for="inputPassword" class="col-2 col-form-label ps-4">ปริมาณพื้นที่ <span class="text-danger">*</span></label>
    <div class="col-4">
      <div class="input-group">
        <input type="number" class="form-control" name="non_badan_calculate_m2" @if($action == 'readonly') readonly @endif>
        <span class="input-group-text">ตารางเมตร</span>
      </div>
    </div>
    <label for="inputPassword" class="col-2 col-form-label ps-4">จำนวนเตียง <span class="text-danger">*</span></label>
    <div class="col-4">
      <div class="input-group">
        <input type="number" class="form-control" name="non_badan_calculate_bed" @if($action == 'readonly') readonly @endif>
        <span class="input-group-text">เตียง</span>
      </div>
    </div>
  </div>
  <div class="mb-3 row align-items-center">
    <label for="inputPassword" class="col-2 col-form-label ps-4">แสดงรายการคำนวณ <span class="text-danger">*</span></label>
    <div class="col-10">
      <textarea class="form-control" name="non_badan_formular" rows="10" @if($action == 'readonly') readonly @endif></textarea>
    </div>
  </div>
  <div class="mb-3 row align-items-center">
    <label for="inputPassword" class="col-2 col-form-label ps-4">ปริมาณน้ำใช้เฉลี่ย <span class="text-danger">*</span></label>
    <div class="col-4">
      <div class="input-group">
        <input type="number" class="form-control" name="non_badan_water_avg_per_month" @if($action == 'readonly') readonly @endif>
        <span class="input-group-text">ลูกบาศก์เมตร/เดือน</span>
      </div>
    </div>
  </div>
  <h5>(โดยคำนวณปริมาณการใช้น้ำ ตามสูตรวิธีคำนวณปริมาณน้ำที่ใช้ในแหล่งกำเนิดน้ำเสียที่ไม่อาจ ใช้เครื่องอุปกรณ์วัดปริมาณน้ำที่ใช้ได้ ตามแนบท้ายประกาศ)</h5> -->


  <h5 class="mt-5">3. หลักฐานที่นำมาประกอบแบบรายงาน (กรณีบุคคลธรรมดา/นิติบุคคล) <span class="text-danger">(ชนิดไฟล์ที่รองรับได้แก่ PDF/JPEG/PNG และขนาดไม่เกิน 20 MB ต่อไฟล์)</span></h5>
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
        <label class="form-check-label" for="cb_doc_attach_4">สำเนาใบอนุญาตใช้น้ำบาดาล</label>
        <button type="button" class="text-primary opacity-100 d-none px-3" id="txt_doc_attach_4">แสดงเอกสารแนบ</button>
      </div>
      <div class="upload-container mb-2 d-none" id="layout_doc_attach_4">
        <input name="doc_attach_4" type="file" accept="image/png, image/jpeg, application/pdf" size="40" />
      </div>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="cb_doc_attach_5" name="cb_doc_attach_5" attr-layout-target="layout_doc_attach_5" @if($action == 'readonly') disabled @endif>
        <label class="form-check-label" for="cb_doc_attach_5">ภาพถ่ายหรือหลักฐานอื่นใดที่แสดงถึงเลขที่จดแจ้งของอุปกรณ์วัดปริมาณน้ำ</label>
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
        <button type="button" class="text-primary opacity-100 d-none px-3" id="txt_doc_attach_6">แสดงเอกสารแนบ</button>
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
  <script src="{{ asset('js/form-document-pg1.js') }}"></script>
  <script src="{{ asset('js/document-pg1-read.js') }}"></script>
@endif
@if ($action == 'freeform')
  <script src="{{ asset('js/form-userprofile.js') }}"></script>
  <script src="{{ asset('js/user-profile.js') }}"></script>
  <script src="{{ asset('js/form-document-pg1.js') }}"></script>
  <script src="{{ asset('js/document-pg1-write.js') }}"></script>
@endif
<!-- ================== END PAGE LEVEL JS ================== -->
