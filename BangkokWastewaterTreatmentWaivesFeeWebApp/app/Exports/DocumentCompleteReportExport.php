<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Database\Eloquent\Collection;

use Carbon\Carbon;

use App\Models\DocumentLog;

class DocumentCompleteReportExport implements FromCollection, ShouldAutoSize, WithEvents, WithColumnFormatting, WithStrictNullComparison
{

  // public function  __construct() {
  //   //
  // }

  /**
  * @return \Illuminate\Support\Collection
  */
  public function collection()
  {
    $documentYV1 = DocumentLog::join("lov_document_type","lov_document_type.doc_type_code", "document_logs.doc_type")
                                  ->join("lov_document_status","lov_document_status.doc_status_id", "document_logs.doc_status")
                                  ->join("user_profile","user_profile.user_id", "document_logs.user_id")
                                  ->join("document_yv_1","document_yv_1.doc_no", "document_logs.doc_no")
                                  ->join("lov_building_type","lov_building_type.building_type_id", "document_yv_1.wastewater_source_building_type")
                                  ->join("users","users.user_id", "document_logs.completed_by")
                                  ->whereIn("doc_status", [1])
                                  ->whereIn("doc_type", ["YV1"])
                                  ->orderBy("created_date", "asc")
                                  ->get();

    $documentYV2 = DocumentLog::join("lov_document_type","lov_document_type.doc_type_code", "document_logs.doc_type")
                                  ->join("lov_document_status","lov_document_status.doc_status_id", "document_logs.doc_status")
                                  ->join("user_profile","user_profile.user_id", "document_logs.user_id")
                                  ->join("document_yv_2","document_yv_2.doc_no", "document_logs.doc_no")
                                  ->join("users","users.user_id", "document_logs.completed_by")
                                  ->whereIn("doc_status", [1])
                                  ->whereIn("doc_type", ["YV2"])
                                  ->orderBy("created_date", "asc")
                                  ->get();

    $documentRB1 = DocumentLog::join("lov_document_type","lov_document_type.doc_type_code", "document_logs.doc_type")
                                  ->join("lov_document_status","lov_document_status.doc_status_id", "document_logs.doc_status")
                                  ->join("user_profile","user_profile.user_id", "document_logs.user_id")
                                  ->join("document_rb_1","document_rb_1.doc_no", "document_logs.doc_no")
                                  ->join("users","users.user_id", "document_logs.completed_by")
                                  ->whereIn("doc_status", [1])
                                  ->whereIn("doc_type", ["RB1"])
                                  ->orderBy("created_date", "asc")
                                  ->get();

    $documentPG1 = DocumentLog::join("lov_document_type","lov_document_type.doc_type_code", "document_logs.doc_type")
                                  ->join("lov_document_status","lov_document_status.doc_status_id", "document_logs.doc_status")
                                  ->join("user_profile","user_profile.user_id", "document_logs.user_id")
                                  ->join("document_pg_1","document_pg_1.doc_no", "document_logs.doc_no")
                                  ->join("users","users.user_id", "document_logs.completed_by")
                                  ->whereIn("doc_status", [1])
                                  ->whereIn("doc_type", ["PG1"])
                                  ->orderBy("created_date", "asc")
                                  ->get();

    $documentPG2 = DocumentLog::join("lov_document_type","lov_document_type.doc_type_code", "document_logs.doc_type")
                                  ->join("lov_document_status","lov_document_status.doc_status_id", "document_logs.doc_status")
                                  ->join("user_profile","user_profile.user_id", "document_logs.user_id")
                                  ->join("document_pg_2","document_pg_2.doc_no", "document_logs.doc_no")
                                  ->join("users","users.user_id", "document_logs.completed_by")
                                  ->whereIn("doc_status", [1])
                                  ->whereIn("doc_type", ["PG2"])
                                  ->orderBy("created_date", "asc")
                                  ->get();

    $documentNT1 = DocumentLog::join("lov_document_type","lov_document_type.doc_type_code", "document_logs.doc_type")
                                  ->join("lov_document_status","lov_document_status.doc_status_id", "document_logs.doc_status")
                                  ->join("user_profile","user_profile.user_id", "document_logs.user_id")
                                  ->join("document_nt_1","document_nt_1.doc_no", "document_logs.doc_no")
                                  ->join("users","users.user_id", "document_logs.completed_by")
                                  ->whereIn("doc_status", [1])
                                  ->whereIn("doc_type", ["NT1"])
                                  ->orderBy("created_date", "asc")
                                  ->get();

    $documentNT2 = DocumentLog::join("lov_document_type","lov_document_type.doc_type_code", "document_logs.doc_type")
                                  ->join("lov_document_status","lov_document_status.doc_status_id", "document_logs.doc_status")
                                  ->join("user_profile","user_profile.user_id", "document_logs.user_id")
                                  ->join("document_nt_2","document_nt_2.doc_no", "document_logs.doc_no")
                                  ->join("users","users.user_id", "document_logs.completed_by")
                                  ->whereIn("doc_status", [1])
                                  ->whereIn("doc_type", ["NT2"])
                                  ->orderBy("created_date", "asc")
                                  ->get();


      $reportName = "รายงานผู้ได้รับการตรวจสอบเอกสารถูกต้อง";
      $reportSearch = "ข้อมูลวันที่ ".(Carbon::now()->locale("th")->isoFormat('DD MMM ').(Carbon::now()->locale("th")->isoFormat('YYYY')+543));

      $this->reportTable = [];
      $this->addDataRow($documentYV1, "YV1");
      $this->addDataRow($documentYV2, "YV2");
      $this->addDataRow($documentRB1, "RB1");
      $this->addDataRow($documentPG1, "PG1");
      $this->addDataRow($documentPG2, "PG2");
      $this->addDataRow($documentNT1, "NT1");
      $this->addDataRow($documentNT2, "NT2");

      return new Collection([
          [$reportName],
          [$reportSearch],
          [""],
          ["เลขที่คำร้อง", "รหัสผู้ใช้น้ำ", "ชื่อเจ้าของแหล่งกำเนิดน้ำเสีย", "ประเภทอาคาร", "วันที่ยื่นคำร้อง", "สถานะคำร้อง", "ผู้ตรวจสอบ"],
          $this->reportTable,
      ]);
  }

