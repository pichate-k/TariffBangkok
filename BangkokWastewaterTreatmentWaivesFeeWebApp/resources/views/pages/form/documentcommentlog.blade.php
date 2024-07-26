<!-- Document Comment Logs-->
<div class="card mb-3">
  <div class="card-header bg-blue text-white">
    <h5 class="text-center">รายการบันทึกการตรวจสอบการยื่นแบบ</h5>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table" id="tb_document_comment_logs">
        <thead class="text-center">
          <tr>
            <td scope="col" class="col-1">ลำดับ</td>
            <td scope="col" class="col-3 text-start">สถานะการยื่นแบบ</td>
            <td scope="col" class="col-2">วันที่ตรวจแบบ</td>
            <td scope="col" class="col-2">ตรวจการยื่นแบบโดย</td>
            <td scope="col" class="col-2">รายละเอียด</td>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>


<!-- Modal Document Comment Detail -->
<div class="modal fade" id="modalDocumentCommentDetail" tabindex="-1" aria-labelledby="modalDocumentCommentDetailLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body">
        <form id="fm_document_comment_detail">
          <input type="hidden" name="role_id" value="{{ Auth::user()->role_id }}">
          <h5 class="text-center">รายการส่งมอบเอกสารเพิ่มเติม</h5>
          <div class="mb-3 row">
            <label class="col-2 col-form-label text-end">1.</label>
            <div class="col-9">
              <input type="text" class="form-control" name="file_comment_1" readonly>
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-2 col-form-label text-end">2.</label>
            <div class="col-9">
              <input type="text" class="form-control" name="file_comment_2" readonly>
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-2 col-form-label text-end">3.</label>
            <div class="col-9">
              <input type="text" class="form-control" name="file_comment_3" readonly>
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-2 col-form-label text-end">4.</label>
            <div class="col-9">
              <input type="text" class="form-control" name="file_comment_4" readonly>
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-2 col-form-label text-end">5.</label>
            <div class="col-9">
              <input type="text" class="form-control" name="file_comment_5" readonly>
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-2 col-form-label text-end">อื่น ๆ</label>
            <div class="col-9">
              <textarea class="form-control" rows="5" name="text_comment" readonly></textarea>
            </div>
          </div>
          <h5 class="mx-5 text-center">
            กำหนดยื่นเอกสารเพิ่มเติม ภายในวันที่
            <span class="text-danger fw-bold text-decoration-underline" name="deadline_submit_doc"></span>
             (ภายใน 15 วันนับจากวันที่ยื่นแบบ หากไม่ยื่นภายในกำหนดจะถือว่าผู้ยื่นแบบไม่ประสงค์จะให้สำนักงานจัดการคุณภาพน้ำดำเนินการกับการยื่นแบบดังกล่าวต่อไป)
          </h5>
        </form>
        <div class="d-grid gap-2 col-6 mx-auto">
          <button type="button" class="btn btn-blue-bg" data-bs-dismiss="modal">ปิด</button>
        </div>
      </div>
    </div>
  </div>
</div>
