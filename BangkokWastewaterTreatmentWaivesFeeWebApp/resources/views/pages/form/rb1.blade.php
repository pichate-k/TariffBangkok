
@if ($action == 'readwrite' || $action == 'readonly')
  @include('pages.form.documentcommentlog')
@endif

<!-- Header Document Name -->
<div class="d-flex">
  <div class="p-2 w-100">
    <h5 class="text-center">คำขอรับบริการบำบัดน้ำเสียของกรุงเทพมหานคร
    </h5>
  </div>
  <div class="p-2 flex-shrink-1">
    <span class="text-nowrap border border-black p-2">แบบ รบ.1</span>
  </div>
</div>

<hr>
<h5>ชื่อผู้ยื่นแบบ/ผู้รับมอบอำนาจ</h5>

@include('pages.form.userprofile', ['action' => 'readonly'])

<form id="fm_document_rb1">
  <input type="hidden" name="doc_rb1_id">
  <input type="hidden" name="doc_no">
  <hr>
  <h5>ขอยื่นคำขอรับบริการบำบัดน้ำเสียของกรุงเทพมหานคร ต่อพนักงานเจ้าหน้าที่ ดังต่อไปนี้</h5>
  <h5 class="mt-5">1. ชื่อสถานประกอบการ</h5>
  <div>

    @include('pages.form.addressprofile', ['action' => $action])

    <!-- <div class="mb-3 row align-items-center">
      <label class="col-3 col-form-label ps-4">โดยมี (ชื่อ-นามสกุล) <span class="text-danger">*</span></label>
      <div class="col-3">
        <input type="text" class="form-control" name="land_owner_name" @if($action == 'readonly') disabled @endif>
      </div>
      <label class="col-3 col-form-label ps-4">เป็นเจ้าของอาคารที่ดินโฉนดเลขที่/น.ส.3 เลขที่ /ส.ค.1 เลขที่ <span class="text-danger">*</span></label>
      <div class="col-3">
        <input type="text" class="form-control" name="land_number" @if($action == 'readonly') disabled @endif>
      </div>
    </div>
    <div class="mb-3 row align-items-center">
      <label class="col-3 col-form-label ps-4">เป็นที่ดิน <span class="text-danger">*</span></label>
      <div class="col-3">
        <input type="text" class="form-control" name="land_description" @if($action == 'readonly') disabled @endif>
      </div>
      <label class="col-3 col-form-label ps-4">ประเภทการใช้อาคาร <span class="text-danger">*</span></label>
      <div class="col-3">
        <input type="text" class="form-control" name="land_building_type" @if($action == 'readonly') disabled @endif>
      </div>
    </div> -->
  </div>


  <h5 class="mt-5">มีการต่อเชื่อมท่อน้ำเสีย</h5>
  <div>
    <div class="mb-3 row align-items-center">
      <label class="col-4 col-form-label ps-4">2. มีการต่อเชื่อมท่อน้ำเสีย</label>
      <div class="col-6">
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="connect_pipe" id="connect_pipe_1" value="1" checked @if($action == 'readonly') disabled @endif>
          <label class="form-check-label" for="connect_pipe_1">ทำการต่อเชื่อมท่อน้ำเสีย</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="connect_pipe" id="connect_pipe_0" value="0" @if($action == 'readonly') disabled @endif>
          <label class="form-check-label" for="connect_pipe_0">ไม่มีการต่อเชื่อมท่อน้ำเสีย</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="connect_pipe" id="connect_pipe_2" value="2" @if($action == 'readonly') disabled @endif>
          <label class="form-check-label" for="connect_pipe_2">ไม่ระบุ</label>
        </div>
      </div>
    </div>
    <div class="mb-3 row align-items-center">
      <div class="col-5 offset-4">
        <input type="text" class="form-control" placeholder="ถ้ามี ระบุบริเวณ" name="address_connect_pipe" @if($action == 'readonly') readonly @endif>
      </div>
    </div>
  </div>


  <h5 class="mt-5">ตามผังบริเวณ แบบขยายบ่อพักและท่อระบายน้ำ แบบขยายบ่อบำบัด รายการคำนวณและอื่น ๆ ที่แนบมาพร้อมนี้</h5>
  <div>
    <div class="mb-3 row align-items-center">
      <label class="col-3 col-form-label ps-4">3. ชื่อ - นามสกุล <br>ผู้ออกแบบและคำนวณ <span class="text-danger">*</span></label>
      <div class="col-3">
        <input type="text" class="form-control" name="pool_engineer_name" @if($action == 'readonly') readonly @endif>
      </div>
      <label class="col-3 col-form-label ps-4">4. วันที่กำหนดแล้วเสร็จ <br>(นับตั้งแต่วันที่ได้รับแจ้งให้ก่อสร้าง) <span class="text-danger">*</span></label>
      <div class="col-3">
        <div class="input-group">
          <input type="text" class="form-control datetimepicker" name="pool_approve_date" @if($action == 'readonly') readonly @endif>
          <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
        </div>
      </div>
    </div>
  </div>

  <h5 class="mt-5">4. พร้อมคำขอนี้ ข้าพเจ้าได้แนบเอกสารหลักฐานประกอบการพิจารณาคำขออนุญาตดังต่อไปนี้ <span class="text-danger">(ชนิดไฟล์ที่รองรับได้แก่ PDF/JPEG/PNG และขนาดไม่เกิน 20 MB ต่อไฟล์)</span></h5>
  <div class="mb-3 row align-items-center">
    <div class="col-11 offset-1">
      <h5 class="mb-4">4.1 เอกสารยืนยันตัวตน</h5>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="cb_doc_attach_1" name="cb_doc_attach_1" attr-layout-target="layout_doc_attach_1" @if($action == 'readonly') disabled @endif>
        <label class="form-check-label" for="cb_doc_attach_1">4.1.1 สำเนาหนังสือรับรองการจดทะเบียนนิติบุคคลออกให้ไม่เกิน 6 เดือน (กรณีนิติบุคคล)</label>
        <button type="button" class="text-primary opacity-100 d-none px-3" id="txt_doc_attach_1">แสดงเอกสารแนบ</button>
      </div>
      <div class="upload-container mb-2 d-none" id="layout_doc_attach_1">
        <input name="doc_attach_1" type="file" accept="image/png, image/jpeg, application/pdf" size="40" />
      </div>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="cb_doc_attach_2" name="cb_doc_attach_2" attr-layout-target="layout_doc_attach_2" @if($action == 'readonly') disabled @endif>
        <label class="form-check-label" for="cb_doc_attach_2">4.1.2 หนังสือเดินทางและใบอนุญาตทำงาน (กรณีบุคคลต่างด้าว)</label>
        <button type="button" class="text-primary opacity-100 d-none px-3" id="txt_doc_attach_2">แสดงเอกสารแนบ</button>
      </div>
      <div class="upload-container mb-2 d-none" id="layout_doc_attach_2">
        <input name="doc_attach_2" type="file" accept="image/png, image/jpeg, application/pdf" size="40" />
      </div>
      <h5 class="my-4">4.2 เอกสารอื่น ๆ สำหรับยื่นเพิ่มเติม</h5>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="cb_doc_attach_3" name="cb_doc_attach_3" attr-layout-target="layout_doc_attach_3" @if($action == 'readonly') disabled @endif>
        <label class="form-check-label" for="cb_doc_attach_3">4.2.1 หนังสือมอบอำนาจที่ถูกต้องตามกฎหมาย (ถ้ามี)</label>
        <button type="button" class="text-primary opacity-100 d-none px-3" id="txt_doc_attach_3">แสดงเอกสารแนบ</button>
      </div>
      <div class="upload-container mb-2 d-none" id="layout_doc_attach_3">
        <input name="doc_attach_3" type="file" accept="image/png, image/jpeg, application/pdf" size="40" />
      </div>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="cb_doc_attach_4" name="cb_doc_attach_4" attr-layout-target="layout_doc_attach_4" @if($action == 'readonly') disabled @endif>
        <label class="form-check-label" for="cb_doc_attach_4">4.2.2 หลักฐานแสดงกรรมสิทธิ์หรือสิทธิในการใช้อาคารเป็นสถานประกอบการ เช่น โฉนดที่ดิน สัญญาที่ซื้อขาย สัญญาเช่า หนังสือยินยอมให้ใช้อาคาร เป็นต้น</label>
        <button type="button" class="text-primary opacity-100 d-none px-3" id="txt_doc_attach_4">แสดงเอกสารแนบ</button>
      </div>
      <div class="upload-container mb-2 d-none" id="layout_doc_attach_4">
        <input name="doc_attach_4" type="file" accept="image/png, image/jpeg, application/pdf" size="40" />
      </div>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="cb_doc_attach_5" name="cb_doc_attach_5" attr-layout-target="layout_doc_attach_5" @if($action == 'readonly') disabled @endif>
        <label class="form-check-label" for="cb_doc_attach_5">4.2.3 รายละเอียดการบำบัดน้ำเสีย ประกอบด้วย รายการปริมาณการใช้น้ำและปริมาณน้ำเสียที่เป็นปัจจุบัน แบบแสดงรายละเอียดระบบบำบัดน้ำเสียขั้นต้น และรายละเอียดการระบายน้ำเสีย (แล้วแต่กรณีการระบายน้ำเสีย) พร้อมรายการคำนวณ และวิศวกรสาขาวิศวกรรมสิ่งแวดล้อม เป็นผู้ลงนามรับรองการออกแบบคำนวณ</label>
        <button type="button" class="text-primary opacity-100 d-none px-3" id="txt_doc_attach_5">แสดงเอกสารแนบ</button>
      </div>
      <div class="upload-container mb-2 d-none" id="layout_doc_attach_5">
        <input name="doc_attach_5" type="file" accept="image/png, image/jpeg, application/pdf" size="40" />
      </div>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="cb_doc_attach_6" name="cb_doc_attach_6" attr-layout-target="layout_doc_attach_6" @if($action == 'readonly') disabled @endif>
        <label class="form-check-label" for="cb_doc_attach_6">4.2.4 สำเนาใบอนุญาตเป็นผู้ประกอบวิชาชีพวิศวกรรมควบคุม สาขาวิศวกรรมสิ่งแวดล้อมของผู้ออกแบบและคำนวณระบบบำบัดน้ำเสีย</label>
        <button type="button" class="text-primary opacity-100 d-none px-3" id="txt_doc_attach_6">แสดงเอกสารแนบ</button>
      </div>
      <div class="upload-container mb-2 d-none" id="layout_doc_attach_6">
        <input name="doc_attach_6" type="file" accept="image/png, image/jpeg, application/pdf" size="40" />
      </div>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="cb_doc_attach_7" name="cb_doc_attach_7" attr-layout-target="layout_doc_attach_7" @if($action == 'readonly') disabled @endif>
        <label class="form-check-label" for="cb_doc_attach_7">4.2.5 ผลการตรวจวิเคราะห์คุณภาพน้ำก่อนเข้าระบบและออกจากระบบบำบัดน้ำเสียของอาคารย้อนหลังหนึ่งเดือนก่อนการต่อเชื่อมท่อน้ำเสีย ซึ่งวิเคราะห์โดยหน่วยตรวจวิเคราะห์ของรัฐ หรือหน่วยตรวจวิเคราะห์เอกชน</label>
        <button type="button" class="text-primary opacity-100 d-none px-3" id="txt_doc_attach_7">แสดงเอกสารแนบ</button>
      </div>
      <div class="upload-container mb-2 d-none" id="layout_doc_attach_7">
        <input name="doc_attach_7" type="file" accept="image/png, image/jpeg, application/pdf" size="40" />
      </div>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="cb_doc_attach_8" name="cb_doc_attach_8" attr-layout-target="layout_doc_attach_8" @if($action == 'readonly') disabled @endif>
        <label class="form-check-label" for="cb_doc_attach_8">4.2.6 การเชื่อมต่อท่อน้ำเสีย กรณีต่อเชื่อมท่อน้ำเสียเข้าสู่บ่อพักท่อรวบรวมน้ำเสียของกรุงเทพมหานคร แบบแสดงรายละเอียด ประกอบด้วย แนวการวางท่อจากอาคารไปยังบ่อพักท่อรวบรวมน้ำเสีย แบบแสดงรายละเอียดการติดตั้งท่อน้ำเสียภายในบ่อพักท่อรวบรวมน้ำเสีย และวิศวกรสาขาวิศวกรรมสิ่งแวดล้อม เป็นผู้ลงนามรับรองการออกแบบคำนวณ</label>
        <button type="button" class="text-primary opacity-100 d-none px-3" id="txt_doc_attach_8">แสดงเอกสารแนบ</button>
      </div>
      <div class="upload-container mb-2 d-none" id="layout_doc_attach_8">
        <input name="doc_attach_8" type="file" accept="image/png, image/jpeg, application/pdf" size="40" />
      </div>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="cb_doc_attach_9" name="cb_doc_attach_9" attr-layout-target="layout_doc_attach_9" @if($action == 'readonly') disabled @endif>
        <label class="form-check-label" for="cb_doc_attach_9">4.2.7 แบบแสดงรายละเอียดของบ่อหน่วงน้ำเสีย กรณีต่อเชื่อมน้ำเสียเข้าสู่บ่อพักท่อระบายน้ำสาธารณะของกรุงเทพมหานคร ตามข้อ 9 (2) ต้องมีอุปกรณ์สำหรับเปิด-ปิดน้ำเสียเพื่อมิให้ระบายน้ำเสียในช่วงฝนตก พร้อมลงลายมือชื่อของวิศวกรสาขาวิศวกรรมสิ่งแวดล้อม เป็นผู้ลงนามรับรองการออกแบบคำนวณ</label>
        <button type="button" class="text-primary opacity-100 d-none px-3" id="txt_doc_attach_9">แสดงเอกสารแนบ</button>
      </div>
      <div class="upload-container mb-2 d-none" id="layout_doc_attach_9">
        <input name="doc_attach_9" type="file" accept="image/png, image/jpeg, application/pdf" size="40" />
      </div>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="cb_doc_attach_10" name="cb_doc_attach_10" attr-layout-target="layout_doc_attach_10" @if($action == 'readonly') disabled @endif>
        <label class="form-check-label" for="cb_doc_attach_10">4.2.8 หนังสืออนุญาตระบายน้ำทิ้ง/เชื่อมท่อระบายน้ำ กรณีเชื่อมท่อน้ำเสียเข้าสู่บ่อพักท่อระบายน้ำสาธารณะของกรุงเทพมหานคร</label>
        <button type="button" class="text-primary opacity-100 d-none px-3" id="txt_doc_attach_10">แสดงเอกสารแนบ</button>
      </div>
      <div class="upload-container mb-2 d-none" id="layout_doc_attach_10">
        <input name="doc_attach_10" type="file" accept="image/png, image/jpeg, application/pdf" size="40" />
      </div>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="cb_doc_attach_11" name="cb_doc_attach_11" attr-layout-target="layout_doc_attach_11" @if($action == 'readonly') disabled @endif>
        <label class="form-check-label" for="cb_doc_attach_11">4.2.9 เอกสารอื่น ๆ (ถ้ามี)</label>
        <button type="button" class="text-primary opacity-100 d-none px-3" id="txt_doc_attach_11">แสดงเอกสารแนบ</button>
      </div>
      <div class="upload-container mb-2 d-none" id="layout_doc_attach_11">
        <input name="doc_attach_11" type="file" accept="image/png, image/jpeg, application/pdf" size="40" />
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
  <script src="{{ asset('js/form-document-rb1.js') }}"></script>
  <script src="{{ asset('js/document-rb1-read.js') }}"></script>
@endif
@if ($action == 'freeform')
  <script src="{{ asset('js/form-userprofile.js') }}"></script>
  <script src="{{ asset('js/user-profile.js') }}"></script>
  <script src="{{ asset('js/form-document-rb1.js') }}"></script>
  <script src="{{ asset('js/document-rb1-write.js') }}"></script>
@endif
<!-- ================== END PAGE LEVEL JS ================== -->
