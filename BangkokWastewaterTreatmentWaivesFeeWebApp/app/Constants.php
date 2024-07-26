<?php

namespace App;

class Constants {

    public $response_query = ["code" => 1, "developer_message" => "OK", "user_message" => "OK", "data" => null];

    public $response_querylist = ["code" => 1, "developer_message" => "OK", "user_message" => "OK", "results_count" => 0, "results" => []];

    public $response_insert = ["code" => 1, "developer_message" => "OK", "user_message" => "OK", "received_records" => 0];

    public $response_delete = ["code" => 1, "developer_message" => "OK", "user_message" => "OK", "removed_records" => 0];

    public $validation_messages = [
      'required' => 'กรุณากรอก :attribute',
      'required_if' => 'กรุณากรอก :attribute',
      'required_with' => 'กรุณากรอก :attribute',
      'max' => ':attribute มีความยาวเกินไป',
      'integer' => ':attribute ข้อมูลไม่ถูกต้อง',
      'date' => ':attribute ต้องเป็นวันที่เท่านั้น',
      'regex' => ':attribute ข้อมูลไม่ถูกต้อง',
      'in' => ':attribute ข้อมูลไม่ถูกต้อง',
      'numeric' => ':attribute ต้องเป็นตัวเลขเท่านั้น',
      'email' => ':attribute ต้องเป็นอีเมลเท่านั้น',
      'unique' => ':attribute นี้ถูกนำมาใช้แล้ว',
      'confirmed' => 'ยืนยันรหัสผ่านไม่ตรงกัน',
      'digits_between' => ':attribute ไม่อยู่ในช่วงที่กำหนด',
      'min' => ':attribute ตัวเลขไม่ถูกต้อง',
      'between' => ':attribute ตัวเลขไม่ถูกต้อง',
      'exists' => 'ไม่พบข้อมูล :attribute นี้',
    ];


    public $StatusOK = "statusOK";
    public $StatusNOK = "statusNOK";

    /**
    ** Message return for user
    **/
    // Positive
    public $MessageChangePasswordSuccessfully = "เปลี่ยนรหัสผ่านสำเร็จ";
    public $MessageRegisterSuccess = "ลงทะเบียนสมาชิกใหม่สำเร็จ!";
    public $MessageRegisterSuccessConfirmEmail = "ลงทะเบียนผู้ใช้งานใหม่เรียบร้อยแล้ว กรุณาตรวจสอบอีเมลเพื่อยืนยันตัวตน!";
    public $MessageResetPasswordSuccessfully = "รีเซ็ตรหัสผ่านเรียบร้อยแล้ว กรุณาตรวจสอบอีเมลเพื่อกำหนดรหัสผ่านใหม่!";

    public $MessageInsertDataSuccess = "เพิ่มข้อมูลสำเร็จ!";
    public $MessageUpdateDataSuccess = "แก้ไขข้อมูลสำเร็จ!";
    public $MessageDeleteDataSuccess = "ลบข้อมูลสำเร็จ!";

    // Negative
    public $MessageDataNotFound = "ไม่พบข้อมูลในการค้นหา";
    public $MessageAuthenNotSuccess = "บัญชีผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง";
    public $MessageOldPasswordInvalid = "รหัสผ่านเดิมไม่ถูกต้อง";
    public $MessageConfirmPasswordNotMatch = "ยืนยันรหัสผ่านไม่ตรงกัน";
    public $MessageTokenInvalid = "Token ไม่ถูกต้องหรือหมดอายุการใช้งาน กรุณาเข้าสู่ระบบอีกครั้ง";
    public $MessageNotAllowed = "ไม่ได้รับอนุญาตให้เข้าถึง";
    public $MessageEmailNotConfirm = "กรุณายืนยันตัวตนผ่านอีเมล ก่อนเริ่มเข้าใช้งาน";


    /**
    ** Message return for admin
    **/
    public $MessageSystemErrorContactAdmin = "ระบบเกิดข้อผิดพลาด กรุณาติดต่อผู้ดูแลระบบ";
}

?>
