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

class DocumentApproveConnectPipeReportExport implements FromCollection, ShouldAutoSize, WithEvents, WithColumnFormatting, WithStrictNullComparison
{

  // public function  __construct() {
  //   //
  // }

  /**
  * @return \Illuminate\Support\Collection
  */
  public function collection()
  {
      $documentLogList = DocumentLog::join("lov_document_type","lov_document_type.doc_type_code", "document_logs.doc_type")
                                    ->join("lov_document_status","lov_document_status.doc_status_id", "document_logs.doc_status")
                                    ->join("user_profile","user_profile.user_id", "document_logs.user_id")
                                    ->join("document_rb_1","document_rb_1.doc_no", "document_logs.doc_no")
                                    ->join("users","users.user_id", "document_logs.completed_by")
                                    ->whereIn("doc_status", [1])
                                    ->where("doc_type", "RB1")
                                    ->orderBy("created_date", "asc")
                                    ->get();

      $reportName = "รายงานการขอรับบริการบำบัดน้ำเสีย";
      $reportSearch = "ข้อมูลวันที่ ".(Carbon::now()->locale("th")->isoFormat('DD MMM ').(Carbon::now()->locale("th")->isoFormat('YYYY')+543));

      $this->reportTable = [];
      for ($i=0; $i < count($documentLogList); $i++) {
        $dataRow = [];

        $documentDate = Carbon::parse($documentLogList[$i]["created_date"]);

        array_push($dataRow, $documentLogList[$i]["doc_no"]);
        array_push($dataRow, $documentLogList[$i]["address_code"]);
        array_push($dataRow, $documentLogList[$i]["address_name"]);
        array_push($dataRow, $documentLogList[$i]["address_owner"]);
        array_push($dataRow, ($documentDate->locale("th")->isoFormat('DD MMM ').($documentDate->locale("th")->isoFormat('YYYY')+543)));
        array_push($dataRow, $documentLogList[$i]["doc_status_name"]);
        array_push($dataRow, $documentLogList[$i]["telephone"]);
        array_push($dataRow, $documentLogList[$i]["username"]);
        array_push($this->reportTable, $dataRow);
      }

      return new Collection([
          [$reportName],
          [$reportSearch],
          [""],
          ["เลขที่คำขอ", "รหัสผู้ใช้น้ำ", "ชื่ออาคาร/สถานประกอบการ", "ชื่อเจ้าของแหล่งกำเนิดน้ำเสีย", "วันที่ยื่นขอ", "สถานะคำขอ", "โทรศัพท์", "ผู้ตรวจสอบ"],
          $this->reportTable,
      ]);
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

            $event->sheet->mergeCells('A1:H1');
            $event->sheet->mergeCells('A2:H2');
            $event->sheet->getStyle('A1:H1')->getFont()->setSize(16);
            $event->sheet->getStyle('A1:A2')->getFont()->setBold(true);

            $event->sheet->getStyle('A2:H2')->getFont()->setSize(13);

            $event->sheet->getStyle('A4:H4')->getFont()->setSize(11);
            $event->sheet->getStyle('A4:H4')->getFont()->setBold(true);
            $event->sheet->getStyle('A4:H4')->getAlignment()->setVertical("center");
            $event->sheet->getStyle('A4:H4')->getAlignment()->setHorizontal("center");
            $event->sheet->getStyle('A4:H4')->applyFromArray($BORDER_THIN);

            $cell = 'A5:H'.(count($this->reportTable) + 4);
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
