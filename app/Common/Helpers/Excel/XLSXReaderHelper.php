<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Common\Helpers\Excel;

class XLSXReaderHelper
{
    // XML schemas
    public const SCHEMA_OFFICEDOCUMENT = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument';

    public const SCHEMA_RELATIONSHIP = 'http://schemas.openxmlformats.org/package/2006/relationships';

    public const SCHEMA_OFFICEDOCUMENT_RELATIONSHIP = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships';

    public const SCHEMA_SHAREDSTRINGS = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings';

    public const SCHEMA_WORKSHEETRELATION = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet';

    public $config = [
        'removeTrailingRows' => true,
    ];

    protected $sheets = [];

    protected $sharedstrings = [];

    protected $sheetInfo;

    protected $zip;

    public function __construct($filePath, $config = [])
    {
        $this->config = array_merge($this->config, $config);
        $this->zip = new ZipArchive();
        $status = $this->zip->open($filePath);
        if ($status === true) {
            $this->parse();
        } else {
            throw new Exception("Failed to open {$filePath} with zip error code: {$status}");
        }
    }

    // returns an array of sheet names, indexed by sheetId
    public function getSheetNames()
    {
        $res = [];
        foreach ($this->sheetInfo as $sheetName => $info) {
            $res[$info['sheetId']] = $sheetName;
        }
        return $res;
    }

    public function getSheetCount()
    {
        return count($this->sheetInfo);
    }

    // instantiates a sheet object (if needed) and returns an array of its data
    public function getSheetData($sheetNameOrId)
    {
        $sheet = $this->getSheet($sheetNameOrId);
        return $sheet->getData();
    }

    // instantiates a sheet object (if needed) and returns the sheet object
    public function getSheet($sheet)
    {
        if (is_numeric($sheet)) {
            $sheet = $this->getSheetNameById($sheet);
        } elseif (! is_string($sheet)) {
            throw new Exception('Sheet must be a string or a sheet Id');
        }
        if (! array_key_exists($sheet, $this->sheets)) {
            $this->sheets[$sheet] = new XLSXWorksheet($this->getSheetXML($sheet), $sheet, $this);
        }
        return $this->sheets[$sheet];
    }

    public function getSheetNameById($sheetId)
    {
        foreach ($this->sheetInfo as $sheetName => $sheetInfo) {
            if ($sheetInfo['sheetId'] === $sheetId) {
                return $sheetName;
            }
        }
        throw new Exception("Sheet ID {$sheetId} does not exist in the Excel file");
    }

    // converts an Excel date field (a number) to a unix timestamp (granularity: seconds)
    public static function toUnixTimeStamp($excelDateTime)
    {
        if (! is_numeric($excelDateTime)) {
            return $excelDateTime;
        }
        $d = floor($excelDateTime); // seconds since 1900
        $t = $excelDateTime - $d;
        return ($d > 0) ? ($d - 25569) * 86400 + $t * 86400 : $t * 86400;
    }

    // get a file from the zip
    protected function getEntryData($name)
    {
        $data = $this->zip->getFromName($name);
        if ($data === false) {
            throw new Exception("File {$name} does not exist in the Excel file");
        }
        return $data;
    }

    // extract the shared string and the list of sheets
    protected function parse()
    {
        $sheets = [];
        $relationshipsXML = simplexml_load_string($this->getEntryData('_rels/.rels'));
        foreach ($relationshipsXML->Relationship as $rel) {
            if ($rel['Type'] == self::SCHEMA_OFFICEDOCUMENT) {
                $workbookDir = dirname($rel['Target']) . '/';
                $workbookXML = simplexml_load_string($this->getEntryData($rel['Target']));
                foreach ($workbookXML->sheets->sheet as $sheet) {
                    $r = $sheet->attributes('r', true);
                    $sheets[(string) $r->id] = [
                        'sheetId' => (int) $sheet['sheetId'],
                        'name' => (string) $sheet['name'],
                    ];
                }
                $workbookRelationsXML = simplexml_load_string($this->getEntryData($workbookDir . '_rels/' . basename($rel['Target']) . '.rels'));
                foreach ($workbookRelationsXML->Relationship as $wrel) {
                    switch ($wrel['Type']) {
                        case self::SCHEMA_WORKSHEETRELATION:
                            $sheets[(string) $wrel['Id']]['path'] = $workbookDir . (string) $wrel['Target'];
                            break;
                        case self::SCHEMA_SHAREDSTRINGS:
                            $sharedStringsXML = simplexml_load_string($this->getEntryData($workbookDir . (string) $wrel['Target']));
                            foreach ($sharedStringsXML->si as $val) {
                                if (isset($val->t)) {
                                    $this->sharedStrings[] = (string) $val->t;
                                } elseif (isset($val->r)) {
                                    $this->sharedStrings[] = XLSXWorksheet::parseRichText($val);
                                }
                            }
                            break;
                    }
                }
            }
        }
        $this->sheetInfo = [];
        foreach ($sheets as $rid => $info) {
            $this->sheetInfo[$info['name']] = [
                'sheetId' => $info['sheetId'],
                'rid' => $rid,
                'path' => $info['path'],
            ];
        }
    }

