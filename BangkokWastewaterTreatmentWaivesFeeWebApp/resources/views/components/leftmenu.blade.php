<style>
body {
  min-height: 100vh;
  min-height: -webkit-fill-available;
}


main {
  height: 100vh;
  height: -webkit-fill-available;
  max-height: 100vh;
  overflow-x: auto;
  overflow-y: hidden;
  background-color: #E8E8E8 !important;
}

.nav-pills .nav-link {
  font-size: 17px;
}

.nav-pills .nav-link.active, .nav-pills .show>.nav-link {
    color: white;
    background-color: #135d9c
}
.dropdown-toggle { outline: 0; }

.btn-toggle {
  padding: .25rem .5rem;
  font-weight: 600;
  color: var(--bs-emphasis-color);
  background-color: transparent;
}
.btn-toggle:hover,
.btn-toggle:focus {
  color: rgba(var(--bs-emphasis-color-rgb), .85);
  background-color: var(--bs-tertiary-bg);
}

.btn-toggle::before {
  width: 1.25em;
  line-height: 0;
  content: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='rgba%280,0,0,.5%29' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 14l6-6-6-6'/%3e%3c/svg%3e");
  transition: transform .35s ease;
  transform-origin: .5em 50%;
}

[data-bs-theme="dark"] .btn-toggle::before {
  content: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='rgba%28255,255,255,.5%29' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 14l6-6-6-6'/%3e%3c/svg%3e");
}

.btn-toggle[aria-expanded="true"] {
  color: rgba(var(--bs-emphasis-color-rgb), .85);
}
.btn-toggle[aria-expanded="true"]::before {
  transform: rotate(90deg);
}

.btn-toggle-nav a {
  padding: .1875rem .5rem;
  margin-top: .125rem;
  margin-left: 1.25rem;
}
.btn-toggle-nav a:hover,
.btn-toggle-nav a:focus {
  background-color: var(--bs-tertiary-bg);
}

.scrollarea {
  overflow-y: auto;
}
</style>

<div class="d-flex flex-column flex-shrink-0 p-1 bg-body-tertiary" style="width: 280px;">
  <a href="#" class="d-flex align-items-center mb-2 mt-1 text-decoration-none text-dark">
    <span class="fs-5">เมนู</span>
  </a>
  <ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item border-bottom border-bottom">
    @if ($active == 'pageadmindashboard')
      <a href="/a/dashboard.htm" class="nav-link text-white active">
    @else
      <a href="/a/dashboard.htm" class="nav-link text-dark">
    @endif
        ภาพรวม
      </a>
    </li>
    <li class="nav-item border-bottom">
    @if ($active == 'pageadmindocumentcheck')
      <a href="/a/document/check.htm" class="nav-link text-white active">
    @else
      <a href="/a/document/check.htm" class="nav-link text-dark">
    @endif
        ตรวจสอบเอกสาร
      </a>
    </li>
    <li class="nav-item border-bottom">
    @if ($active == 'pageadminwaterqualitycheck')
      <a href="/a/waterquality/check.htm" class="nav-link text-white active">
    @else
      <a href="/a/waterquality/check.htm" class="nav-link text-dark">
    @endif
        ตรวจสอบรายงานผลคุณภาพน้ำ
      </a>
    </li>
    <li class="nav-item border-bottom">
    @if ($active == 'pageadmindocumentawaitapprove')
      <a href="/a/document/awaitapprove.htm" class="nav-link text-white active">
    @else
      <a href="/a/document/awaitapprove.htm" class="nav-link text-dark">
    @endif
        รายการรออนุมัติ
      </a>
    </li>
    <!-- <li class="nav-item border-bottom">
    @if ($active == 'pageadmindocumentoverridestatus')
      <a href="/a/document/override.htm" class="nav-link text-white active">
    @else
      <a href="/a/document/override.htm" class="nav-link text-dark">
    @endif
        แก้ไขสถานะการยื่นแบบ
      </a>
    </li> -->
    <li class="nav-item border-bottom">
    @if ($active == 'pageadmindocumenthistory')
      <a href="/a/document/history.htm" class="nav-link text-white active">
    @else
      <a href="/a/document/history.htm" class="nav-link text-dark">
    @endif
        ประวัติการยื่นเอกสาร
      </a>
    </li>
    <li class="nav-item border-bottom">
    @if ($active == 'pageadmindocumentwaterqualityhistory')
      <a href="/a/waterquality/history.htm" class="nav-link text-white active">
    @else
      <a href="/a/waterquality/history.htm" class="nav-link text-dark">
    @endif
        ประวัติการรายงานคุณภาพน้ำ
      </a>
    </li>
    <a href="#" class="d-flex align-items-center mb-2 mt-1 text-decoration-none text-dark">
      <span class="fs-5">รายงาน</span>
    </a>
    <li class="nav-item border-bottom">
      @if ($active == 'pageadminreportdocumentcompleted')
        <a href="/a/report/documentcompleted.htm" class="nav-link text-white active">
      @else
        <a href="/a/report/documentcompleted.htm" class="nav-link text-dark">
      @endif
        รายงานเอกสารถูกต้อง
      </a>
    </li>
    <li class="nav-item border-bottom">
      @if ($active == 'pageadminreportdocumentapproved')
        <a href="/a/report/documentapproved.htm" class="nav-link text-white active">
      @else
        <a href="/a/report/documentapproved.htm" class="nav-link text-dark">
      @endif
        รายงานได้รับการอนุมัติ
      </a>
    </li>
    <li class="nav-item border-bottom">
      @if ($active == 'pageadminreportdocumentconnectpipe')
        <a href="/a/report/documentconnectpipe.htm" class="nav-link text-white active">
      @else
        <a href="/a/report/documentconnectpipe.htm" class="nav-link text-dark">
      @endif
        รายงานการขอรับบริการบำบัดน้ำเสีย
      </a>
    </li>
    <a href="#" class="d-flex align-items-center mb-2 mt-1 text-decoration-none text-dark">
      <span class="fs-5">ข้อมูลอื่น ๆ</span>
    </a>
    <!-- <li class="nav-item border-bottom">
    @if ($active == 'pageadminuseradminlist')
      <a href="/a/user/list.htm" class="nav-link text-white active">
    @else
      <a href="/a/user/list.htm" class="nav-link text-dark">
    @endif
        จัดการบัญชีเจ้าหน้าที่
      </a>
    </li> -->
    <li class="nav-item border-bottom">
    @if ($active == 'pageadminuserlist')
      <a href="/a/user/list.htm" class="nav-link text-white active">
    @else
      <a href="/a/user/list.htm" class="nav-link text-dark">
    @endif
        รายชื่อผู้ใช้งานในระบบ
      </a>
    </li>
    <li class="nav-item border-bottom">
    @if ($active == 'pageadminuseraccess')
      <a href="/a/user/access.htm" class="nav-link text-white active">
    @else
      <a href="/a/user/access.htm" class="nav-link text-dark">
    @endif
        ประวัติการเข้าใช้งานระบบ
      </a>
    </li>
    <li class="nav-item border-bottom">
      <form class="m-0" method="POST" action="{{ route('logout') }}">
        @csrf
        <a class="nav-link text-dark" href="#" onclick="event.preventDefault(); this.closest('form').submit();" id="btn_logout">
          ออกจากระบบ
        </a>
      </form>
    </li>
  </ul>
</div>
