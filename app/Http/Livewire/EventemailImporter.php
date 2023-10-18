<?php
namespace App\Http\Livewire;

use App\Models\EventListing;
use App\Models\EventEmail;
use App\Models\EventEmailInfo;
use App\Models\EventPlaceholder;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use League\Csv\Reader;
use Livewire\Component;
use Livewire\WithFileUploads;


class EventemailImporter extends Component
{
    use WithFileUploads;

    public EventListing $listing;
    public bool $uploaded = false;
    public $file = null;
    public int $rowsCount = 0;
    public int $columnsCount = 0;
    public array $columns = [];
    public array $check = [];
    public array $recordsss = [];
    public array $checkreader = [];
    public  $seperator_type = ',' ;

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:csv,txt'],
            'listing.notes' => ['required'],
            'columns' => [
                'array', function ($_, $columns, $fail) {
                    if (!in_array('email', array_map('strtolower', $columns))) {
                        $fail('Missing email column.');
                        return;
                    }

                    if (count($columns) > count(array_unique($columns))) {
                        $fail('Duplicated columns not supported.');
                        return;
                    }
                }
            ],
            'columns.*' => ['required', Rule::in(array_keys($this->columnValues()))]
        ];
    }

    public function mount(EventListing $eventlisting): void
    {
        if (! $eventlisting->exists) {
            throw new \InvalidArgumentException('Listing model must exist in database.');
        }

        $this->listing = $eventlisting;
    }

    public function updatedFile(): void
    {
        $this->validateOnly('file');

        $reader = Reader::createFromPath($this->file->getRealPath());
        foreach ($reader as $record) {

            $this->columnsCount = count($record);
            $this->recordsss[] = $record;
            break;
        }
        $this->rowsCount = count($reader);

        if( $this->rowsCount > 0){
            if(strpos($this->recordsss[0][0], '|') !== false){
                $reader->setDelimiter('|');
                $this->seperator_type= '|';
                $this->recordsss = [];
                foreach ($reader as $record) {
                    $this->columnsCount = count($record);
                    $this->recordsss[] = $record;
                    break;
                }
            }elseif(strpos($this->recordsss[0][0], ';') !== false){
                $reader->setDelimiter(';');
                $this->seperator_type= ';';
                $this->recordsss = [];
                foreach ($reader as $record) {
                    $this->columnsCount = count($record);
                    $this->recordsss[] = $record;
                    break;
                }
            }
        }
        for($i =0; $i< $this->columnsCount; $i++){
            if(!empty($this->recordsss)){
                $this->columns[$i] = $this->recordsss[0][$i];
            }
        }
        $this->uploaded = true;
    }

    public function columnValues(): array
    {
        $list_placeholder =[];
        $data =EventPlaceholder::get();
        if(!empty($data)){
            foreach($data as $place){
                $list_placeholder[] = $place['name'];
            }
            $final_placeholder = collect($list_placeholder)->mapWithKeys(function ($item) {
                return [Str::snake($item) => $item];
            })->toArray();
        }
        if($final_placeholder){
            return [
                'email' => 'Email',
            ] + $final_placeholder;
        }
        else{
            return [
                'email' => 'Email'
            ];
        }


        // return [
        //     'email' => 'Email',
        // ] + EventEmailInfo::typeOptions();
    }

    public function import(): void
    {

        if (! $this->uploaded) {
            return;
        }
        $notesListing = $this->listing->toArray();
       
        if(!empty($notesListing)){
            $notes = $notesListing['notes'];
            $insNotes = EventListing::where('id',$notesListing['id'])->update([
                                    'notes' => $notes,
                                    ]);
        }
        $this->validateOnly('columns');

        $reader = Reader::createFromPath($this->file->getRealPath());
        $emails = collect($reader->getRecords())->map(function ($record) {
            $columns = array_filter($this->columns);
            
            $key = array_search('email', $this->columns);
            
            if (! $email = $record[$key]) {
                return null;
            }

            if (! Str::contains($email, '@')) {
                return null;
            }

            if(strpos($email, '|') !== false){
                $email = explode('|', $email);
                $email = $email[0];
            }

            if(strpos($email, ';') !== false){
                $email = explode(';', $email);
                $email = $email[0];
            }

            unset($columns[$key]);
            $notesListing = $this->listing->toArray();

            $model = EventEmail::query()->firstOrCreate([
                'email' => $email,
                'rule_id' => $notesListing['id']
            ]);
            if(strpos($record[0], '|') !== false){
                $record = explode('|', $record[0]);
               
            }
            if(strpos($record[0], ';') !== false){
                $record = explode(';', $record[0]);
                
            }
            if(strpos($record[0], ',') !== false){
                $record = explode(',', $record[0]);
                
            }
            foreach ($columns as $index => $type) {
                if (!isset($record[$index])) {
                    continue;
                }
                $model->infos()->create([
                    'type' => str_replace(' ', '_', strtolower($type)),
                    'value' => $record[$index]
                ]);
            }
            return $model;
        })->filter();
        $this->listing->emails()->syncWithoutDetaching(
            $emails->pluck('id')->toArray()
        );

        $this->uploaded = false;
        $this->file = null;
        $this->rowsCount = 0;
        $this->columnsCount = 0;
        $this->columns = [];

        $this->emit('imported');

        $this->redirectRoute('eventlistings.index');
    }
    public function getSeparatorsProperty(): array
    {
        return [
            ',' => 'Comma (,)',
            ';' => 'Semicolon (;)',
            '|' => 'Pipe (|)',
        ];
    }
    public function extracolumn(): array
    {
        $allColumn =[];
        $this->validateOnly('file');

        $reader = Reader::createFromPath($this->file->getRealPath());
        $columns = array_filter($this->columns);
        $results = array_diff($columns, $allColumn);
        foreach ($results as $key => $value) {
            $columList = str_replace('_', ' ', $value);
            $allColumn[str_replace(' ', '_', strtolower($value))] = ucwords($columList);
        }
        return $allColumn;
    }
}
