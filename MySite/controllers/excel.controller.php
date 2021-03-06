<?php

class ExcelController extends Controller{

    protected $excel;

    public function __construct($data = array()){
        parent::__construct($data);
        $this->model = new Products();
    }

    public function index(){
        Router::redirect('/');
    }

    public function create(){
        $this->data['products'] = $this->model->getList();
        $objPHPExcel = new PHPEXcel();

        $objPHPExcel->setActiveSheetIndex(0);

//$objPHPExcel->createSheet();

        $active_sheet = $objPHPExcel->getActiveSheet();

        $active_sheet->getPageSetup()
            ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);

        $active_sheet->getPageSetup()
            ->SetPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);


        $active_sheet->getPageMargins()->setTop(1);
        $active_sheet->getPageMargins()->setRight(0.75);
        $active_sheet->getPageMargins()->setLeft(0.75);
        $active_sheet->getPageMargins()->setBottom(1);

        $active_sheet->setTitle("Прайс лист");

        $active_sheet->getHeaderFooter()->setOddHeader("&CШапка нашего прайс листа");
        $active_sheet->getHeaderFooter()->setOddFooter('&L&B' . $active_sheet->getTitle() . '&RСтраница &P из &N');

        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(8);


        $active_sheet->getColumnDimension('A')->setWidth(7);
        $active_sheet->getColumnDimension('B')->setWidth(80);
        $active_sheet->getColumnDimension('C')->setWidth(10);
        $active_sheet->getColumnDimension('D')->setAutoSize(true);

        $active_sheet->mergeCells('A1:D1');
        $active_sheet->getRowDimension('1')->setRowHeight(40);
        $active_sheet->setCellValue('A1', 'Інтернет-магазин Чаю');

        $active_sheet->mergeCells('A2:D2');
        $active_sheet->setCellValue('A2', 'Ексклюзивні сорти китайського чаю з швидкою доставкою по Києву та Україні!');

        $active_sheet->mergeCells('A4:C4');
        $active_sheet->setCellValue('A4', 'Дата створення прайс листа');

        $date = date('d-m-Y');
        $active_sheet->setCellValue('D4', $date);
        $active_sheet->getStyle('D4')
            ->getNumberFormat()->
            setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);

        $active_sheet->setCellValue('A6', '№');
        $active_sheet->setCellValue('B6', 'Назва');
        $active_sheet->setCellValue('C6', 'Ціна');

        $row_start = 7;
        $i = 0;
        foreach ($this->data['products'] as $item) {
            $row_next = $row_start + $i;

            $active_sheet->setCellValue('A' . $row_next, $item['id']);
            $active_sheet->setCellValue('B' . $row_next, $item['name']);
            $active_sheet->setCellValue('C' . $row_next, $item['price'] . 'грн');

            $i++;
        }

        $style_wrap = array(
            'borders' => array(
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THICK
                ),
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => '696969'
                    )
                )

            )


        );

        $active_sheet->getStyle('A1:D' . ($i + 6))->applyFromArray($style_wrap);


        $style_header = array(
            'font' => array(
                'bold' => true,
                'name' => 'Times New Roman',
                'size' => 20
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
            ),
            'fill' => array(
                'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                'color' => array(
                    'rgb' => 'CFCFCF'
                )
            )


        );

        $active_sheet->getStyle('A1:D1')->applyFromArray($style_header);

        $style_slogan = array(
            'font' => array(
                'bold' => true,
                'italic' => true,
                'name' => 'Times New Roman',
                'size' => 13,
                'color' => array(
                    'rgb' => '8B8989'
                )

            ),
            'alignment' => array(
                'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
            ),
            'fill' => array(
                'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                'color' => array(
                    'rgb' => 'CFCFCF'
                )
            ),
            'borders' => array(
                'bottom' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THICK
                )

            )


        );

        $active_sheet->getStyle('A2:D2')->applyFromArray($style_slogan);


        $style_tdate = array(
            'alignment' => array(
                'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_RIGHT,
            ),
            'fill' => array(
                'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                'color' => array(
                    'rgb' => 'CFCFCF'
                )
            ),
            'borders' => array(
                'right' => array(
                    'style' => PHPExcel_Style_Border::BORDER_NONE
                )

            )


        );

        $active_sheet->getStyle('A4:C4')->applyFromArray($style_tdate);

        $style_date = array(

            'fill' => array(
                'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                'color' => array(
                    'rgb' => 'CFCFCF'
                )
            ),
            'borders' => array(
                'left' => array(
                    'style' => PHPExcel_Style_Border::BORDER_NONE
                )

            ),


        );

        $active_sheet->getStyle('D4')->applyFromArray($style_date);


        $style_hprice = array(
            'alignment' => array(
                'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
            ),
            'fill' => array(
                'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                'color' => array(
                    'rgb' => 'CFCFCF'
                )
            ),
            'font' => array(
                'bold' => true,
                'italic' => true,
                'name' => 'Times New Roman',
                'size' => 10
            ),


        );

        $active_sheet->getStyle('A6:D6')->applyFromArray($style_hprice);

        $style_price = array(
            'alignment' => array(
                'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_LEFT,
            )


        );

        $active_sheet->getStyle('A7:D' . ($i + 6))->applyFromArray($style_price);


        header("Content-Type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=list.xls");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
}