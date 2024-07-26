<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailController extends Mailable
{
    use Queueable, SerializesModels;

    public $details;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
      $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

      if($this->details["mail_type"] == "email_verify"){
        return $this->subject("ยืนยันอีเมลสำหรับลงทะเบียนผู้ใช้งานใหม่")->view('emails.emailverification');
      } else if($this->details["mail_type"] == "reset_password"){
        return $this->subject("กำหนดรหัสผ่านใหม่")->view('emails.userresetpassword');
      } else if($this->details["mail_type"] == "confirm_document_created"){
        return $this->subject("ระบบได้รับการยื่นแบบของคุณแล้ว")->view('emails.confirmdocumentcreated');
      } else if($this->details["mail_type"] == "document_completed"){
        return $this->subject("ผลการตรวจสอบเอกสารการยื่นแบบ: การยื่นแบบถูกต้อง")->view('emails.documentnotification');
      } else if($this->details["mail_type"] == "document_reject"){
        return $this->subject("ผลการตรวจสอบเอกสารการยื่นแบบ: มีข้อบกพร้อง")->view('emails.documentnotification');
      } else if($this->details["mail_type"] == "document_cancel"){
        return $this->subject("ระบบได้ทำการยกเลิกการยื่นแบบของคุณแล้ว")->view('emails.documentnotification');
      } else if($this->details["mail_type"] == "document_approved"){
        return $this->subject("ระบบได้ทำการอนุมัติการยื่นแบบของคุณแล้ว")->view('emails.documentnotification');
      } else if($this->details["mail_type"] == "confirm_submit_waterquality"){
        return $this->subject("ระบบได้รับผลตรวจวัดคุณภาพน้ำของคุณแล้ว")->view('emails.confirmsubmitwaterquality');
      } else if($this->details["mail_type"] == "waterquality_submit_approved"){
        return $this->subject("ผลการตรวจสอบเอกสารคุณภาพน้ำ: ได้รับอนุมัติแล้ว")->view('emails.waterqualitysubmitnofitication');
      } else if($this->details["mail_type"] == "waterquality_submit_reject"){
        return $this->subject("ผลการตรวจสอบเอกสารคุณภาพน้ำ: ไม่ได้รับอนุมัติแล้ว")->view('emails.waterqualitysubmitnofitication');
      } else if($this->details["mail_type"] == "confirm_resetpassword"){
        return $this->subject("ยืนยันการเปลี่ยนรหัสผ่าน")->view('emails.confirmresetpassword');
      }

    }
}
