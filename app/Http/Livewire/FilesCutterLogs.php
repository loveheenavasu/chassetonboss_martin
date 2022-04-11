<?php

namespace App\Http\Livewire;

use App\Models\CutterLog;
use Livewire\Component;
use Storage;
use Livewire\WithPagination;

class FilesCutterLogs extends Component
{
    use WithPagination;
    public bool $confirmingConnectionDeletion = false;
    public bool $fileNotAvaiale = false;
    public bool $waitDownloaded = true;
    public ?CutterLog $connectionBeingDeleted = null;
    protected $listeners = [
        'processed' => '$refresh',
    ];

    public function getLogsProperty()
    {
        return CutterLog::query()->latest()->paginate(10);
    }

    public function downloadMergedFile(CutterLog $log)
    {
         if (!file_exists(storage_path($log->chunks_path))){ 
                  $this->fileNotAvaiale = true;
                   
                }else{
                    $this->fileNotAvaiale = false;
                     $this->waitDownloaded = true;
                    return response()->download(storage_path($log->merged_path), $log->merged_filename);
                }
       
    }

    public function downloadChunksArchive(CutterLog $log)
    {
        if (!file_exists(storage_path($log->chunks_path))){ 
                  $this->fileNotAvaiale = true;
                  
                }else{
                    $this->fileNotAvaiale = false;
                     $this->waitDownloaded = true;
                    return response()->download(storage_path($log->chunks_path), $log->chunks_filename);
                }
        
    }
     public function deleteMergedFile(CutterLog $log)
    {

        if(!empty($log->id)){

            $delete = CutterLog::where('id',$log->id)->delete();
             if($delete){
                $file_merged_path = storage_path($log->merged_path);
                $file_chunks_path = storage_path($log->chunks_path);
                if (file_exists(storage_path($log->chunks_path))){ 
                 unlink($file_chunks_path); 
                }
                if (file_exists(storage_path($log->merged_path))){ 
                 unlink($file_merged_path); 
                }
                 
             }
        }
    }

    public function confirmCutterLogsDeletion(CutterLog $log): void
    {
        $this->confirmingConnectionDeletion = true;
        $this->connectionBeingDeleted = $log;
    }
    public function deleteCutterLogs(): void
    {
        
        $file_merged_path = storage_path($this->connectionBeingDeleted->merged_path);
        $file_chunks_path = storage_path($this->connectionBeingDeleted->chunks_path);
        if (file_exists(storage_path($this->connectionBeingDeleted->chunks_path))){ 
         unlink($file_chunks_path); 
        }
        if (file_exists(storage_path($this->connectionBeingDeleted->merged_path))){ 
         unlink($file_merged_path); 
        }
        $this->connectionBeingDeleted->delete();
        $this->confirmingConnectionDeletion = false;
    }
}
