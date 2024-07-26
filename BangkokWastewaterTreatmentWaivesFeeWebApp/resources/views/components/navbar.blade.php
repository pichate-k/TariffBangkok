<div class="nav-scroller py-1 mb-3 border-bottom">
  <nav class="nav nav-underline justify-content-between">
    @if ($active == 'pagehome')
      <a class="nav-item nav-link link-body-emphasis active" href="/">หน้าแรก</a>
    @else
      <a class="nav-item nav-link link-body-emphasis" href="/">หน้าแรก</a>
    @endif

    <div class="dropdown">
      @if ($active == 'pagedocumentyv1' || $active == 'pagedocumentyv2' || $active == 'pagedocumentrb1' || $active == 'pagedocumentpg1' || $active == 'pagedocumentpg2' || $active == 'pagedocumentnt1' || $active == 'pagedocumentnt2')
        <a class="nav-item nav-link link-body-emphasis dropdown-toggle active" data-bs-toggle="dropdown" aria-expanded="false" href="#">ยื่นแบบ</a>
      @else
        <a class="nav-item nav-link link-body-emphasis dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" href="#">ยื่นแบบ</a>
      @endif

      <ul class="dropdown-menu">
       <li>
         @if ($active == 'pagedocumentyv1')
           <a class="dropdown-item py-2 active" href="/u/document/yv1.htm">แบบ ยว.1 คำร้องขอยกเว้นค่าธรรมเนียมบำบัดน้ำเสีย</a>
         @else
           <a class="dropdown-item py-2" href="/u/document/yv1.htm">แบบ ยว.1 คำร้องขอยกเว้นค่าธรรมเนียมบำบัดน้ำเสีย</a>
         @endif
       </li>
       <li>
         @if ($active == 'pagedocumentyv2')
           <a class="dropdown-item py-2 active" href="/u/document/yv2.htm">แบบ ยว.2 คำร้องขอยกเลิกการขอยกเว้นค่าธรรมเนียมบำบัดน้ำเสีย</a>
         @else
           <a class="dropdown-item py-2" href="/u/document/yv2.htm">แบบ ยว.2 คำร้องขอยกเลิกการขอยกเว้นค่าธรรมเนียมบำบัดน้ำเสีย</a>
         @endif
       </li>
       <li>
         @if ($active == 'pagedocumentrb1')
           <a class="dropdown-item py-2 active" href="/u/document/rb1.htm">แบบ รบ.1 คำขอรับบริการบำบัดน้ำเสียของกรุงเทพมหานคร</a>
         @else
           <a class="dropdown-item py-2" href="/u/document/rb1.htm">แบบ รบ.1 คำขอรับบริการบำบัดน้ำเสียของกรุงเทพมหานคร</a>
         @endif
       </li>
       <li>
         @if ($active == 'pagedocumentpg1')
           <a class="dropdown-item py-2 active" href="/u/document/pg1.htm">แบบ ปก.1 คำขอรายงานปริมาณการใช้น้ำหรือปริมาณน้ำเสีย</a>
         @else
           <a class="dropdown-item py-2" href="/u/document/pg1.htm">แบบ ปก.1 คำขอรายงานปริมาณการใช้น้ำหรือปริมาณน้ำเสีย</a>
         @endif
       </li>
       <li>
         @if ($active == 'pagedocumentpg2')
           <a class="dropdown-item py-2 active" href="/u/document/pg2.htm">แบบ ปก.2 รายงานปริมาณการใช้น้ำหรือปริมาณน้ำเสียโดยติดตั้งอุปกรณ์วัดปริมาณน้ำ</a>
         @else
           <a class="dropdown-item py-2" href="/u/document/pg2.htm">แบบ ปก.2 รายงานปริมาณการใช้น้ำหรือปริมาณน้ำเสียโดยติดตั้งอุปกรณ์วัดปริมาณน้ำ</a>
         @endif
       </li>
       <li>
         @if ($active == 'pagedocumentnt1')
           <a class="dropdown-item py-2 active" href="/u/document/nt1.htm">แบบ นท.1 คำขอติดตั้งอุปกรณ์วัดปริมาณน้ำเสียจากแหล่งกำเนิดน้ำเสียที่ใช้น้ำประปา</a>
         @else
           <a class="dropdown-item py-2" href="/u/document/nt1.htm">แบบ นท.1 คำขอติดตั้งอุปกรณ์วัดปริมาณน้ำเสียจากแหล่งกำเนิดน้ำเสียที่ใช้น้ำประปา</a>
         @endif
       </li>
       <li>
         @if ($active == 'pagedocumentnt2')
           <a class="dropdown-item py-2 active" href="/u/document/nt2.htm">แบบ นท.2 รายงานปริมาณน้ำเสียโดยติดตั้งอุปกรณ์วัดปริมาณน้ำเสียจากแหล่งกำเนิดน้ำเสียที่ใช้น้ำประปา</a>
         @else
           <a class="dropdown-item py-2" href="/u/document/nt2.htm">แบบ นท.2 รายงานปริมาณน้ำเสียโดยติดตั้งอุปกรณ์วัดปริมาณน้ำเสียจากแหล่งกำเนิดน้ำเสียที่ใช้น้ำประปา</a>
         @endif
       </li>
      </ul>
    </div>

    @if ($active == 'pagedocumentstatus')
      <a class="nav-item nav-link link-body-emphasis active" href="/u/document/status.htm">ติดตามสถานะ</a>
    @else
      <a class="nav-item nav-link link-body-emphasis" href="/u/document/status.htm">ติดตามสถานะ</a>
    @endif

    @if ($active == 'pagewaterqualitysubmit')
      <a class="nav-item nav-link link-body-emphasis active" href="/u/waterquality/submit.htm">รายงานผลตรวจวัดคุณภาพน้ำ</a>
    @else
      <a class="nav-item nav-link link-body-emphasis" href="/u/waterquality/submit.htm">รายงานผลตรวจวัดคุณภาพน้ำ</a>
    @endif

    @if ($active == 'pagedocumenthistory')
      <a class="nav-item nav-link link-body-emphasis active" href="/u/document/history.htm">ประวัติการยื่นแบบ</a>
    @else
      <a class="nav-item nav-link link-body-emphasis" href="/u/document/history.htm">ประวัติการยื่นแบบ</a>
    @endif

    <div class="dropdown">

      @if ($active == 'pageuserprofile' || $active == 'pageuserchangepassword')
        <a class="nav-item nav-link link-body-emphasis dropdown-toggle active" data-bs-toggle="dropdown" aria-expanded="false" href="#">บัญชีผู้ใช้งาน</a>
      @else
        <a class="nav-item nav-link link-body-emphasis dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" href="#">บัญชีผู้ใช้งาน</a>
      @endif


      <ul class="dropdown-menu">
       <li>
         @if ($active == 'pageuserprofile')
           <a class="dropdown-item py-2 active" href="/u/user/profile.htm">ตั้งค่าผู้ใช้งาน</a>
         @else
           <a class="dropdown-item py-2" href="/u/user/profile.htm">ตั้งค่าผู้ใช้งาน</a>
         @endif
       </li>
       <!-- <li>
         @if ($active == 'pageuserwastewateraddress')
           <a class="dropdown-item py-2 active" href="/u/user/wastewater/address.htm">ที่อยู่แหล่งกำเนิดน้ำเสีย</a>
         @else
           <a class="dropdown-item py-2" href="/u/user/wastewater/address.htm">ที่อยู่แหล่งกำเนิดน้ำเสีย</a>
         @endif
       </li> -->
       <li>
         @if ($active == 'pageuserchangepassword')
           <a class="dropdown-item py-2 active" href="/u/user/changepassword.htm">เปลี่ยนรหัสผ่าน</a>
         @else
           <a class="dropdown-item py-2" href="/u/user/changepassword.htm">เปลี่ยนรหัสผ่าน</a>
         @endif
       </li>
       <li>
         <form class="m-0" method="POST" action="{{ route('logout') }}">
         @csrf
         <a class="dropdown-item py-2" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" id="btn_logout">ออกจากระบบ</a></li>
         </form>
     </ul>
   </div>
  </nav>
</div>