    protected function getSheetXML($name)
    {
        return simplexml_load_string($this->getEntryData($this->sheetInfo[$name]['path']));
    }
}

class XLSXWorksheet
{
    public $sheetName;

    public $colCount;

    public $rowCount;

    protected $workbook;

    protected $data;

    protected $config;

    public function __construct($xml, $sheetName, XLSXReader $workbook)
    {
        $this->config = $workbook->config;
        $this->sheetName = $sheetName;
        $this->workbook = $workbook;
        $this->parse($xml);
    }

    // returns an array of the data from the sheet
    public function getData()
    {
        return $this->data;
    }

    // returns the text content from a rich text or inline string field
    public static function parseRichText($is = null)
    {
        $value = [];
        if (isset($is->t)) {
            $value[] = (string) $is->t;
        } else {
            foreach ($is->r as $run) {
                $value[] = (string) $run->t;
            }
        }
        return implode(' ', $value);
    }

    protected function parse($xml)
    {
        $this->parseDimensions($xml->dimension);
        $this->parseData($xml->sheetData);
    }

    protected function parseDimensions($dimensions)
    {
        $range = (string) $dimensions['ref'];
        $cells = explode(':', $range);
        $maxValues = $this->getColumnIndex($cells[1]);
        $this->colCount = $maxValues[0] + 1;
        $this->rowCount = $maxValues[1] + 1;
    }

    protected function parseData($sheetData)
    {
        $rows = [];
        $curR = 0;
        $lastDataRow = -1;
        foreach ($sheetData->row as $row) {
            $rowNum = (int) $row['r'];
            if ($rowNum != ($curR + 1)) {
                $missingRows = $rowNum - ($curR + 1);
                for ($i = 0; $i < $missingRows; ++$i) {
                    $rows[$curR] = array_pad([], $this->colCount, null);
                    ++$curR;
                }
            }
            $curC = 0;
            $rowData = [];
            foreach ($row->c as $c) {
                [$cellIndex] = $this->getColumnIndex((string) $c['r']);
                if ($cellIndex !== $curC) {
                    $missingCols = $cellIndex - $curC;
                    for ($i = 0; $i < $missingCols; ++$i) {
                        $rowData[$curC] = null;
                        ++$curC;
                    }
                }
                $val = $this->parseCellValue($c);
                if (! is_null($val)) {
                    $lastDataRow = $curR;
                }
                $rowData[$curC] = $val;
                ++$curC;
            }
            $rows[$curR] = array_pad($rowData, $this->colCount, null);
            ++$curR;
        }
        if ($this->config['removeTrailingRows']) {
            $this->data = array_slice($rows, 0, $lastDataRow + 1);
            $this->rowCount = count($this->data);
        } else {
            $this->data = $rows;
        }
    }

    protected function getColumnIndex($cell = 'A1')
    {
        if (preg_match('/([A-Z]+)(\\d+)/', $cell, $matches)) {
            $col = $matches[1];
            $row = $matches[2];
            $colLen = strlen($col);
            $index = 0;

            for ($i = $colLen - 1; $i >= 0; --$i) {
                $index += (ord($col[$i]) - 64) * pow(26, $colLen - $i - 1);
            }
            return [$index - 1, $row - 1];
        }
        throw new Exception('Invalid cell index');
    }

    protected function parseCellValue($cell)
    {
        // $cell['t'] is the cell type
        switch ((string) $cell['t']) {
            case 's': // Value is a shared string
                if ((string) $cell->v != '') {
                    $value = $this->workbook->sharedStrings[intval($cell->v)];
                } else {
                    $value = '';
                }
                break;
            case 'b': // Value is boolean
                $value = (string) $cell->v;
                if ($value == '0') {
                    $value = false;
                } elseif ($value == '1') {
                    $value = true;
                } else {
                    $value = (bool) $cell->v;
                }
                break;
            case 'inlineStr': // Value is rich text inline
                $value = self::parseRichText($cell->is);
                break;
            case 'e': // Value is an error message
                if ((string) $cell->v != '') {
                    $value = (string) $cell->v;
                } else {
                    $value = '';
                }
                break;
            default:
                if (! isset($cell->v)) {
                    return null;
                }
                $value = (string) $cell->v;

                // Check for numeric values
                if (is_numeric($value)) {
                    if ($value == (int) $value) {
                        $value = (int) $value;
                    } elseif ($value == (float) $value) {
                        $value = (float) $value;
                    } elseif ($value == (float) $value) {
                        $value = (float) $value;
                    }
                }
        }
        return $value;
    }
}