  public function addDataRow($documentLogList, $documentType) {

    for ($i=0; $i < count($documentLogList); $i++) {
      $dataRow = [];

      $documentDate = Carbon::parse($documentLogList[$i]["created_date"]);

      array_push($dataRow, $documentLogList[$i]["doc_no"]);
      array_push($dataRow, $documentLogList[$i]["address_code"]);
      array_push($dataRow, $documentLogList[$i]["address_owner"]);

      if($documentType == "YV1") {
        array_push($dataRow, $documentLogList[$i]["building_name"]);
      } else {
        array_push($dataRow, "-");
      }

      array_push($dataRow, ($documentDate->locale("th")->isoFormat('DD MMM ').($documentDate->locale("th")->isoFormat('YYYY')+543)));
      array_push($dataRow, $documentLogList[$i]["doc_status_name"]);
      array_push($dataRow, $documentLogList[$i]["username"]);
      array_push($this->reportTable, $dataRow);
    }

  }

  public function registerEvents(): array
  {
      return [
          AfterSheet::class => function(AfterSheet $event) {
            $BORDER_THIN = [
              'borders' => [
                  'outline' => [
                      'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                      'color' => ['rgb' => '000000'],
                  ],
              ],
            ];

            $event->sheet->mergeCells('A1:G1');
            $event->sheet->mergeCells('A2:G2');
            $event->sheet->getStyle('A1:G1')->getFont()->setSize(16);
            $event->sheet->getStyle('A1:A2')->getFont()->setBold(true);

            $event->sheet->getStyle('A2:G2')->getFont()->setSize(13);

            $event->sheet->getStyle('A4:G4')->getFont()->setSize(11);
            $event->sheet->getStyle('A4:G4')->getFont()->setBold(true);
            $event->sheet->getStyle('A4:G4')->getAlignment()->setVertical("center");
            $event->sheet->getStyle('A4:G4')->getAlignment()->setHorizontal("center");
            $event->sheet->getStyle('A4:G4')->applyFromArray($BORDER_THIN);

            $cell = 'A5:G'.(count($this->reportTable) + 4);
            $event->sheet->getStyle($cell)->applyFromArray($BORDER_THIN);
            $event->sheet->getStyle($cell)->getAlignment()->setHorizontal("center");

            // <!-- Set Row Height -->
            for ($i=0; $i < 5; $i++) {
              $event->sheet->getRowDimension($i)->setRowHeight(20);
            }

          },
        ];
  }

  public function columnFormats(): array
  {
      return [
          // 'C' => '@',
      ];
  }
}
