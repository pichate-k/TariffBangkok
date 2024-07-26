<!-- ================== BEGIN BASE CSS STYLE ================== -->
<link href="{{ asset('assets/plugins/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet" />
<!-- ================== END BASE CSS STYLE ================== -->

@if ($action == 'readwrite' || $action == 'readonly')
  @include('pages.form.documentcommentlog')
@endif

<!-- Header Document Name -->
<div class="d-flex">
  <div class="p-2 w-100">
    <h5 class="text-center">คำร้องขอยกเว้นค่าธรรมเนียมบำบัดน้ำเสีย</h5>
  </div>
  <div class="p-2 flex-shrink-1">
    <span class="text-nowrap border border-black p-2">แบบ ยว.1</span>
  </div>
</div>

<hr>
<h5>ชื่อผู้ยื่นแบบ/ผู้รับมอบอำนาจ</h5>

@include('pages.form.userprofile', ['action' => 'readonly'])

<form id="fm_document_yv1">
  <input type="hidden" name="doc_yv1_id">
  <input type="hidden" name="doc_no">
  <hr>
  <h5>ขอยื่นแบบขอยกเว้นค่าธรรมเนียมบำบัดน้ำเสีย ต่อพนักงานเจ้าหน้าที่ ดังต่อไปนี้</h5>
  <h5 class="mt-5">1. ชื่อสถานประกอบการ</h5>
  <div>
    @include('pages.form.addressprofile', ['action' => $action])
  </div>


  <h5 class="mt-5">2. ประเภทของแหล่งกำเนิดน้ำเสีย <span class="text-danger">*</span></h5>
  <div class="mb-2 row align-items-center">
    <label class="col-3 col-form-label ps-4">ประเภทอาคาร <span class="text-danger">*</span></label>
    <div class="col-3">
      <select class="form-select" name="wastewater_source_building_type" @if($action == 'readonly') disabled @endif>
        <option>ไม่พบข้อมูล</option>
      </select>
    </div>
  </div>
  <div class="mb-2 row align-items-center">
    <label class="col-3 col-form-label ps-4">ขนาดของอาคาร <span class="text-danger">*</span></label>
    <div class="col-3">
      <select class="form-select" name="wastewater_source_building_size" @if($action == 'readonly') disabled @endif>
        <option>เลือกขนาดของอาคาร</option>
      </select>
    </div>
  </div>
  <!-- <div class="mb-2 row align-items-center">
    <label class="col-2 col-form-label text-end">อื่น ๆ (ระบุเพิ่มเติม)</label>
    <div class="col-4">
      <input type="text" class="form-control" name="wastewater_source_remark" @if($action == 'readonly') disabled @endif>
    </div>
  </div> -->

  <h5 class="mt-5">3. ข้อมูลเกี่ยวกับระบบบำบัดน้ำเสีย และแหล่งรองรับน้ำทิ้ง</h5>
  <div>
    <!-- <div class="mb-2 row align-items-center">
      <label class="col-6 col-form-label ps-4">3.1 ประเภทและชนิดของระบบบำบัดน้ำเสีย <span class="text-danger">*</span></label>
      <div class="col-6">
        <input type="text" class="form-control" name="treatment_process_name" @if($action == 'readonly') disabled @endif>
      </div>
    </div> -->
    <div class="mb-2 row align-items-center">
      <label class="col-6 col-form-label ps-4">3.1 ประเภทและชนิดของระบบบำบัดน้ำเสีย <span class="text-danger">*</span></label>
      <div class="col-6">
        <div class="form-check">
          <input class="form-check-input" type="radio" name="wastewater_treatment_type" id="wastewater_treatment_type_1" value="1" checked @if($action == 'readonly') disabled @endif>
          <label class="form-check-label" for="wastewater_treatment_type_1">ระบบบำบัดน้ำเสียแบบใช้อากาศ</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="wastewater_treatment_type" id="wastewater_treatment_type_2" value="2" @if($action == 'readonly') disabled @endif>
          <label class="form-check-label" for="wastewater_treatment_type_2">ระบบบำบัดน้ำเสียแบบไม่ใช้อากาศ (Anaerobic process)</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="wastewater_treatment_type" id="wastewater_treatment_type_3" value="3" @if($action == 'readonly') disabled @endif>
          <label class="form-check-label" for="wastewater_treatment_type_3">อื่น ๆ</label>
        </div>
      </div>
      <div class="offset-6 col-6">
        <select class="form-select" name="wastewater_treatment_name_id" @if($action == 'readonly') disabled @endif>
          <option>เลือกชนิดของระบบบำบัดน้ำเสีย</option>
        </select>
      </div>
    </div>
    <div class="mb-2 row align-items-center">
      <label class="col-6 col-form-label ps-4">3.2 ความสามารถในการรองรับน้ำเสียของระบบบำบัดน้ำเสีย <span class="text-danger">*</span></label>
      <div class="col-6">
        <div class="input-group">
          <input type="number" class="form-control" name="treatment_capacity_per_day" @if($action == 'readonly') disabled @endif>
          <span class="input-group-text">ลบ.ม./วัน</span>
        </div>
      </div>
    </div>
    <div class="mb-2 row align-items-center">
      <label class="col-6 col-form-label ps-4">3.3 ปริมาณน้ำเสียที่เข้าระบบบำบัดน้ำเสีย <span class="text-danger">*</span></label>
      <div class="col-6">
        <div class="input-group">
          <input type="number" class="form-control" name="water_treatment_per_month" @if($action == 'readonly') disabled @endif>
          <span class="input-group-text">ลบ.ม./เดือน</span>
        </div>
      </div>
    </div>
    <div class="mb-2 row align-items-center">
      <label class="col-6 col-form-label ps-5">ค่าบีโอดีของน้ำเสียเข้าระบบ <span class="text-danger">*</span></label>
      <div class="col-6">
        <div class="input-group">
          <input type="number" class="form-control" name="bod_treatment_per_month" @if($action == 'readonly') disabled @endif>
          <span class="input-group-text">มิลลิกรัม/ลิตร</span>
        </div>
      </div>
    </div>

    <div class="mb-2 row align-items-center">
      <label class="col-6 col-form-label ps-4">3.4 การระบายน้ำทิ้งจากระบบบำบัดน้ำเสีย <span class="text-danger">*</span></label>
      <div class="col-6">
        <div class="form-check">
          <input class="form-check-input" type="radio" name="water_treatment_to" id="water_treatment_to_1" value="1" checked @if($action == 'readonly') disabled @endif>
          <label class="form-check-label" for="water_treatment_to_1">แหล่งน้ำสาธารณะ</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="water_treatment_to" id="water_treatment_to_2" value="2" @if($action == 'readonly') disabled @endif>
          <label class="form-check-label" for="water_treatment_to_2">ท่อระบายน้ำสาธารณะ หรือท่อรวบรวมน้ำเสียของกรุงเทพมหานคร</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="water_treatment_to" id="water_treatment_to_3" value="3" @if($action == 'readonly') disabled @endif>
          <label class="form-check-label" for="water_treatment_to_3">อื่น ๆ</label>
        </div>
      </div>
    </div>
    <div class="mb-2 row align-items-center">
      <div class="col-6 offset-6">
        <input type="text" class="form-control" placeholder="อื่น ๆ โปรดระบุ" name="water_treatment_to_remark" @if($action == 'readonly') disabled @endif>
      </div>
    </div>

    <!-- <h5>3.3 การควบคุมระบบบำบัดน้ำเสีย</h5>
    <hr>
    <div class="mb-2 row align-items-center">
      <div class="col-6 ps-5">
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="treatment_control_by" id="treatment_control_by_1" value="1" checked @if($action == 'readonly') disabled @endif>
          <label class="form-check-label" for="treatment_control_by_1">เดินระบบเอง</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="treatment_control_by" id="treatment_control_by_2" value="2" @if($action == 'readonly') disabled @endif>
          <label class="form-check-label" for="treatment_control_by_2">จ้างเดินระบบโดย</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="treatment_control_by" id="treatment_control_by_3" value="3" @if($action == 'readonly') disabled @endif>
          <label class="form-check-label" for="treatment_control_by_3">อื่น ๆ โปรดระบุ</label>
        </div>
      </div>
      <label class="col-2 col-form-label text-end">ระบุเพิ่มเติม</label>
      <div class="col-4">
        <input type="text" class="form-control" name="treatment_control_remark" @if($action == 'readonly') disabled @endif>
      </div>
    </div>
    <h5>3.4 การทำงานของระบบบำบัดน้ำเสีย</h5>
    <hr>
    <div class="mb-2 row align-items-center">
      <div class="col-6 ps-5">
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="treatment_work_per_day" id="treatment_work_per_day_1" value="1" checked @if($action == 'readonly') disabled @endif>
          <label class="form-check-label" for="treatment_work_per_day_1">แบบต่อเนื่อง</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="treatment_work_per_day" id="treatment_work_per_day_2" value="2" @if($action == 'readonly') disabled @endif>
          <label class="form-check-label" for="treatment_work_per_day_2">แบบไม่ต่อเนื่อง</label>
        </div>
      </div>
      <label class="col-2 col-form-label text-end">ระบุเพิ่มเติม</label>
      <div class="col-4">
        <input type="text" class="form-control" name="treatment_work_remark" @if($action == 'readonly') disabled @endif>
      </div>
    </div>
    <h5>3.5 ปริมาณน้ำใช้ในทุกกิจกรรมของแหล่งกำเนิดน้ำเสีย</h5>
    <hr>
    <div class="mb-2 row align-items-center">
      <label class="col-2 col-form-label text-end">ปริมาณน้ำใช้ในทุกกิจกรรมของแหล่งกำเนิดน้ำเสีย <span class="text-danger">*</span></label>
      <div class="col-4">
        <div class="input-group">
          <input type="number" class="form-control" name="water_use_per_month" @if($action == 'readonly') disabled @endif>
          <span class="input-group-text">ลบ.ม./เดือน</span>
        </div>
      </div>
    </div>
  </div> -->


  <h5 class="mt-5">4. หลักฐานที่นำมาประกอบการพิจารณาคำร้อง (กรณีบุคคลธรรมดา/นิติบุคคล) <span class="text-danger">(ชนิดไฟล์ที่รองรับได้แก่ PDF/JPEG/PNG และขนาดไม่เกิน 20 MB ต่อไฟล์)</span></h5>
  <div class="mb-2 row align-items-center">
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
        <label class="form-check-label" for="cb_doc_attach_4">รายงานผลการตรวจวิเคราะห์คุณภาพน้ำทิ้งจากหน่วยตรวจวิเคราะห์ของรัฐหรือหน่วยตรวจวิเคราะห์ของเอกชนอย่างน้อย 2 เดือนย้อนหลัง</label>
        <button type="button" class="text-primary opacity-100 d-none px-3" id="txt_doc_attach_4">แสดงเอกสารแนบ</button>
      </div>
      <div class="upload-container mb-2 d-none" id="layout_doc_attach_4">
        <input name="doc_attach_4" type="file" accept="image/png, image/jpeg, application/pdf" size="40" />
      </div>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="cb_doc_attach_5" name="cb_doc_attach_5" attr-layout-target="layout_doc_attach_5" @if($action == 'readonly') disabled @endif>
        <label class="form-check-label" for="cb_doc_attach_5">ผังบริเวณระบบระบายน้ำ และระบบบำบัดน้ำเสีย</label>
        <button type="button" class="text-primary opacity-100 d-none px-3" id="txt_doc_attach_5">แสดงเอกสารแนบ</button>
      </div>
      <div class="upload-container mb-2 d-none" id="layout_doc_attach_5">
        <input name="doc_attach_5" type="file" accept="image/png, image/jpeg, application/pdf" size="40" />
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

<!-- ================== BEGIN BASE JS ================== -->
<script src="{{ asset('assets/plugins/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
<!-- ================== END BASE JS ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
@if ($action == 'readwrite' || $action == 'readonly')
  <script src="{{ asset('js/form-userprofile.js') }}"></script>
  <script src="{{ asset('js/user-profile.js') }}"></script>
  <script src="{{ asset('js/document-comment-log.js') }}"></script>
  <script src="{{ asset('js/form-document-yv1.js') }}"></script>
  <script src="{{ asset('js/document-yv1-read.js') }}"></script>
@endif
@if ($action == 'freeform')
  <script src="{{ asset('js/form-userprofile.js') }}"></script>
  <script src="{{ asset('js/user-profile.js') }}"></script>
  <script src="{{ asset('js/form-document-yv1.js') }}"></script>
  <script src="{{ asset('js/document-yv1-write.js') }}"></script>
@endif
<!-- ================== END PAGE LEVEL JS ================== -->
