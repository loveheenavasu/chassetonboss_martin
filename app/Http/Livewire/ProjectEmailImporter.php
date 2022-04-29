<?php

namespace App\Http\Livewire;
use App\Models\ProjectEmail;
use App\Models\ProjectListing;
use App\Models\GmailConnection;
use App\Models\Groups;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use League\Csv\Reader;
use Livewire\Component;
use Livewire\WithFileUploads;
use DB;
use File;
use Illuminate\Support\Facades\Storage;


class ProjectEmailImporter extends Component
{
    use WithFileUploads;

    public ProjectListing $projectlist;
    public bool $uploaded = false;
    public $file = null;
    public $file1 = null;
    public int $rowsCount = 0;
    public int $columnsCount = 0;
    public array $columns = [];
    public array $check = [];
    public array $recordsss = [];
    public array $checkreader = [];
    public  $seperator_type = ',' ;
    public array $finalArray = [];
    public array $jsonArray = [];
    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:csv,txt'],
            'file1' => ['required', 'file'],
            'projectlist.group_id' => ['nullable'],
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
            'columns.*' => ['required']
        ];
    }
    public function getGroupsProperty(){

        return Groups::get();

    }
    public function mount(ProjectListing $projectlist): void
    {
        if (! $projectlist->exists) {
            throw new \InvalidArgumentException('Listing model must exist in database.');
        }

        $this->projectlist = $projectlist;
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
        return [
            'email' => 'Email',
        ];
    }

    public function import(): void
    {
        $this->columns = array_map('strtolower', $this->columns);
        if (! $this->uploaded) {
            return;
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
            $i = 0;
            foreach ($columns as $index => $type) {
                if (!isset($record[$index])) {
                    continue;
                }
                $this->finalArray[] = $record[$index];
            }
        })->filter();
        $group_name = DB::table('groups')->where('id',$this->projectlist->group_id)->pluck('name');
        foreach($this->finalArray as $res){
            $insert_email = array('email' => $res);
            $gmail_list_insert = array('email_id' => $res,'project_listing_id' => $this->projectlist->id,'group_id'=>$this->projectlist->group_id,'group_name'=>$group_name[0]);
            $gmail_conn_id = DB::table('gmail_connections')->insertGetId($gmail_list_insert);
            if($this->projectlist->group_id){
                DB::table('gmail_connection_groups')->insert([
                        'groups_id'=>$this->projectlist->group_id,
                        'gmail_connection_id'=>$gmail_conn_id
                        ]);
            }
            
            $id = ProjectEmail::create($insert_email)->id;
            $email_listing = array('project_listing_id'=>$this->projectlist->id,'project_email_id' => $id);
            DB::table('project_listing_emails')
                ->insert($email_listing);
        }

        $this->validateOnly('file1');
        $filename = $this->file1->getClientOriginalName();
        $read_path = $this->file1->getRealPath();
        $jsonData = file_get_contents($read_path);
        $jsonName= str_replace(' ', '', $this->projectlist->name);
        file_put_contents(strtolower($jsonName.'.json'),$jsonData);
        $projectArray = array('project_json' => $jsonName.'.json');
        ProjectListing::where('id',$this->projectlist->id)->update($projectArray);


        $this->uploaded = false;
        $this->file = null;
        $this->rowsCount = 0;
        $this->columnsCount = 0;
        $this->columns = [];

        $this->emit('imported');
        $this->redirectRoute('projectlist.index');
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
       $allColumn = $this->columnValues();
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
