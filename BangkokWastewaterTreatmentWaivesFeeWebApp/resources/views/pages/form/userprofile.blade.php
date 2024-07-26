<form id="fm_user_profile">
  <div class="mb-2 row align-items-center">
    <label class="col-3 col-form-label ps-4">คำนำหน้าชื่อ <span class="text-danger">*</span></label>
    <div class="col-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="name_title" id="name_title1" value="1" checked @if($action == 'readonly') disabled @endif>
        <label class="form-check-label">นาย</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="name_title" id="name_title2" value="2" @if($action == 'readonly') disabled @endif>
        <label class="form-check-label">นาง</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="name_title" id="name_title3" value="3" @if($action == 'readonly') disabled @endif>
        <label class="form-check-label">นางสาว</label>
      </div>
    </div>
    <label class="col-3 col-form-label ps-4">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
    <div class="col-3">
      <input type="text" class="form-control" name="name" @if($action == 'readonly') readonly @endif>
    </div>
  </div>
  <div class="mb-2 row align-items-center">
    <label class="col-3 col-form-label ps-4">ประเภท <span class="text-danger">*</span></label>
    <div class="col-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="user_type" id="user_type1" value="1" checked @if($action == 'readonly') disabled @endif>
        <label class="form-check-label">บุคคลธรรมดา</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="user_type" id="user_type2" value="2" @if($action == 'readonly') disabled @endif>
        <label class="form-check-label">นิติบุคคล</label>
      </div>
    </div>
  </div>
  <!-- Layout personal -->
  <div id="layout_personal">
    <div class="mb-2 row align-items-center">
      <!-- <label class="col-3 col-form-label ps-4">อายุ <span class="text-danger">*</span></label>
      <div class="col-3">
        <input type="text" class="form-control" name="age" @if($action == 'readonly') readonly @endif>
      </div> -->
      <label class="col-3 col-form-label ps-4">สัญชาติ <span class="text-danger">*</span></label>
      <div class="col-3">
        <input type="text" class="form-control" name="nationality" @if($action == 'readonly') readonly @endif>
      </div>
      <label class="col-3 col-form-label ps-4">เลขประจำตัวประชาชน <span class="text-danger">*</span></label>
      <div class="col-3">
        <input type="text" class="form-control" name="tax_id" @if($action == 'readonly') readonly @endif>
      </div>
    </div>
  </div>

  <!-- Layout company -->
  <div id="layout_company" class="d-none">
    <div class="mb-2 row align-items-center">
      <label class="col-3 col-form-label ps-4">นิติบุคคลประเภท <span class="text-danger">*</span></label>
      <div class="col-3">
        <select class="form-select" name="company_type_id" @if($action == 'readonly') disabled @endif>
          <option value="1">บริษัทจำกัด</option>
          <option value="2">ห้างหุ้นส่วนจำกัด</option>
          <option value="3">ห้างหุ้นส่วนสามัญจดทะเบียน</option>
          <option value="4">สมาคม</option>
          <option value="5">มูลนิธิ</option>
        </select>
      </div>
      <!-- <label class="col-3 col-form-label ps-4">จดทะเบียนเมื่อ <span class="text-danger">*</span></label>
      <div class="col-3">
        <div class="input-group">
          <input type="text" class="form-control datetimepicker" name="company_register_date" @if($action == 'readonly') readonly @endif>
          <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
        </div>
      </div> -->
      <label class="col-3 col-form-label ps-4">ทะเบียนนิติบุคคลเลขที่ <span class="text-danger">*</span></label>
      <div class="col-3">
        <input type="text" class="form-control" name="company_tax_id" @if($action == 'readonly') readonly @endif>
      </div>
    </div>
  </div>

  <div class="mb-2 row align-items-center">
    <label class="col-3 col-form-label ps-4" id="label_address_personal">อยู่บ้านเลขที่ <span class="text-danger">*</span></label>
    <label class="col-3 col-form-label ps-4 d-none" id="label_address_company">สำนักงานใหญ่ตั้งอยู่เลขที่ <span class="text-danger">*</span></label>
    <div class="col-3">
      <input type="text" class="form-control" name="address" @if($action == 'readonly') readonly @endif>
    </div>
    <label class="col-3 col-form-label ps-4">หมู่ที่</label>
    <div class="col-3">
      <input type="text" class="form-control" name="moo" @if($action == 'readonly') readonly @endif>
    </div>
  </div>
  <div class="mb-2 row align-items-center">
    <label class="col-3 col-form-label ps-4">ตรอก / ซอย</label>
    <div class="col-3">
      <input type="text" class="form-control" name="soi" @if($action == 'readonly') readonly @endif>
    </div>
    <label class="col-3 col-form-label ps-4">ถนน</label>
    <div class="col-3">
      <input type="text" class="form-control" name="road" @if($action == 'readonly') readonly @endif>
    </div>
  </div>
  <div class="mb-2 row align-items-center">
    <label class="col-3 col-form-label ps-4">จังหวัด <span class="text-danger">*</span></label>
    <div class="col-3">
      <select class="form-select" name="province_code" @if($action == 'readonly') disabled @endif>
      </select>
    </div>
    <label class="col-3 col-form-label ps-4">อำเภอ/เขต <span class="text-danger">*</span></label>
    <div class="col-3">
      <select class="form-select" name="district_code" @if($action == 'readonly') disabled @endif>
      </select>
    </div>
  </div>
  <div class="mb-2 row align-items-center">
    <label class="col-3 col-form-label ps-4">ตำบล/แขวง <span class="text-danger">*</span></label>
    <div class="col-3">
      <select class="form-select" name="sub_district_code" @if($action == 'readonly') disabled @endif>
      </select>
    </div>
    <label class="col-3 col-form-label ps-4">รหัสไปรษณีย์ <span class="text-danger">*</span></label>
    <div class="col-3">
      <input type="number" class="form-control" name="zip_code" @if($action == 'readonly') readonly @endif>
    </div>
  </div>
  <div class="mb-2 row align-items-center">
    <label class="col-3 col-form-label ps-4">โทรศัพท์</label>
    <div class="col-3">
      <input type="text" class="form-control" name="telephone" @if($action == 'readonly') readonly @endif>
    </div>
    <label class="col-3 col-form-label ps-4">โทรศัพท์เคลื่อนที่ <span class="text-danger">*</span></label>
    <div class="col-3">
      <input type="text" class="form-control" name="mobile_phone" @if($action == 'readonly') readonly @endif>
    </div>
  </div>
  <div class="mb-2 row align-items-center">
    <!-- <label class="col-3 col-form-label ps-4">โทรสาร</label>
    <div class="col-3">
      <input type="text" class="form-control" name="fax" @if($action == 'readonly') readonly @endif>
    </div> -->
    <label class="col-3 col-form-label ps-4">อีเมล <span class="text-danger">*</span></label>
    <div class="col-3">
      <input type="text" class="form-control" name="email" value="{{ Auth::user()->username }}" readonly>
    </div>
  </div>
</form>
