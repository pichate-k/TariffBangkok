<!DOCTYPE html>
<html>
<head>
    <title></title>
    <style>
      body {
        width: 600px;
        border: 1px solid;
        border-radius: 40px;
      }

      img {
        display: block;
        margin-left: auto;
        margin-right: auto;
        padding-top: 10px
      }

      .header {
        color: #fff;
        border-radius: 40px 40px 0px 0px;
        background-color: green;
        padding-bottom: 5px;
      }

      .body {
        margin-left: 50px;
      }

      .footer {
        color: #fff;
        padding-top: 5px;
        padding-bottom: 5px;
        border-radius: 0px 0px 40px 40px;
        background-color: green;
      }

      .text-center {
        text-align: center;
      }
    </style>
</head>
<body>

    <div class="header">
      <img src="http://tariffconnect.bangkok.go.th/imgs/logo_bkk_2.png" with="120" height="120">
      <h1 class="text-center">ระบบการยื่นแบบคำร้อง/คำขอ/รายงาน ที่เกี่ยวข้องกับการจัดเก็บค่าธรรมเนียมบำบัดน้ำเสีย</h1>
    </div>


    <div class="body">
      <h3>เรียน ผู้ยื่นคำร้อง/คำขอ/รายงาน</h3>
      <h4>เรื่อง {{ $details['document_type'] }}</h4>
      <br>

      @if ($details['document_status'] == 1)
        <p>เจ้าหน้าที่ได้ทำการตรวจสอบคำร้องหมายเลข: <b>{{ $details['document_no'] }}</b></p>
        <p>ผลการตรวจสอบเอกสาร: <span style='color: green'>{{ $details['document_status_desc'] }}</span></p>
        <p>สามารถตรวจสอบการยื่นแบบของท่านได้ที่เว็บไซต์ https://tariffconnect.bangkok.go.th/</p>
      @endif

      @if ($details['document_status'] == 0)
        <p>ท่านได้ทำการยกเลิกคำร้องหมายเลข: <span style='color: red'>{{ $details['document_no'] }}</span></p>
        <p>สามารถตรวจสอบการยื่นแบบของท่านได้ที่เว็บไซต์ https://tariffconnect.bangkok.go.th/</p>
      @endif

      @if ($details['document_status'] == 50)
        <p>การยื่นแบบของท่านได้รับการอนุมัติแล้ว: <span style='color: green'>{{ $details['document_no'] }}</span></p>
        <p>สามารถตรวจสอบการยื่นแบบของท่านได้ที่เว็บไซต์ https://tariffconnect.bangkok.go.th/</p>
      @endif

      @if ($details['document_status'] != 1 && $details['document_status'] != 0 && $details['document_status'] != 50)
        <p>เจ้าหน้าที่ได้ทำการตรวจสอบคำร้องหมายเลข: <b>{{ $details['document_no'] }}</b></p>
        <p>ผลการตรวจสอบเอกสาร: <span style='color: red'>{{ $details['document_status_desc'] }}</span></p>
        <p>โปรดดำเนินการแก้ไขภายในวันที่ <span style='color: red'>{{ $details['deadline_submit_doc'] }}</span> ได้ที่เว็บไซต์ https://tariffconnect.bangkok.go.th/</p>
      @endif

      <br>
      <p>ขอแสดงความนับถือ</p>
      <p>สำนักการระบายน้ำ กรุงเทพมหานคร</p>
      <p>โปรดอย่าตอบกลับอีเมลนี้ เนื่องจากอีเมลนี้สร้างขึ้นโดยระบบอัตโนมัติ</p>
    </div>


    <div class="footer">
      <p class="text-center">กลุ่มงานระบบข้อมูลและบริหารการจัดเก็บค่าธรรมเนียม</p>
      <p class="text-center">สำนักงานจัดการคุณภาพน้ำ สำนักการระบายน้ำ</p>
      <p class="text-center">โทร. 02-203-2657 อีเมล wastewatertariff@bangkok.go.th</p>
    </div>

</body>
</html>
