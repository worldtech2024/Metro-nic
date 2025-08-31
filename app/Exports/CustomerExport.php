<?php
namespace App\Exports;

use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomerExport implements FromArray, WithStyles, WithTitle
{
    protected $users;

    public function __construct($users)
    {
        $this->users = $users;
    }

    public function array(): array
    {
        $rows = [];

        // الصف الأول: required/optional
        $rows[] = [
            'required',
            'required',
            'required',
            'required',
            'optional',
            'optional',
            'optional',
            'optional',
            'optional',
            'optional',
            'optional',
            'optional',
        ];

        // الصف الثاني: رؤوس الأعمدة
        $rows[] = [
            'country',
            'name',
            'email',
            'phone',
            'city',
            'street',
            'neighborhood',
            'zipCode',
            'buildingNumber',
            'additionalNumber',
            'taxNumber',
            'commercialRegister',
        ];

        // البيانات الفعلية
        foreach ($this->users as $user) {
            $rows[] = [
                App::getLocale() === 'en' ? $user->country->name_en : $user->country->name_ar,
                $user->name,
                $user->email,
                $user->phone,
                $user->city,
                $user->street,
                $user->neighborhood,
                $user->zipCode,
                $user->buildingNumber,
                $user->additionalNumber,
                $user->taxNum,
                $user->commercialRegister,
            ];
        }

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        // توسيع الأعمدة
        foreach (range('A', 'L') as $col) {
            $sheet->getColumnDimension($col)->setWidth(20);
        }

        // تنسيق الصف الأول والثاني
        $sheet->getStyle('A1:L2')->applyFromArray([
            'font'      => [
                'bold' => true,
                'size' => 15,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'fill'      => [
                'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFE699'], // لون أصفر خفيف
            ],
            'borders'   => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color'       => ['argb' => '000000'],
                ],
            ],
        ]);

        // تنسيق بقية الصفوف
        $sheet->getStyle('A3:L' . $sheet->getHighestRow())->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders'   => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color'       => ['argb' => '000000'],
                ],
            ],
        ]);
    }

    public function title(): string
    {
        return 'Customers Sheet';
    }
}
