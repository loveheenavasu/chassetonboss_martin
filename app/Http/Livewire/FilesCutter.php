<?php

namespace App\Http\Livewire;

use App\Models\CutterLog;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use League\Csv\Reader;
use ZipArchive;

class FilesCutter extends Component
{
    use WithFileUploads;

    public bool $processed = false;

    public array $emails = [];
    public int $skippedCount = 0;

    public $files = [];
    public ?string $filename = '';
    public ?string $separator = ',';
    public ?string $column_header = 'LOT';
    public ?string $column_value = 'section :index:';
    public ?string $crop_mode = 'emails_per_chunk';
    public ?int $emails_per_chunk = null;
    public ?int $chunks_count = null;
    public ?string $col_loc = '0';
    public array $recordsss = [];
    public  $seperator_type = '' ;
    public ?int $emailIndex = null;
    public ?int $lotIndex = null;
    public ?int $headerCount = null;
    public ?CutterLog $log = null;
    public ?string $tempName = null;

    public function mount()
    {
        if (is_null($this->tempName)) {
            $this->tempName = Str::random();
        }
    }

    public function updatedFiles(): void
    {
        $this->validate([
            'files' => ['required', 'array', 'min:1'],
            'files.*' => ['file', 'mimes:csv,txt'],
        ]);
        $reader = $this->files;
        foreach ($this->files as $file) {
            if (($handle = fopen($file->getRealPath(), 'r')) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    $email = reset($data);
                    if (Str::contains($email, '@')) {
                        $emails[] = $email;
                    }
                }
            }
        }
        $this->rowsCount = count($emails);
        $this->recordsss[] = $emails;
        if( $this->rowsCount > 0){
            if(strpos($this->recordsss[0][0], ';') !== false){
                $this->seperator_type= ';';
            }elseif(strpos($this->recordsss[0][0], '|') !== false){
                $this->seperator_type= '|';
            }else{
                $this->seperator_type= ',';
            }
        }
        $this->populateEmailsFromFiles();
    }

    public function updatedCropMode(): void
    {
        $this->processed = false;
    }

    protected function populateEmailsFromFiles(): void
    {
        $emails = [];
        $skippedCount = 0;
        $counter = 0;
        $headerValues = [];
        foreach ($this->files as $file) {
            if (($handle = fopen($file->getRealPath(), 'r')) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    if ($counter == 0) {
                      $headerValues = $data;
                      $columnsEmail = explode(',',$headerValues[0]);
                      $this->headerCount=count($columnsEmail);
                      $emailkey = array_map('strtolower', $columnsEmail);
                      $this->emailIndex =  array_search('email', $emailkey);
                      $this->lotIndex =  array_search('lot', $emailkey);
                      $counter++;                  
                    } 
                    $email = reset($data);      
                    if($this->headerCount == 1){
                        if (Str::contains($email, '@')) {
                            $emails[] = $email;
                        } else {
                            ++$skippedCount;
                        }
                    }else{
                        if (Str::contains($email, '@')) {
                            $data = explode(',',$email);
                            $emails[] = $data;
                        } else {
                            ++$skippedCount;
                        }
                    }
                }
                fclose($handle);
            }
        }
        $this->emails = $emails;
        sort($this->emails);
        if($this->headerCount == 1){
            $this->emails = array_unique($this->emails);
        }
        $this->skippedCount = $skippedCount;
    }

    public function process(): void
    {
        $this->processed = false;

        if (!count($this->emails)) {
            return;
        }

        $this->validate([
            'filename' => ['required', 'string'],
            'separator' => ['required', Rule::in(array_keys($this->separators))],
            'column_header' => ['required', 'string'],
            'column_value' => ['required', 'string'],
            'crop_mode' => ['required', Rule::in(array_keys($this->cropModes))],
            'emails_per_chunk' => ['nullable', Rule::requiredIf(fn () => $this->crop_mode === 'emails_per_chunk'), 'integer', 'min:1'],
            'chunks_count' => ['nullable', Rule::requiredIf(fn () => $this->crop_mode === 'chunks_count'), 'integer', 'min:1'],
            'col_loc' => ['required', Rule::in(array_keys($this->col))],
        ]);

        $this->processed = true;

        $this->processMergedFile();
        $this->processChunksArchive();
        $this->captureLog();

        $this->emit('processed');

    }

    protected function processMergedFile(): void
    {
        if (!$this->processed) {
            return;
        }

        $tempFilepath = storage_path($this->tempMergedName());

        File::ensureDirectoryExists(dirname($tempFilepath));
        File::put($tempFilepath, $this->prepareCsv(collect($this->emails)));
    }

    public function processChunksArchive(): void
    {
        if (!$this->processed) {
            return;
        }

        $tempFilepath = storage_path($this->tempChunksName());

        File::ensureDirectoryExists(dirname($tempFilepath));

        $zip = new ZipArchive();
        $zip->open($tempFilepath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        collect($this->emails)->chunk($this->calculatedEmailsPerChunk())->each(function (Collection $emails, $index) use ($zip) {
            $zip->addFromString(
                $this->downloadedChunkName($index, count($emails)), $this->prepareCsv($emails, $index)
            );
        });

        $zip->close();
    }

    protected function captureLog()
    {
        $this->log = $this->log ?? new CutterLog();

        $this->log->forceFill([
            'merged_filename' => $this->downloadedFileName(),
            'merged_path' => $this->tempMergedName(),
            'chunks_filename' => $this->downloadedArchiveName(),
            'chunks_path' => $this->tempChunksName()
        ]);

        $this->log->save();
    }

    // TODO: refactor?
    public function calculatedEmailsPerChunk(): int
    {
        if ($this->crop_mode === 'emails_per_chunk') {
            return $this->emails_per_chunk;
        } else if ($this->crop_mode === 'chunks_count') {
            if (!count($this->emails) || !$this->chunks_count) {
                return 0;
            }

            return ceil(count($this->emails) / $this->chunks_count);
        }

        return 0;
    }

    // TODO: refactor?
    public function calculatedChunksCount(): int
    {
        if ($this->crop_mode === 'emails_per_chunk') {
            if (!count($this->emails) || !$this->emails_per_chunk) {
                return 0;
            }

            return ceil(count($this->emails) / $this->emails_per_chunk);
        } else if ($this->crop_mode === 'chunks_count') {
            return $this->chunks_count;
        }

        return 0;
    }

    public function downloadMergedFile()
    {
        if (is_null($this->log)) {
            return null;
        }

        return response()->download(storage_path($this->log->merged_path), $this->log->merged_filename);
    }

    public function downloadChunksArchive()
    {
        if (is_null($this->log)) {
            return null;
        }

        return response()->download(storage_path($this->log->chunks_path), $this->log->chunks_filename);
    }

    protected function prepareCsv(Collection $emails, $index = null): string
    {
        $buffer = fopen('php://temp', 'r+');
        if($this->headerCount == 1){
            if ($this->col_loc === '0') {
                fputcsv($buffer, [$this->column_header, 'Email'], $this->separator);

                $emails->each(function ($email) use ($buffer, $index) {
                    fputcsv($buffer, [!is_null($index) ? $this->formattedColumnValue($index) : '', $email], $this->separator);
                });
            }else{
                fputcsv($buffer, ['Email', $this->column_header], $this->separator);

                $emails->each(function ($email) use ($buffer, $index) {
                    fputcsv($buffer, [$email, !is_null($index) ? $this->formattedColumnValue($index) : ''], $this->separator);
                });
            }

        }else{
            if ($this->col_loc === '0') {
                fputcsv($buffer, [$this->column_header, 'Email']);

                $emails->each(function ($email) use ($buffer, $index) {
                    fputcsv($buffer, [$email[$this->lotIndex], $email[$this->emailIndex]]);
                });
            }else{
                fputcsv($buffer, ['Email', $this->column_header], $this->separator);

                $emails->each(function ($email) use ($buffer, $index) {
                    fputcsv($buffer, [$email[$this->emailIndex], $email[$this->lotIndex]], $this->separator);
                
                });
            }
        }
        rewind($buffer);
        $csv = stream_get_contents($buffer);
        fclose($buffer);
        return $csv;
    }

    protected function tempMergedName(): string
    {
        return 'app/cutter/' . $this->tempName . '.csv';
    }

    protected function tempChunksName(): string
    {
        return 'app/cutter/' . $this->tempName . '.zip';
    }

    protected function downloadedFileName(): string
    {
        return $this->filename . ' (' . $this->formattedCount(count($this->emails)) . ').csv';
    }

    protected function downloadedArchiveName(): string
    {
        return $this->filename . ' (' . $this->formattedCount(count($this->emails)) . ').zip';
    }

    protected function downloadedChunkName($index, $count): string
    {
        return $this->filename . ' ' . Str::padLeft($index, 3, 0)  . ' (' . $this->formattedCount($count). ').csv';
    }

    protected function formattedCount($count): string
    {
        return number_format($count, 0, '.', ' ');
    }

    protected function formattedColumnValue($index): ?string
    {
        return trim(str_replace(':index:', $index, $this->column_value));
    }

    public function getCropModesProperty(): array
    {
        return [
            'emails_per_chunk' => 'Emails per chunk',
            'chunks_count' => 'Chunks count'
        ];
    }

    public function getSeparatorsProperty(): array
    {
        return [
            ',' => 'Comma (,)',
            ';' => 'Semicolon (;)',
            '|' => 'Pipe (|)',
        ];
    }

    public function getColProperty(): array
    {
        return [
            '0' => '1st',
            '1' => '2nd',
        ];
    }
}
