@extends('layouts.app')

<!-- ================== BEGIN BASE CSS STYLE ================== -->
<link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}" rel="stylesheet" />

<link href="{{ asset('assets/plugins/datatables/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/datatables-select/css/select.dataTables.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" />
<!-- ================== END BASE CSS STYLE ================== -->

<style>

</style>

@section('content')
<main class="flex-shrink-0">
  <div class="container-fluid h-100">

    <div class="container">

      <!-- begin #navbar -->
      @include('components.stepper', [
        'step_display1'=> 'กรอกข้อมูลการยื่นแบบ',
        'step_action1'=> 'active',
        'step_display2'=> 'ตรวจสอบความครบถ้วนของเอกสาร',
        'step_action2'=> 'active',
        'step_display3'=> 'รอเจ้าหน้าที่ตรวจสอบข้อมูล',
        'step_action3'=> 'active',
        'step_display4'=> 'อนุมัติ / ไม่อนุมัติ (ยื่นเอกสารเพิ่มเติม)',
        'step_action4'=> '',
        'step_display5'=> '',
        'step_action5'=> ''
      ])
      <!-- end #navbar -->

      <!-- Document Form -->
      <div class="card mb-5 mt-3">
        <div class="card-body">

          <form id="fm_document_view">
            <input type="hidden" name="document_log_id">
            <input type="hidden" name="doc_status">
            <input type="hidden" name="doc_expiry_date">
          </form>

          @if (Request::get('doc_type') == "YV1")
            @include('pages.form.yv1', ['action' => 'readonly'])
          @endif

          @if (Request::get('doc_type') == "YV2")
            @include('pages.form.yv2', ['action' => 'readonly'])
          @endif

          @if (Request::get('doc_type') == "RB1")
            @include('pages.form.rb1', ['action' => 'readonly'])
          @endif

          @if (Request::get('doc_type') == "PG1")
            @include('pages.form.pg1', ['action' => 'readonly'])
          @endif

          @if (Request::get('doc_type') == "PG2")
            @include('pages.form.pg2', ['action' => 'readonly'])
          @endif

          @if (Request::get('doc_type') == "NT1")
            @include('pages.form.nt1', ['action' => 'readonly'])
          @endif

          @if (Request::get('doc_type') == "NT2")
            @include('pages.form.nt2', ['action' => 'readonly'])
          @endif

          <!-- Button -->
          <div class="d-grid gap-2 col-6 mx-auto">
            @if (!Auth::guest() && Auth::user()->role_id == 1)
            <button class="btn btn-blue-bg btn-lg" type="button" onClick="javascript:window.close('','_parent','');">ปิด</button>
            @endif
          </div>
        </div>
      </div>

      @if (!Auth::guest() && Auth::user()->role_id != 1)
      <!-- Document Comment Form -->
      <div class="card my-5" id="layout_document_comment_form">
        <div class="card-header bg-blue text-white">
          <h5 class="text-center">สำหรับเจ้าหน้าที่ตรวจสอบเอกสาร</h5>
        </div>
        <div class="card-body bg-secondary-subtle">
          <form id="fm_document_comment" class="mt-3 mb-5">
            <input type="hidden" name="doc_no">
            <div class="mb-3 row justify-content-center">
              <label class="col-4 col-form-label text-end">ความเห็นเจ้าหน้าที่ <span class="text-danger">*</span></label>
              <div class="col-8">
                <div class="form-check form-check-inline mx-4">
                  <input class="form-check-input" type="radio" name="doc_status" id="doc_status1" value="1" checked>
                  <label class="form-check-label" for="doc_status1">ครบถ้วน</label>
                </div>
                <div class="form-check form-check-inline mx-4">
                  <input class="form-check-input" type="radio" name="doc_status" id="doc_status2" value="11">
                  <label class="form-check-label" for="doc_status2">มีข้อบกพร่อง</label>
                </div>
                <div class="form-check form-check-inline mx-4">
                  <input class="form-check-input" type="radio" name="doc_status" id="doc_status3" value="12">
                  <label class="form-check-label" for="doc_status3">อื่น ๆ</label>
                </div>
              </div>
            </div>

            <div id="layout_reject" class="d-none">
              <h5 class="text-center">รายการส่งมอบเอกสารเพิ่มเติม</h5>
              <div class="mb-3 row">
                <label class="col-4 col-form-label text-end">1.</label>
                <div class="col-5">
                  <input type="text" class="form-control bg-white" name="file_comment_1">
                </div>
              </div>
              <div class="mb-3 row">
                <label class="col-4 col-form-label text-end">2.</label>
                <div class="col-5">
                  <input type="text" class="form-control bg-white" name="file_comment_2">
                </div>
              </div>
              <div class="mb-3 row">
                <label class="col-4 col-form-label text-end">3.</label>
                <div class="col-5">
                  <input type="text" class="form-control bg-white" name="file_comment_3">
                </div>
              </div>
              <div class="mb-3 row">
                <label class="col-4 col-form-label text-end">4.</label>
                <div class="col-5">
                  <input type="text" class="form-control bg-white" name="file_comment_4">
                </div>
              </div>
              <div class="mb-3 row">
                <label class="col-4 col-form-label text-end">5.</label>
                <div class="col-5">
                  <input type="text" class="form-control bg-white" name="file_comment_5">
                </div>
              </div>
              <div class="mb-3 row">
                <label class="col-4 col-form-label text-end">กำหนดยื่นเอกสารเพิ่มเติม ภายในวันที่ <span class="text-danger">*</span></label>
                <div class="col-5">
                  <div class="input-group">
                    <input type="hidden" name="document_create_date">
                    <input type="text" class="form-control bg-white datetimepicker" name="deadline_submit_doc">
                    <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
                  </div>
                </div>
              </div>
            </div>
            <div id="layout_other" class="d-none">
              <div class="mb-3 row">
                <label class="col-4 col-form-label text-end">อื่น ๆ<span class="text-danger">*</span></label>
                <div class="col-5">
                  <textarea class="form-control" name="text_comment" rows="5"></textarea>
                </div>
              </div>
            </div>

          </form>

          <!-- Button -->
          <div class="d-grid gap-2 col-6 mx-auto">
            <button class="btn btn-blue-bg btn-lg" type="button" id="btn_save_document_comment_log">บันทึกการตรวจสอบการยื่นแบบ</button>
          </div>
        </div>
      </div>
      @endif


    </div>
  </div>
</main>

</div>
@endsection


<!-- ================== BEGIN BASE JS ================== -->
<script src="{{ asset('assets/plugins/jquery/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

<script src="{{ asset('assets/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-select/js/dataTables.select.min.js') }}"></script>

<script src="{{ asset('assets/plugins/moment/js/moment-with-locales.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
<!-- ================== END BASE JS ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="{{ asset('js/web-service-task.js') }}"></script>
<script src="{{ asset('js/document-comment-log.js') }}"></script>
<script src="{{ asset('js/document-view.js') }}"></script>

@if (!Auth::guest() && Auth::user()->role_id != 1)
<script src="{{ asset('js/document-view-user-profile.js') }}"></script>
@endif
<!-- ================== END PAGE LEVEL JS ================== -->
