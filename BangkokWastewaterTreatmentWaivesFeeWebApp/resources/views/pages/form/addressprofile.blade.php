<div class="mb-2 row align-items-center">
  <label class="col-3 col-form-label ps-4">ชื่อเจ้าของ <span class="text-danger">*</span></label>
  <div class="col-3">
    <input type="text" class="form-control" name="address_owner" @if($action == 'readonly') disabled @endif>
  </div>
</div>
<div class="mb-2 row align-items-center">
  <label class="col-3 col-form-label ps-4">ชื่ออาคาร/สถานประกอบการ <span class="text-danger">*</span></label>
  <div class="col-3">
    <input type="text" class="form-control" name="address_name" @if($action == 'readonly') disabled @endif>
  </div>
  <label class="col-3 col-form-label ps-4">รหัสผู้ใช้น้ำ</label>
  <div class="col-3">
    <input type="text" class="form-control" name="address_code" @if($action == 'readonly') disabled @endif>
  </div>
</div>
<div class="mb-2 row align-items-center">
  <label class="col-3 col-form-label ps-4">บ้านเลขที่ <span class="text-danger">*</span></label>
  <div class="col-3">
    <input type="text" class="form-control" name="address" @if($action == 'readonly') disabled @endif>
  </div>
  <label class="col-3 col-form-label ps-4">หมู่</label>
  <div class="col-3">
    <input type="text" class="form-control" name="moo" @if($action == 'readonly') disabled @endif>
  </div>
</div>
<div class="mb-2 row align-items-center">
  <label class="col-3 col-form-label ps-4">ตรอก / ซอย</label>
  <div class="col-3">
    <input type="text" class="form-control" name="soi" @if($action == 'readonly') disabled @endif>
  </div>
  <label class="col-3 col-form-label ps-4">ถนน</label>
  <div class="col-3">
    <input type="text" class="form-control" name="road" @if($action == 'readonly') disabled @endif>
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
    <input type="number" class="form-control" name="zip_code" @if($action == 'readonly') disabled @endif>
  </div>
</div>
<div class="mb-2 row align-items-center">
  <label class="col-3 col-form-label ps-4">โทรศัพท์</label>
  <div class="col-3">
    <input type="text" class="form-control" name="telephone" @if($action == 'readonly') disabled @endif>
  </div>
  <label class="col-3 col-form-label ps-4">อีเมล</label>
  <div class="col-3">
    <input type="text" class="form-control" name="email" @if($action == 'readonly') disabled @endif>
  </div>
  <!-- <label class="col-3 col-form-label ps-4">โทรศัพท์เคลื่อนที่</label>
  <div class="col-3">
    <input type="text" class="form-control" name="mobile_phone" @if($action == 'readonly') disabled @endif>
  </div> -->
</div>
<div class="mb-2 row align-items-center">
  <!-- <label class="col-3 col-form-label ps-4">โทรสาร</label>
  <div class="col-3">
    <input type="text" class="form-control" name="fax" @if($action == 'readonly') disabled @endif>
  </div> -->
</div>
<div class="mb-2 row align-items-center">
  <label class="col-3 col-form-label ps-4">ละติจูด</label>
  <div class="col-3">
    <input type="number" class="form-control" name="latitude" @if($action == 'readonly') disabled @endif>
  </div>
  <label class="col-3 col-form-label ps-4">ลองจิจูด</label>
  <div class="col-3">
    <input type="number" class="form-control" name="longitude" @if($action == 'readonly') disabled @endif>
  </div>
</div>
<div class="mb-2 row align-items-center">
  <label class="col-3 col-form-label ps-4">ประกอบกิจการประเภท</label>
  <div class="col-3">
    <input type="text" class="form-control" name="business_type" @if($action == 'readonly') disabled @endif>
  </div>
</div>
