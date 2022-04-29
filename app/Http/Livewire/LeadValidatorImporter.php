<?php

namespace App\Http\Livewire;
use App\Models\Email;
use App\Models\EmailInfo;
use App\Models\LeadValidator;
use App\Models\Keywords;
use App\Models\Blacklist;
use App\Models\Profession;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use League\Csv\Reader;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule as ValidationRule;

class LeadValidatorImporter extends Component
{
    use WithFileUploads;

    public LeadValidator $leadvalidator;
    public bool $uploaded = false;
    public $file = null;
    public int $rowsCount = 0;
    public int $columnsCount = 0;
    public array $columns = [];
    public array $check = [];
    public array $recordsss = [];
    public array $checkreader = [];
    public  $seperator_type = ',' ;
    public array $csvArray = [];
    public array $allmail = [];
    public array $all_mails = [];
    public array $allEmails = [];
    public array $allWebEmail = [];
    public array $allFinalEmail = [];
    public array $alligonerEmail = [];
    public array $allCorpEmail = [];
    public array $WebEmail = [];
    public array $CorpEmail = [];
    public int $countallCorpEmail = 0;
    public int $countallWebEmail = 0;
    public int $skip = 0;
    public $is_profession_checked = 0;
    public $is_allmail_checked = 0;
    public  $is_webmail_checked = 0;
    public  $is_corp_checked = 0;
    public array $professionlist = [];
  

    public  $allFields = '';

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:csv,txt'],
            'leadvalidator.notes' => ['required'],
            'leadvalidator.allmail' => ['nullable'],
            'leadvalidator.webmail' => ['nullable'],
            'leadvalidator.corpmail' => ['nullable'],
            'leadvalidator.onlyhttp' => ['nullable'],
            'leadvalidator.profession' => ['nullable'],
            'professionlist' => ['array', 'min:1'],
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
            ]
        ];
    }

    public function mount(LeadValidator $leadvalidator): void
    {
        if (! $leadvalidator->exists) {
            throw new \InvalidArgumentException('Listing model must exist in database.');
        }

        $this->leadvalidator = $leadvalidator;

    }

    public function getProfessionsProperty(){
        return Profession::get();
    }
    public function updatedFile(): void
    {
        $this->leadvalidator->allmail = 1;
        $this->validateOnly('file');
        $pathh =  $this->file->getRealPath();
        $FileRead = file_get_contents($pathh);
        $arrayDta = explode(',',$FileRead);
        $reader = Reader::createFromPath($this->file->getRealPath());
        $emails = collect($reader->getRecords())->map(function ($record) {
             if(strpos($record[0], '|') !== false){
                $record = explode('|', $record[0]);
                $record = $record[0];
            }

            else if(strpos($record[0], ';') !== false){
                $record = explode(';', $record[0]);
                $record = $record[0];
            }
            else{
                $record = $record[0];
            }
            if($this->skip>0){
                
             array_push($this->allEmails, $record);
            }
            $this->skip++;
        })->filter();
        $all_blacklist_data = Blacklist::get()->toArray();
        $count = count($this->allEmails);

        for($i=0; $i<$count; $i++){
            foreach($all_blacklist_data as $single){
                if ((Str::contains($this->allEmails[$i], strtolower($single['name'])))) {
                    if(!in_array($this->allEmails[$i], $this->alligonerEmail, true))
                    {
                        array_push($this->alligonerEmail, $this->allEmails[$i]);
                    }
                }
                else
                {
                    if(!in_array($this->allEmails[$i], $this->allFinalEmail, true))
                    {
                        array_push($this->allFinalEmail, $this->allEmails[$i]);
                    }
                }     
            }        
        }
        $finalemails = array_merge(array_diff($this->allFinalEmail, $this->alligonerEmail),array_diff($this->alligonerEmail, $this->allFinalEmail));
        $this->allFinalEmail = $finalemails;
         
        $all_db_webmails = Keywords::get()->toArray();
        $count = count($this->allFinalEmail);
        for($i=0; $i<$count; $i++){
            foreach($all_db_webmails as $single){
                if (Str::contains($this->allFinalEmail[$i], $single['name'])) {
                    if(!in_array($this->allFinalEmail[$i], $this->allWebEmail, true)){
                        array_push($this->allWebEmail, $this->allFinalEmail[$i]);
                    }
                }else{  
                    if(!in_array($this->allFinalEmail[$i], $this->allCorpEmail, true)){
                        array_push($this->allCorpEmail, $this->allFinalEmail[$i]);
                    }
                } 
            }
                 
        }
         
        $output = array_merge(array_diff($this->allCorpEmail, $this->allWebEmail),array_diff($this->allWebEmail, $this->allCorpEmail));
        $this->allCorpEmail = $output;
        $this->countallCorpEmail = count($this->allCorpEmail);
        $this->countallWebEmail = count($this->allWebEmail);
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
        ] + EmailInfo::typeOptions();
    }

    
    function array_unique_multidimensional($input)
    {
        $serialized = array_map('serialize', $input);
        $unique = array_unique($serialized);
        return array_intersect_key($input, $unique);
    }

    public function import(): void
    {
        $this->columns = array_map('strtolower', $this->columns);
        $webmail=[];
        $corpmail=[];
        $allmail=[];
        $allValidEmails = [];
        $allInvalidEmails = [];
        $allUnknownEmails = [];
        $allValidCorpEmails = [];
        $allInvalidCorpEmails = [];
        $allUnknownCorpEmails = [];
        $allValidWebEmails = [];
        $allInvalidWebEmails = [];
        $allUnknownWebEmails = [];
        $testing = [];
        if (! $this->uploaded) {
            return;
        }
        $notesListing = $this->leadvalidator->toArray();
        $this->is_profession_checked = $this->leadvalidator->profession;
        $this->is_allmail_checked = $this->leadvalidator->allmail;
        $this->is_webmail_checked = $this->leadvalidator->webmail;
        $this->is_corp_checked =    $this->leadvalidator->corpmail;
        $this->check_http =  $this->leadvalidator->onlyhttp;
        $name = str_replace(' ', '', strtolower($notesListing['name']));
        $name = str_replace(array(':', '\\', '/', '*','-','_'), '', $name);
    
        if(!empty($notesListing)){
            $notes = $notesListing['notes'];
            $insNotes = LeadValidator::where('id',$notesListing['id'])->update([
                                    'notes' => $notes,
                                    ]);
            $this->validateOnly('columns');
            $reader = Reader::createFromPath($this->file->getRealPath());
        }
        $allColumns = array_filter($this->columns);
        $key = array_search('email', $this->columns);
        $temp = array($key => $allColumns[$key]);
        $mainColumn = $temp + $allColumns;
        $headerValuesSort[] = $mainColumn;

        $emails = collect($reader->getRecords())->map(function ($record) {
            $columns = array_filter($this->columns);
            $key = array_search('email', $this->columns);
            if (! $email = $record[$key]) {
                session()->flash('message', 'Something Went Wrong!');
                return null;
            }

            if (! Str::contains($email, '@')) {
                session()->flash('message', 'Something Went Wrong!');
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

            if(strpos($record[0], '|') !== false){
                $record = explode('|', $record[0]);
               
            }
            if(strpos($record[0], ';') !== false){
                $record = explode(';', $record[0]);
                
            }
            
            if(strpos($record[0], ',') !== false){
                $record = explode(',', $record[0]);
                
            }
            $i = 0;
            if(!empty($columns)){
                foreach ($columns as $index => $type) {
                    if (!isset($record[$index])) {
                        continue;
                    }
                    $this->csvArray[$email][$i] = $record[$index];
                    $i++;

                }
            }else{
               $this->csvArray[$email][] = '';
            }

        })->filter();
        if(array_search('website', $this->columns)){
            $website = array_search('website', $this->columns);
        }else{
            $website = array_search('website url', $this->columns);
        }
        $professionkey = array_search('profession', $this->columns);
        $headerValues[] = $this->columns;
        $keywordArray = [];
        $all_keywords = Profession::whereIn('id',$this->professionlist)->get()->pluck('keyword')->toArray();

        $all_promails = [];
        foreach ($all_keywords as $key => $all_keyword) {
            $keywordArray = explode('\n',$all_keyword);
            $all_promails = array_merge($all_promails,$keywordArray);
        }

        
        // Don't Run Validater Check Start
        if($this->is_allmail_checked == 1 && $this->check_http == 0 && $this->is_profession_checked  == 0 && $this->is_webmail_checked == 0 && $this->is_corp_checked == 0){
            foreach($this->csvArray as $key => $res){
                //$res = array_unique($res);
                array_unshift($res,$key);
                $count = count($this->allWebEmail);
                for($i = 0;$i<$count; $i++){
                    if($key == $this->allWebEmail[$i]){
                        if(!empty($website)){
                            $parse = parse_url($res[$website]);
                            $res[$website] = $parse['scheme'].'://'.$parse['host'];
                        }
                        $webmail[]=$res;
                    }
                }
            }

            foreach($this->csvArray as $key => $res){
                //$res = array_unique($res);
                array_unshift($res,$key);
                $count = count($this->allCorpEmail);
                for($i = 0;$i<$count; $i++){
                    if($key == $this->allCorpEmail[$i]){
                        if(!empty($website)){
                            $parse = parse_url($res[$website]);
                            $res[$website] = $parse['scheme'].'://'.$parse['host'];
                        }
                        $corpmail[]=$res; 
                    }
                }   
            }
            $allValidEmails = array_unique($webmail, SORT_REGULAR);
            $allInvalidEmails = array_unique($corpmail, SORT_REGULAR);
            $allValidEmails = array_merge($headerValuesSort,$allValidEmails);
            $allInvalidEmails = array_merge($headerValuesSort,$allInvalidEmails); 
      
            $fpvalid = fopen($name.'-allwebemail.csv', 'w');
            $fpinvalid = fopen($name.'-allcorpemail.csv', 'w');
            foreach($allValidEmails as $result){
                fputcsv($fpvalid, $result);
            }
            foreach($allInvalidEmails as $res){
                fputcsv($fpinvalid, $res);
            }
        }
        // Don't Run Validater Check End

        // Don't Run Validater and http checked start 
        elseif($this->is_allmail_checked == 1 && $this->check_http == 1 && $this->is_profession_checked  == 0 && $this->is_webmail_checked == 0 && $this->is_corp_checked == 0){
            foreach($this->csvArray as $key => $res){
                //$res = array_unique($res);
                array_unshift($res,$key);
                $count = count($this->allWebEmail);
                for($i = 0;$i<$count; $i++){
                    if($key == $this->allWebEmail[$i]){
                        $timeout = 10;
                        $url = $res[$website];
                        $parse = parse_url($url);
                        $url = substr($url, 0, strrpos( $url, '/'));
                        if(!empty($parse)){
                            $ch = curl_init($url);
                            curl_setopt ( $ch, CURLOPT_URL, $url );
                            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                            curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                            $http_respond = curl_exec($ch);
                            $http_respond = trim( strip_tags( $http_respond ) );
                            $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                            if(( $http_code == "200" ) || ( $http_code == "302" ))
                            {
                                $res[$website] = 'http://'.$parse['host'];
                                $webmail[]=$res;
                            }
                            else
                            {
                                $res[$website] = '';
                            }
                        }  
                    }
                }
            }

            foreach($this->csvArray as $key => $res){
                //$res = array_unique($res);
                array_unshift($res,$key);
                $count = count($this->allCorpEmail);
                for($i = 0;$i<$count; $i++){
                    if($key == $this->allCorpEmail[$i]){
                        $timeout = 10;
                        $url = $res[$website];
                        $parse = parse_url($url);
                        $url = substr($url, 0, strrpos( $url, '/'));
                        if(!empty($parse)){
                            $ch = curl_init($url);
                            curl_setopt ( $ch, CURLOPT_URL, $url );
                            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                            curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                            $http_respond = curl_exec($ch);
                            $http_respond = trim( strip_tags( $http_respond ) );
                            $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                            if(( $http_code == "200" ) || ( $http_code == "302" ))
                            {
                                $res[$website] = 'http://'.$parse['host'];
                                $corpmail[]=$res; 
                            }
                            else
                            {
                                $res[$website] = '';
                            }
                        }
                        
                    }
                  }   
            }
            $allValidEmails = array_unique($webmail, SORT_REGULAR);
            $allInvalidEmails = array_unique($corpmail, SORT_REGULAR);
            $allValidEmails = array_merge($headerValuesSort,$allValidEmails);
            $allInvalidEmails = array_merge($headerValuesSort,$allInvalidEmails); 
      
            $fpvalid = fopen($name.'-allwebemail.csv', 'w');
            $fpinvalid = fopen($name.'-allcorpemail.csv', 'w');
            foreach($allValidEmails as $result){
                fputcsv($fpvalid, $result);
            }
            foreach($allInvalidEmails as $res){
                fputcsv($fpinvalid, $res);
            }
        }
        // Don't Run Validater and http checked end 

        // Don't Run Validater and Profession checked start 
        elseif($this->is_allmail_checked == 1 && $this->is_profession_checked  == 1 && $this->is_webmail_checked == 0 && $this->is_corp_checked == 0 && $this->check_http == 0 ){
            $webmail = [];
            $corpmail = [];
    
            foreach($this->csvArray as $key => $res){
                //$res = array_unique($res);
                array_unshift($res,$key);
                $count = count($this->allWebEmail);
                for($i = 0;$i<$count; $i++){
                    if($key == $this->allWebEmail[$i]){
                       $professionValue = strtolower($res[$professionkey]);
                       $all_promails =  array_map('strtolower', $all_promails);
                       $all_promails =  array_map('trim', $all_promails);
                       $word_count = str_word_count($professionValue);
                        if(!empty($website)){
                            $parse = parse_url($res[$website]);
                            $res[$website] = $parse['scheme'].'://'.$parse['host'];
                        }
                        if($word_count>1){
                        //$professionValue = explode(' ', $professionValue);
                         //foreach($professionValue as $value){
                            foreach($all_promails as $sinle){
                                $result = strcmp($professionValue,$sinle);
                                if($result == 0){
                                    $webmail[]=$res;
                                }
                                //$word_count = str_word_count($sinle);
                                   // if($word_count>1){
                                   //    $sinle = explode(' ', $sinle);
                                   //    foreach($sinle as $s){
                                   //      if($s == $value ){
                                   //          $webmail[]=$res;
                                   //      }
                                   //    }
                                   //  }else{
                                   //   if (in_array($value , $all_promails)) {
                                   //       $webmail[]=$res;
                                   //   }
                                   //  }
                                //echo "<pre>"; print_r($result);
                            }
                            
                        //}
                       
                        }else{
                             foreach($all_promails as $sinle){
                                $result = strcmp($professionValue,$sinle);
                                  //$word_count = str_word_count($sinle);
                                   // if($word_count>1){
                                   //  $sinle = explode(' ', $sinle);
                                   //    foreach($sinle as $s){
                                   //      if($s == $professionValue ){
                                   //          $webmail[]=$res;
                                   //      }
                                   //    }
                                   // }else{
                                   //   if (in_array($professionValue , $all_promails)) {
                                   //       $webmail[]=$res;
                                   //  }
                                   // }
                                if($result == 0){
                                    $webmail[]=$res;
                                }
                               // echo "<pre>"; print_r($result);
                            }
                           
                        }
                    }
                }
            }
            
           
            foreach($this->csvArray as $key => $res){
                //$res = array_unique($res);
                array_unshift($res,$key);
                $count = count($this->allCorpEmail);
                for($i = 0;$i<$count; $i++){
                    if($key == $this->allCorpEmail[$i]){

                       $professionValue = strtolower($res[$professionkey]);
                       
                        $word_count = str_word_count($professionValue);
                        $all_promails =  array_map('strtolower', $all_promails);
                        $all_promails =  array_map('trim', $all_promails);
                        if(!empty($website)){
                            $parse = parse_url($res[$website]);
                            $res[$website] = $parse['scheme'].'://'.$parse['host'];
                        }
                        if($word_count>1){
                        //$professionValue = explode(' ', $professionValue);

                         //foreach($professionValue as $value){
                            foreach($all_promails as $sinle){
                                $result = strcmp($professionValue,$sinle);
                                  // $word_count = str_word_count($sinle);
                                  //  if($word_count>1){
                                  //   $sinle = explode(' ', $sinle);
                                  //     foreach($sinle as $s){
                                  //       if($s == $value ){
                                  //           $corpmail[]=$res;
                                  //       }
                                  //     }
                                  //  }else{
                                  //    if (in_array($value , $all_promails)) {
                                  //        $corpmail[]=$res;
                                  //   }
                                  //  }
                                if($result == 0){
                                    $corpmail[]=$res;
                                }
                            }
                            
                        //}
                       
                        }else{
                             foreach($all_promails as $sinle){
                                  //$word_count = str_word_count($sinle);
                                   // if($word_count>1){
                                   //  $sinle = explode(' ', $sinle);
                                   //    foreach($sinle as $s){
                                   //      if($s == $professionValue ){
                                            
                                   //          $corpmail[]=$res;
                                   //      }
                                   //    }
                                   // }else{
                                   //   if (in_array($professionValue , $all_promails)) {
                                   //       $corpmail[]=$res;
                                   //  }
                                   // }
                                if($result == 0){
                                    $corpmail[]=$res;
                                }
                            }
                           
                        }
                        
                    }
                    
                }
            }
            
            $webmail = array_unique($webmail, SORT_REGULAR);
            $corpmail = array_unique($corpmail, SORT_REGULAR);
            $allValidEmails = array_merge($headerValuesSort,$webmail);
            $allInvalidEmails = array_merge($headerValuesSort,$corpmail); 

            $fpvalid = fopen($name.'-allwebemail.csv', 'w');
            $fpinvalid = fopen($name.'-allcorpemail.csv', 'w');
            foreach($allValidEmails as $result){
                fputcsv($fpvalid, $result);
            }
            foreach($allInvalidEmails as $res){
                fputcsv($fpinvalid, $res);
            } 
        }
        // Don't Run Validater and Profession checked end

        // Don't Run Validater, http checked and Profession checked start
        elseif($this->is_allmail_checked == 1 && $this->is_profession_checked  == 1 && $this->check_http == 1 && $this->is_webmail_checked == 0 && $this->is_corp_checked == 0 ){
            $corpmail = [];
            $webmail= [];
            foreach($this->csvArray as $key => $res){
                    //$res = array_unique($res);
                    array_unshift($res,$key);
                    $count = count($this->allWebEmail);
                    for($i = 0;$i<$count; $i++){
                        if($key == $this->allWebEmail[$i]){ 
                            $timeout = 10;
                            $url = $res[$website];
                            $parse = parse_url($url);
                            $url = substr($url, 0, strrpos( $url, '/'));
                            if(!empty($parse)){
                       $professionValue = strtolower($res[$professionkey]);
                       
                        $word_count = str_word_count($professionValue);
                        $all_promails =  array_map('strtolower', $all_promails);
                        $all_promails =  array_map('trim', $all_promails);
                        if($word_count>1){
                        $professionValue = explode(' ', $professionValue);

                         foreach($professionValue as $value){
                            foreach($all_promails as $sinle){
                                  $word_count = str_word_count($sinle);
                                   if($word_count>1){
                                    $sinle = explode(' ', $sinle);
                                      foreach($sinle as $s){
                                        if($s == $value ){
                                            
                                        $ch = curl_init($url);
                                        curl_setopt ( $ch, CURLOPT_URL, $url );
                                        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                        curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                        $http_respond = curl_exec($ch);
                                        $http_respond = trim( strip_tags( $http_respond ) );
                                        $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                        if(( $http_code == "200" ) || ( $http_code == "302" ))
                                        {
                                            $res[$website] = 'http://'.$parse['host'];
                                            $webmail[]=$res;   
                                        }
                                        else
                                        {
                                            $res[$website] = '';
                                        } 
                                        }
                                      }
                                   }else{
                                     if (in_array($value , $all_promails)) {
                                         
                                        $ch = curl_init($url);
                                        curl_setopt ( $ch, CURLOPT_URL, $url );
                                        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                        curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                        $http_respond = curl_exec($ch);
                                        $http_respond = trim( strip_tags( $http_respond ) );
                                        $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                        if(( $http_code == "200" ) || ( $http_code == "302" ))
                                        {
                                            $res[$website] = 'http://'.$parse['host'];
                                            $webmail[]=$res;   
                                        }
                                        else
                                        {
                                            $res[$website] = '';
                                        } 
                                    }
                                   }
                            }
                            
                        }
                       
                        }else{
                             foreach($all_promails as $sinle){
                                  $word_count = str_word_count($sinle);
                                   if($word_count>1){
                                    $sinle = explode(' ', $sinle);
                                      foreach($sinle as $s){
                                        if($s == $professionValue ){
                                            
                                            
                                        $ch = curl_init($url);
                                        curl_setopt ( $ch, CURLOPT_URL, $url );
                                        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                        curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                        $http_respond = curl_exec($ch);
                                        $http_respond = trim( strip_tags( $http_respond ) );
                                        $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                        if(( $http_code == "200" ) || ( $http_code == "302" ))
                                        {
                                            $res[$website] = 'http://'.$parse['host'];
                                            $webmail[]=$res;   
                                        }
                                        else
                                        {
                                            $res[$website] = '';
                                        } 
                                        }
                                      }
                                   }else{
                                     if (in_array($professionValue , $all_promails)) {
                                         
                                        $ch = curl_init($url);
                                        curl_setopt ( $ch, CURLOPT_URL, $url );
                                        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                        curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                        $http_respond = curl_exec($ch);
                                        $http_respond = trim( strip_tags( $http_respond ) );
                                        $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                        if(( $http_code == "200" ) || ( $http_code == "302" ))
                                        {
                                            $res[$website] = 'http://'.$parse['host'];
                                            $webmail[]=$res;   
                                        }
                                        else
                                        {
                                            $res[$website] = '';
                                        } 
                                    }
                                   }
                            }
                           
                        }
                            }         
                        }
                    }
                }
                
                foreach($this->csvArray as $key => $res){
                   // $res = array_unique($res);
                    array_unshift($res,$key);
                    $count = count($this->allCorpEmail);
                    for($i = 0;$i<$count; $i++){
                        if($key == $this->allCorpEmail[$i]){
                            $timeout = 10;
                            $url = $res[$website];
                            $parse = parse_url($url);
                            $url = substr($url, 0, strrpos( $url, '/'));
                            if(!empty($parse)){
                                $professionValue = strtolower($res[$professionkey]);
                       
                        $word_count = str_word_count($professionValue);
                        $all_promails =  array_map('strtolower', $all_promails);
                        $all_promails =  array_map('trim', $all_promails);

                        if($word_count>1){
                        $professionValue = explode(' ', $professionValue);

                         foreach($professionValue as $value){
                            foreach($all_promails as $sinle){
                                  $word_count = str_word_count($sinle);
                                   if($word_count>1){
                                    $sinle = explode(' ', $sinle);
                                      foreach($sinle as $s){
                                        if($s == $value ){
                                            
                                        $ch = curl_init($url);
                                        curl_setopt ( $ch, CURLOPT_URL, $url );
                                        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                        curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                        $http_respond = curl_exec($ch);
                                        $http_respond = trim( strip_tags( $http_respond ) );
                                        $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                        if(( $http_code == "200" ) || ( $http_code == "302" ))
                                        {
                                            $res[$website] = 'http://'.$parse['host'];
                                            $corpmail[]=$res;   
                                        }
                                        else
                                        {
                                            $res[$website] = '';
                                        } 
                                        }
                                      }
                                   }else{
                                     if (in_array($value , $all_promails)) {
                                         
                                        $ch = curl_init($url);
                                        curl_setopt ( $ch, CURLOPT_URL, $url );
                                        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                        curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                        $http_respond = curl_exec($ch);
                                        $http_respond = trim( strip_tags( $http_respond ) );
                                        $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                        if(( $http_code == "200" ) || ( $http_code == "302" ))
                                        {
                                            $res[$website] = 'http://'.$parse['host'];
                                            $corpmail[]=$res;   
                                        }
                                        else
                                        {
                                            $res[$website] = '';
                                        } 
                                    }
                                   }
                            }
                            
                        }
                       
                        }else{
                             foreach($all_promails as $sinle){
                                  $word_count = str_word_count($sinle);
                                   if($word_count>1){
                                    $sinle = explode(' ', $sinle);
                                      foreach($sinle as $s){
                                        if($s == $professionValue ){
                                            
                                            
                                        $ch = curl_init($url);
                                        curl_setopt ( $ch, CURLOPT_URL, $url );
                                        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                        curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                        $http_respond = curl_exec($ch);
                                        $http_respond = trim( strip_tags( $http_respond ) );
                                        $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                        if(( $http_code == "200" ) || ( $http_code == "302" ))
                                        {
                                            $res[$website] = 'http://'.$parse['host'];
                                            $corpmail[]=$res;   
                                        }
                                        else
                                        {
                                            $res[$website] = '';
                                        } 
                                        }
                                      }
                                   }else{
                                     if (in_array($professionValue , $all_promails)) {
                                         
                                        $ch = curl_init($url);
                                        curl_setopt ( $ch, CURLOPT_URL, $url );
                                        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                        curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                        $http_respond = curl_exec($ch);
                                        $http_respond = trim( strip_tags( $http_respond ) );
                                        $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                        if(( $http_code == "200" ) || ( $http_code == "302" ))
                                        {
                                            $res[$website] = 'http://'.$parse['host'];
                                            $corpmail[]=$res;   
                                        }
                                        else
                                        {
                                            $res[$website] = '';
                                        } 
                                    }
                                   }
                            }
                           
                        }
                            }
                        }
                        
                    } 
                        
                }
                $webmail = array_unique($webmail, SORT_REGULAR);
                $corpmail = array_unique($corpmail, SORT_REGULAR);
                $allValidEmails = array_merge($headerValuesSort,$webmail);
                $allInvalidEmails = array_merge($headerValuesSort,$corpmail); 
          
                $fpvalid = fopen($name.'-allwebemail.csv', 'w');
                $fpinvalid = fopen($name.'-allcorpemail.csv', 'w');
                foreach($allValidEmails as $result){
                    fputcsv($fpvalid, $result);
                }
                foreach($allInvalidEmails as $res){
                    fputcsv($fpinvalid, $res);
                }

        }
        // Don't Run Validater, http checked and Profession checked end

        // Http checked and Profession checked start
        elseif($this->is_allmail_checked == 0 && $this->is_profession_checked  == 1 && $this->check_http == 1 && $this->is_webmail_checked == 0 && $this->is_corp_checked == 0 ){
            $corpmail = [];
            $webmail= [];
            foreach($this->csvArray as $key => $res){
                    //$res = array_unique($res);
                    array_unshift($res,$key);
                    $count = count($this->allWebEmail);
                    for($i = 0;$i<$count; $i++){
                        if($key == $this->allWebEmail[$i]){ 
                            $timeout = 10;
                            $url = $res[$website];
                            $parse = parse_url($url);
                            $url = substr($url, 0, strrpos( $url, '/'));
                            if(!empty($parse)){
                                $professionValue = strtolower($res[$professionkey]);
                       
                        $word_count = str_word_count($professionValue);
                        $all_promails =  array_map('strtolower', $all_promails);
                        $all_promails =  array_map('trim', $all_promails);
                        if($word_count>1){
                        $professionValue = explode(' ', $professionValue);

                         foreach($professionValue as $value){
                            foreach($all_promails as $sinle){
                                  $word_count = str_word_count($sinle);
                                   if($word_count>1){
                                    $sinle = explode(' ', $sinle);
                                      foreach($sinle as $s){
                                        if($s == $value ){
                                            
                                        $ch = curl_init($url);
                                        curl_setopt ( $ch, CURLOPT_URL, $url );
                                        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                        curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                        $http_respond = curl_exec($ch);
                                        $http_respond = trim( strip_tags( $http_respond ) );
                                        $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                        if(( $http_code == "200" ) || ( $http_code == "302" ))
                                        {
                                            $res[$website] = 'http://'.$parse['host'];
                                            $webmail[]=$res;   
                                        }
                                        else
                                        {
                                            $res[$website] = '';
                                        } 
                                        }
                                      }
                                   }else{
                                     if (in_array($value , $all_promails)) {
                                         
                                        $ch = curl_init($url);
                                        curl_setopt ( $ch, CURLOPT_URL, $url );
                                        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                        curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                        $http_respond = curl_exec($ch);
                                        $http_respond = trim( strip_tags( $http_respond ) );
                                        $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                        if(( $http_code == "200" ) || ( $http_code == "302" ))
                                        {
                                            $res[$website] = 'http://'.$parse['host'];
                                            $webmail[]=$res;   
                                        }
                                        else
                                        {
                                            $res[$website] = '';
                                        } 
                                    }
                                   }
                            }
                            
                        }
                       
                        }else{
                             foreach($all_promails as $sinle){
                                  $word_count = str_word_count($sinle);
                                   if($word_count>1){
                                    $sinle = explode(' ', $sinle);
                                      foreach($sinle as $s){
                                        if($s == $professionValue ){
                                            
                                            
                                        $ch = curl_init($url);
                                        curl_setopt ( $ch, CURLOPT_URL, $url );
                                        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                        curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                        $http_respond = curl_exec($ch);
                                        $http_respond = trim( strip_tags( $http_respond ) );
                                        $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                        if(( $http_code == "200" ) || ( $http_code == "302" ))
                                        {
                                            $res[$website] = 'http://'.$parse['host'];
                                            $webmail[]=$res;   
                                        }
                                        else
                                        {
                                            $res[$website] = '';
                                        } 
                                        }
                                      }
                                   }else{
                                     if (in_array($professionValue , $all_promails)) {
                                         
                                        $ch = curl_init($url);
                                        curl_setopt ( $ch, CURLOPT_URL, $url );
                                        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                        curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                        $http_respond = curl_exec($ch);
                                        $http_respond = trim( strip_tags( $http_respond ) );
                                        $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                        if(( $http_code == "200" ) || ( $http_code == "302" ))
                                        {
                                            $res[$website] = 'http://'.$parse['host'];
                                            $webmail[]=$res;   
                                        }
                                        else
                                        {
                                            $res[$website] = '';
                                        } 
                                    }
                                   }
                            }
                           
                        }
                            }
                        }
                    }
                }
                foreach($this->csvArray as $key => $res){
                    //$res = array_unique($res);
                    array_unshift($res,$key);
                    $count = count($this->allCorpEmail);
                    for($i = 0;$i<$count; $i++){
                        if($key == $this->allCorpEmail[$i]){
                            $timeout = 10;
                            $url = $res[$website];
                            $parse = parse_url($url);
                            $url = substr($url, 0, strrpos( $url, '/'));
                            if(!empty($parse)){
                             $professionValue = strtolower($res[$professionkey]);
                       
                        $word_count = str_word_count($professionValue);
                        $all_promails =  array_map('strtolower', $all_promails);
                        $all_promails =  array_map('trim', $all_promails);
                        if($word_count>1){
                        $professionValue = explode(' ', $professionValue);

                         foreach($professionValue as $value){
                            foreach($all_promails as $sinle){
                                  $word_count = str_word_count($sinle);
                                   if($word_count>1){
                                    $sinle = explode(' ', $sinle);
                                      foreach($sinle as $s){
                                        if($s == $value ){
                                            
                                        $ch = curl_init($url);
                                        curl_setopt ( $ch, CURLOPT_URL, $url );
                                        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                        curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                        $http_respond = curl_exec($ch);
                                        $http_respond = trim( strip_tags( $http_respond ) );
                                        $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                        if(( $http_code == "200" ) || ( $http_code == "302" ))
                                        {
                                            $res[$website] = 'http://'.$parse['host'];
                                            $corpmail[]=$res;   
                                        }
                                        else
                                        {
                                            $res[$website] = '';
                                        } 
                                        }
                                      }
                                   }else{
                                     if (in_array($value , $all_promails)) {
                                         
                                        $ch = curl_init($url);
                                        curl_setopt ( $ch, CURLOPT_URL, $url );
                                        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                        curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                        $http_respond = curl_exec($ch);
                                        $http_respond = trim( strip_tags( $http_respond ) );
                                        $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                        if(( $http_code == "200" ) || ( $http_code == "302" ))
                                        {
                                            $res[$website] = 'http://'.$parse['host'];
                                            $corpmail[]=$res;   
                                        }
                                        else
                                        {
                                            $res[$website] = '';
                                        } 
                                    }
                                   }
                            }
                            
                        }
                       
                        }else{
                             foreach($all_promails as $sinle){
                                  $word_count = str_word_count($sinle);
                                   if($word_count>1){
                                    $sinle = explode(' ', $sinle);
                                      foreach($sinle as $s){
                                        if($s == $professionValue ){
                                            
                                            
                                        $ch = curl_init($url);
                                        curl_setopt ( $ch, CURLOPT_URL, $url );
                                        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                        curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                        $http_respond = curl_exec($ch);
                                        $http_respond = trim( strip_tags( $http_respond ) );
                                        $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                        if(( $http_code == "200" ) || ( $http_code == "302" ))
                                        {
                                            $res[$website] = 'http://'.$parse['host'];
                                            $corpmail[]=$res;   
                                        }
                                        else
                                        {
                                            $res[$website] = '';
                                        } 
                                        }
                                      }
                                   }else{
                                     if (in_array($professionValue , $all_promails)) {
                                         
                                        $ch = curl_init($url);
                                        curl_setopt ( $ch, CURLOPT_URL, $url );
                                        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                        curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                        $http_respond = curl_exec($ch);
                                        $http_respond = trim( strip_tags( $http_respond ) );
                                        $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                        if(( $http_code == "200" ) || ( $http_code == "302" ))
                                        {
                                            $res[$website] = 'http://'.$parse['host'];
                                            $corpmail[]=$res;   
                                        }
                                        else
                                        {
                                            $res[$website] = '';
                                        } 
                                    }
                                   }
                            }
                           
                        }
                            }
                        }
                        
                    } 
                        
                }
                $webmail = array_unique($webmail, SORT_REGULAR);
                $corpmail = array_unique($corpmail, SORT_REGULAR);
                $allValidEmails = array_merge($headerValuesSort,$webmail);
                $allInvalidEmails = array_merge($headerValuesSort,$corpmail); 
          
                $fpvalid = fopen($name.'-allwebemail.csv', 'w');
                $fpinvalid = fopen($name.'-allcorpemail.csv', 'w');
                foreach($allValidEmails as $result){
                    fputcsv($fpvalid, $result);
                }
                foreach($allInvalidEmails as $res){
                    fputcsv($fpinvalid, $res);
                }
        }
        // Http checked and Profession checked end

        // Http checked and valid Corp checked start
        elseif($this->is_allmail_checked == 0 && $this->is_profession_checked  == 0 && $this->check_http == 1 && $this->is_webmail_checked == 0 && $this->is_corp_checked == 1 ){
            foreach($this->csvArray as $key => $res){
                    //$res = array_unique($res);
                    array_unshift($res,$key);
                    $count = count($this->allCorpEmail);
                    for($i = 0;$i<$count; $i++){
                    if($key == $this->allCorpEmail[$i]){
                        $curl = curl_init();
                            curl_setopt_array($curl, array(
                                CURLOPT_URL => 'https://api.neverbounce.com/v4/single/check?key=private_a858390e9dc3175c6e809053edc7349f&email='.$key.' ',
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 0,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'GET',
                            ));
                            $response = curl_exec($curl);
                            curl_close($curl);
                            $validation_check = json_decode($response); 
                            if($validation_check->result == "invalid"){
                                $timeout = 10;
                                $url = $res[$website];
                                $parse = parse_url($url);
                                $url = substr($url, 0, strrpos( $url, '/'));
                                if(!empty($parse)){
                                    $ch = curl_init($url);
                                    curl_setopt ( $ch, CURLOPT_URL, $url );
                                    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                    curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                    $http_respond = curl_exec($ch);
                                    $http_respond = trim( strip_tags( $http_respond ) );
                                    $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                    if(( $http_code == "200" ) || ( $http_code == "302" ))
                                    {
                                        $res[$website] = 'http://'.$parse['host'];
                                        $allInvalidEmails[] = $res;
                                    }
                                    else
                                    {
                                        $res[$website] = '';
                                    }
                                }
                                
                            }elseif($validation_check->result == "unknown"){
                                $timeout = 10;
                                $url = $res[$website];
                                $parse = parse_url($url);
                                $url = substr($url, 0, strrpos( $url, '/'));
                                if(!empty($parse)){
                                    $ch = curl_init($url);
                                    curl_setopt ( $ch, CURLOPT_URL, $url );
                                    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                    curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                    $http_respond = curl_exec($ch);
                                    $http_respond = trim( strip_tags( $http_respond ) );
                                    $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                    if(($http_code == "200" ) || ($http_code == "302"))
                                    {
                                        $res[$website] = 'http://'.$parse['host'];
                                        $allUnknownEmails[] = $res;
                                    }else{
                                       $res[$website] = '';
                                    }
                                }
                                
                            }else{
                                $timeout = 10;
                                $url = $res[$website];
                                $parse = parse_url($url);
                                $url = substr($url, 0, strrpos( $url, '/'));
                                if(!empty($parse)){
                                    $ch = curl_init($url);
                                    curl_setopt ( $ch, CURLOPT_URL, $url );
                                    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                    curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                    $http_respond = curl_exec($ch);
                                    $http_respond = trim( strip_tags( $http_respond ) );
                                    $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                    if(( $http_code == "200" ) || ( $http_code == "302" ))
                                    {
                                        $res[$website] = 'http://'.$parse['host'];
                                        $allValidEmails[] = $res;
                                    }else{
                                       $res[$website] = '';
                                    }
                                }
                                
                            }
                    }
                }
            }
            $allValidEmails = array_unique($allValidEmails, SORT_REGULAR);
            $allInvalidEmails = array_unique($allInvalidEmails, SORT_REGULAR);
            $allUnknownEmails = array_unique($allUnknownEmails, SORT_REGULAR);
            $allValidEmails = array_merge($headerValuesSort,$allValidEmails);

            $allInvalidEmails = array_merge($headerValuesSort,$allInvalidEmails); 

            $allUnknownEmails = array_merge($headerValuesSort,$allUnknownEmails); 
                $fpvalid = fopen($name.'-valid-corp.csv', 'w');
                $fpinvalid = fopen($name.'-invalid-corp.csv', 'w');
                $fpunknown = fopen($name.'-unknown-corp.csv', 'w');
               
                foreach($allValidEmails as $result){

                    fputcsv($fpvalid, $result);
                }
                foreach($allInvalidEmails as $res){

                    fputcsv($fpinvalid, $res);
                }
                foreach($allUnknownEmails as $res){
                    fputcsv($fpunknown, $res);
                }

        }
        // Http checked and valid Corp checked end

        // Http checked and valid Web checked start

        elseif($this->is_allmail_checked == 0 && $this->is_profession_checked  == 0 && $this->check_http == 1 && $this->is_webmail_checked == 1 && $this->is_corp_checked == 0 ){
            foreach($this->csvArray as $key => $res){
                    //$res = array_unique($res);
                    array_unshift($res,$key);
                    $count = count($this->allWebEmail);
                        for($i = 0;$i<$count; $i++){
                            if($key == $this->allWebEmail[$i]){
                                $curl = curl_init();
                                curl_setopt_array($curl, array(
                                CURLOPT_URL => 'https://api.neverbounce.com/v4/single/check?key=private_a858390e9dc3175c6e809053edc7349f&email='.$key.' ',
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 0,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'GET',
                            ));
                            $response = curl_exec($curl);
                            curl_close($curl);
                            $validation_check = json_decode($response); 
                            if($validation_check->result == "invalid"){
                                $timeout = 10;
                                $url = $res[$website];
                                $parse = parse_url($url);
                                $url = substr($url, 0, strrpos( $url, '/'));
                                if(!empty($parse)){
                                    $ch = curl_init($url);
                                    curl_setopt ( $ch, CURLOPT_URL, $url );
                                    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                    curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                    $http_respond = curl_exec($ch);
                                    $http_respond = trim( strip_tags( $http_respond ) );
                                    $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                    if(( $http_code == "200" ) || ( $http_code == "302" ))
                                    {
                                        $res[$website] = 'http://'.$parse['host'];
                                        $allInvalidEmails[] = $res;
                                    }else{
                                       $res[$website] = '';
                                    }
                                }
                                
                            }elseif($validation_check->result == "unknown"){
                                $timeout = 10;
                                $url = $res[$website];
                                $parse = parse_url($url);
                                $url = substr($url, 0, strrpos( $url, '/'));
                                if(!empty($parse)){
                                    $ch = curl_init($url);
                                    curl_setopt ( $ch, CURLOPT_URL, $url );
                                    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                    curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                    $http_respond = curl_exec($ch);
                                    $http_respond = trim( strip_tags( $http_respond ) );
                                    $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                    if(( $http_code == "200" ) || ( $http_code == "302" ))
                                    {
                                        $res[$website] = 'http://'.$parse['host'];
                                        $allUnknownEmails[] = $res;
                                    }else{
                                       $res[$website] = '';
                                    }
                                }
                                
                            }else{
                                $timeout = 10;
                                $url = $res[$website];
                                $parse = parse_url($url);
                                $url = substr($url, 0, strrpos( $url, '/'));
                                if(!empty($parse)){
                                    $ch = curl_init($url);
                                    curl_setopt ( $ch, CURLOPT_URL, $url );
                                    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                    curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                    $http_respond = curl_exec($ch);
                                    $http_respond = trim( strip_tags( $http_respond ) );
                                    $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                    if(( $http_code == "200" ) || ( $http_code == "302" ))
                                    {
                                        $res[$website] = 'http://'.$parse['host'];
                                        $allValidEmails[] = $res;
                                    }else{
                                       $res[$website] = '';
                                    }
                                }
                                
                            }
                        }
                    }
                }
            $allValidEmails = array_unique($allValidEmails, SORT_REGULAR);
            $allInvalidEmails = array_unique($allInvalidEmails, SORT_REGULAR);
            $allUnknownEmails = array_unique($allUnknownEmails, SORT_REGULAR);
            $allValidEmails = array_merge($headerValuesSort,$allValidEmails);

            $allInvalidEmails = array_merge($headerValuesSort,$allInvalidEmails); 

            $allUnknownEmails = array_merge($headerValuesSort,$allUnknownEmails); 
                $fpvalid = fopen($name.'-valid-web.csv', 'w');
                $fpinvalid = fopen($name.'-invalid-web.csv', 'w');
                $fpunknown = fopen($name.'-unknown-web.csv', 'w');
               
                foreach($allValidEmails as $result){

                    fputcsv($fpvalid, $result);
                }
                foreach($allInvalidEmails as $res){

                    fputcsv($fpinvalid, $res);
                }
                foreach($allUnknownEmails as $res){
                    fputcsv($fpunknown, $res);
                }
        }
        // Http checked and valid Web checked end

        // Http checked,valid Web checked and profession check start
        elseif($this->is_allmail_checked == 0 && $this->is_profession_checked  == 1 && $this->check_http == 1 && $this->is_webmail_checked == 1 && $this->is_corp_checked == 0 ){
            foreach($this->csvArray as $key => $res){
                    //$res = array_unique($res);
                    array_unshift($res,$key);
                    $count = count($this->allWebEmail);
                        for($i = 0;$i<$count; $i++){
                            if($key == $this->allWebEmail[$i]){
                                $curl = curl_init();
                                curl_setopt_array($curl, array(
                                CURLOPT_URL => 'https://api.neverbounce.com/v4/single/check?key=private_a858390e9dc3175c6e809053edc7349f&email='.$key.' ',
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 0,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'GET',
                            ));
                            $response = curl_exec($curl);
                            curl_close($curl);
                            $validation_check = json_decode($response); 

                            if($validation_check->result == "invalid"){
                                $timeout = 10;
                                $url = $res[$website];
                                $parse = parse_url($url);
                                $url = substr($url, 0, strrpos( $url, '/'));
                                if(!empty($parse)){
                        $professionValue = strtolower($res[$professionkey]);
                       
                        $word_count = str_word_count($professionValue);
                        $all_promails =  array_map('strtolower', $all_promails);
                        $all_promails =  array_map('trim', $all_promails);
                        if($word_count>1){
                        $professionValue = explode(' ', $professionValue);

                         foreach($professionValue as $value){
                            foreach($all_promails as $sinle){
                                  $word_count = str_word_count($sinle);
                                   if($word_count>1){
                                    $sinle = explode(' ', $sinle);
                                      foreach($sinle as $s){
                                        if($s == $value ){
                                        $ch = curl_init($url);
                                            curl_setopt ( $ch, CURLOPT_URL, $url );
                                            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                            curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                            $http_respond = curl_exec($ch);
                                            $http_respond = trim( strip_tags( $http_respond ) );
                                            $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                            if(( $http_code == "200" ) || ( $http_code == "302" ))
                                            {
                                                $res[$website] = 'http://'.$parse['host'];
                                                $allInvalidEmails[] = $res;
                                            }else{
                                               $res[$website] = '';
                                            } 
                                        }
                                      }
                                   }else{
                                     if (in_array($value , $all_promails)) {
                                         
                                        $ch = curl_init($url);
                                        curl_setopt ( $ch, CURLOPT_URL, $url );
                                        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                        curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                        $http_respond = curl_exec($ch);
                                        $http_respond = trim( strip_tags( $http_respond ) );
                                        $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                        if(( $http_code == "200" ) || ( $http_code == "302" ))
                                        {
                                            $res[$website] = 'http://'.$parse['host'];
                                            $allInvalidEmails[]=$res;   
                                        }
                                        else
                                        {
                                            $res[$website] = '';
                                        } 
                                    }
                                   }
                            }
                            
                        }
                       
                        }else{
                             foreach($all_promails as $sinle){
                                  $word_count = str_word_count($sinle);
                                   if($word_count>1){
                                    $sinle = explode(' ', $sinle);
                                      foreach($sinle as $s){
                                        if($s == $professionValue ){
                                            
                                         $ch = curl_init($url);
                                            curl_setopt ( $ch, CURLOPT_URL, $url );
                                            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                            curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                            $http_respond = curl_exec($ch);
                                            $http_respond = trim( strip_tags( $http_respond ) );
                                            $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                            if(( $http_code == "200" ) || ( $http_code == "302" ))
                                            {
                                                $res[$website] = 'http://'.$parse['host'];
                                                $allInvalidEmails[] = $res;
                                            }else{
                                               $res[$website] = '';
                                            } 
                                        }
                                      }
                                   }else{
                                     if (in_array($professionValue , $all_promails)) {
                                         
                                        $ch = curl_init($url);
                                            curl_setopt ( $ch, CURLOPT_URL, $url );
                                            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                            curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                            $http_respond = curl_exec($ch);
                                            $http_respond = trim( strip_tags( $http_respond ) );
                                            $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                            if(( $http_code == "200" ) || ( $http_code == "302" ))
                                            {
                                                $res[$website] = 'http://'.$parse['host'];
                                                $allInvalidEmails[] = $res;
                                            }else{
                                               $res[$website] = '';
                                            } 
                                    }
                                   }
                            }
                           
                        }
                                }
                                
                            }elseif($validation_check->result == "unknown"){
                                $timeout = 10;
                                $url = $res[$website];
                                $parse = parse_url($url);
                                $url = substr($url, 0, strrpos( $url, '/'));
                                if(!empty($parse)){
                                    $professionValue = strtolower($res[$professionkey]);
                       
                        $word_count = str_word_count($professionValue);
                        $all_promails =  array_map('strtolower', $all_promails);
                        $all_promails =  array_map('trim', $all_promails);
                        if($word_count>1){
                        $professionValue = explode(' ', $professionValue);

                         foreach($professionValue as $value){
                            foreach($all_promails as $sinle){
                                  $word_count = str_word_count($sinle);
                                   if($word_count>1){
                                    $sinle = explode(' ', $sinle);
                                      foreach($sinle as $s){
                                        if($s == $value ){
                                        $ch = curl_init($url);
                                            curl_setopt ( $ch, CURLOPT_URL, $url );
                                            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                            curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                            $http_respond = curl_exec($ch);
                                            $http_respond = trim( strip_tags( $http_respond ) );
                                            $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                            if(( $http_code == "200" ) || ( $http_code == "302" ))
                                            {
                                                $res[$website] = 'http://'.$parse['host'];
                                                $allInvalidEmails[] = $res;
                                            }else{
                                               $res[$website] = '';
                                            } 
                                        }
                                      }
                                   }else{
                                     if (in_array($value , $all_promails)) {
                                         
                                        $ch = curl_init($url);
                                        curl_setopt ( $ch, CURLOPT_URL, $url );
                                        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                        curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                        $http_respond = curl_exec($ch);
                                        $http_respond = trim( strip_tags( $http_respond ) );
                                        $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                        if(( $http_code == "200" ) || ( $http_code == "302" ))
                                        {
                                            $res[$website] = 'http://'.$parse['host'];
                                            $webmail[]=$res;   
                                        }
                                        else
                                        {
                                            $res[$website] = '';
                                        } 
                                    }
                                   }
                            }
                            
                        }
                       
                        }else{
                             foreach($all_promails as $sinle){
                                  $word_count = str_word_count($sinle);
                                   if($word_count>1){
                                    $sinle = explode(' ', $sinle);
                                      foreach($sinle as $s){
                                        if($s == $professionValue ){
                                            
                                         $ch = curl_init($url);
                                            curl_setopt ( $ch, CURLOPT_URL, $url );
                                            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                            curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                            $http_respond = curl_exec($ch);
                                            $http_respond = trim( strip_tags( $http_respond ) );
                                            $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                            if(( $http_code == "200" ) || ( $http_code == "302" ))
                                            {
                                                $res[$website] = 'http://'.$parse['host'];
                                                $allInvalidEmails[] = $res;
                                            }else{
                                               $res[$website] = '';
                                            } 
                                        }
                                      }
                                   }else{
                                     if (in_array($professionValue , $all_promails)) {
                                         
                                        $ch = curl_init($url);
                                            curl_setopt ( $ch, CURLOPT_URL, $url );
                                            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                            curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                            $http_respond = curl_exec($ch);
                                            $http_respond = trim( strip_tags( $http_respond ) );
                                            $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                            if(( $http_code == "200" ) || ( $http_code == "302" ))
                                            {
                                                $res[$website] = 'http://'.$parse['host'];
                                                $allInvalidEmails[] = $res;
                                            }else{
                                               $res[$website] = '';
                                            } 
                                    }
                                   }
                            }
                           
                        }
                                }
                                
                            }else{
                             
                                $timeout = 10;
                                $url = $res[$website];
                                $parse = parse_url($url);
                                $url = substr($url, 0, strrpos( $url, '/'));
                                if(!empty($parse['host'])){
                                    $professionValue = strtolower($res[$professionkey]);
                         
                        $word_count = str_word_count($professionValue);
                        $all_promails =  array_map('strtolower', $all_promails);
                        $all_promails =  array_map('trim', $all_promails);
                        if($word_count>1){
                        $professionValue = explode(' ', $professionValue);

                         foreach($professionValue as $value){
                            foreach($all_promails as $sinle){
                                  $word_count = str_word_count($sinle);
                                   if($word_count>1){
                                    $sinle = explode(' ', $sinle);
                                      foreach($sinle as $s){
                                        if($s == $value ){
                                        $ch = curl_init($url);
                                            curl_setopt ( $ch, CURLOPT_URL, $url );
                                            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                            curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                            $http_respond = curl_exec($ch);
                                            $http_respond = trim( strip_tags( $http_respond ) );
                                            $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                            if(( $http_code == "200" ) || ( $http_code == "302" ))
                                            {
                                                $res[$website] = 'http://'.$parse['host'];
                                                $allValidEmails[] = $res;
                                            }else{
                                               $res[$website] = '';
                                            } 
                                        }
                                      }
                                   }else{
                                     if (in_array($value , $all_promails)) {
                                         
                                        $ch = curl_init($url);
                                        curl_setopt ( $ch, CURLOPT_URL, $url );
                                        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                        curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                        $http_respond = curl_exec($ch);
                                        $http_respond = trim( strip_tags( $http_respond ) );
                                        $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                        if(( $http_code == "200" ) || ( $http_code == "302" ))
                                        {
                                            $res[$website] = 'http://'.$parse['host'];
                                            $allValidEmails[]=$res;   
                                        }
                                        else
                                        {
                                            $res[$website] = '';
                                        } 
                                    }
                                   }
                            }
                            
                        }
                       
                        }else{
                             foreach($all_promails as $sinle){
                                  $word_count = str_word_count($sinle);
                                   if($word_count>1){
                                    $sinle = explode(' ', $sinle);
                                      foreach($sinle as $s){
                                        if($s == $professionValue ){ 
                                            
                                            
                                         $ch = curl_init($url);
                                            curl_setopt ( $ch, CURLOPT_URL, $url );
                                            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                            curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                            $http_respond = curl_exec($ch);
                                            $http_respond = trim( strip_tags( $http_respond ) );
                                            $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                            if(( $http_code == "200" ) || ( $http_code == "302" ))
                                            {
                                            
                                                $res[$website] = 'http://'.$parse['host'];
                                                $allValidEmails[] = $res;
                                            }else{

                                               $res[$website] = '';
                                            } 
                                        }
                                      }
                                   }else{
                                     if (in_array($professionValue , $all_promails)) {
                                         
                                         
                                        $ch = curl_init($url);
                                            curl_setopt ( $ch, CURLOPT_URL, $url );
                                            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                            curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                            $http_respond = curl_exec($ch);
                                            $http_respond = trim( strip_tags( $http_respond ) );
                                            $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                            if(( $http_code == "200" ) || ( $http_code == "302" ))
                                            {
                                                $res[$website] = 'http://'.$parse['host'];
                                                $allValidEmails[] = $res;
                                            }else{
                                               $res[$website] = '';
                                            } 
                                    }
                                   }
                            }
                           
                        }
                                }
                                
                            }
                        }
                    }
                }

            $allValidEmails = array_unique($allValidEmails, SORT_REGULAR);
            $allInvalidEmails = array_unique($allInvalidEmails, SORT_REGULAR);
            $allUnknownEmails = array_unique($allUnknownEmails, SORT_REGULAR);
            $allValidEmails = array_merge($headerValuesSort,$allValidEmails);

            $allInvalidEmails = array_merge($headerValuesSort,$allInvalidEmails); 

            $allUnknownEmails = array_merge($headerValuesSort,$allUnknownEmails); 
                $fpvalid = fopen($name.'-valid-web.csv', 'w');
                $fpinvalid = fopen($name.'-invalid-web.csv', 'w');
                $fpunknown = fopen($name.'-unknown-web.csv', 'w');
               
                foreach($allValidEmails as $result){

                    fputcsv($fpvalid, $result);
                }
                foreach($allInvalidEmails as $res){

                    fputcsv($fpinvalid, $res);
                }
                foreach($allUnknownEmails as $res){
                    fputcsv($fpunknown, $res);
                }
        }
        // Http checked,valid Web checked and profession check end

        // Http checked,valid Corp checked and profession check start
        elseif($this->is_allmail_checked == 0 && $this->is_profession_checked  == 1 && $this->check_http == 1 && $this->is_webmail_checked == 0 && $this->is_corp_checked == 1 ){
            foreach($this->csvArray as $key => $res){
                    //$res = array_unique($res);
                    array_unshift($res,$key);
                    $count = count($this->allCorpEmail);
                    for($i = 0;$i<$count; $i++){
                    if($key == $this->allCorpEmail[$i]){
                        $curl = curl_init();
                            curl_setopt_array($curl, array(
                                CURLOPT_URL => 'https://api.neverbounce.com/v4/single/check?key=private_a858390e9dc3175c6e809053edc7349f&email='.$key.' ',
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 0,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'GET',
                            ));
                            $response = curl_exec($curl);
                            curl_close($curl);
                            $validation_check = json_decode($response); 
                            if($validation_check->result == "invalid"){
                                $timeout = 10;
                                $url = $res[$website];
                                $parse = parse_url($url);
                                $url = substr($url, 0, strrpos( $url, '/'));
                                if(!empty($parse)){
                                    $professionValue = strtolower($res[$professionkey]);

                                    $word_count = str_word_count($professionValue);
                       
                                    if($word_count>1){
                                    $professionValue = explode(' ', $professionValue);
                                    foreach($professionValue as $value){
                                        if(in_array($value , $all_promails)){
                                            $ch = curl_init($url);
                                            curl_setopt ( $ch, CURLOPT_URL, $url );
                                            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                            curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                            $http_respond = curl_exec($ch);
                                            $http_respond = trim( strip_tags( $http_respond ) );
                                            $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                            if(( $http_code == "200" ) || ( $http_code == "302" ))
                                            {
                                                $res[$website] = 'http://'.$parse['host'];
                                                $allInvalidEmails[] = $res;
                                            }
                                            else
                                            {
                                                $res[$website] = '';
                                            }
                                        }
                                    }
                                }else{
                                    if(in_array($professionValue , $all_promails)){
                                            $ch = curl_init($url);
                                            curl_setopt ( $ch, CURLOPT_URL, $url );
                                            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                            curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                            $http_respond = curl_exec($ch);
                                            $http_respond = trim( strip_tags( $http_respond ) );
                                            $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                            if(( $http_code == "200" ) || ( $http_code == "302" ))
                                            {
                                                $res[$website] = 'http://'.$parse['host'];
                                                $allUnknownEmails[] = $res;
                                            }
                                            else
                                            {
                                                $res[$website] = '';
                                            }
                                        }
                                }
                                }
                                
                            }elseif($validation_check->result == "unknown"){
                                $timeout = 10;
                                $url = $res[$website];
                                $parse = parse_url($url);
                                $url = substr($url, 0, strrpos( $url, '/'));
                                if(!empty($parse)){
                                    $professionValue = strtolower($res[$professionkey]);
                                    $word_count = str_word_count($professionValue);
                       
                                    if($word_count>1){
                                    $professionValue = explode(' ', $professionValue);
                                    foreach($professionValue as $value){
                                        if(in_array($value , $all_promails)){
                                            $ch = curl_init($url);
                                            curl_setopt ( $ch, CURLOPT_URL, $url );
                                            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                            curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                            $http_respond = curl_exec($ch);
                                            $http_respond = trim( strip_tags( $http_respond ) );
                                            $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                            if(($http_code == "200" ) || ($http_code == "302"))
                                            {
                                                $res[$website] = 'http://'.$parse['host'];
                                                $allUnknownEmails[] = $res;
                                            }else{
                                               $res[$website] = '';
                                            }
                                        }
                                    }
                                }else{
                                    if(in_array($professionValue , $all_promails)){
                                            $ch = curl_init($url);
                                            curl_setopt ( $ch, CURLOPT_URL, $url );
                                            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                            curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                            $http_respond = curl_exec($ch);
                                            $http_respond = trim( strip_tags( $http_respond ) );
                                            $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                            if(($http_code == "200" ) || ($http_code == "302"))
                                            {
                                                $res[$website] = 'http://'.$parse['host'];
                                                $allUnknownEmails[] = $res;
                                            }else{
                                               $res[$website] = '';
                                            }
                                        }
                                }
                                }
                                
                            }else{
                                $timeout = 10;
                                $url = $res[$website];
                                $parse = parse_url($url);
                                $url = substr($url, 0, strrpos( $url, '/'));
                                if(!empty($parse)){
                                    $professionValue = strtolower($res[$professionkey]);
                                    $word_count = str_word_count($professionValue);
                       
                                    if($word_count>1){
                                    $professionValue = explode(' ', $professionValue);
                                    foreach($professionValue as $value){
                                        if(in_array($value , $all_promails)){
                                            $ch = curl_init($url);
                                            curl_setopt ( $ch, CURLOPT_URL, $url );
                                            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                            curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                            $http_respond = curl_exec($ch);
                                            $http_respond = trim( strip_tags( $http_respond ) );
                                            $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                            if(( $http_code == "200" ) || ( $http_code == "302" ))
                                            {
                                                $res[$website] = 'http://'.$parse['host'];
                                                $allValidEmails[] = $res;
                                            }else{
                                               $res[$website] = '';
                                            }
                                        }
                                    }
                                }else{
                                    if(in_array($professionValue , $all_promails)){
                                            $ch = curl_init($url);
                                            curl_setopt ( $ch, CURLOPT_URL, $url );
                                            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                            curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                            $http_respond = curl_exec($ch);
                                            $http_respond = trim( strip_tags( $http_respond ) );
                                            $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                            if(( $http_code == "200" ) || ( $http_code == "302" ))
                                            {
                                                $res[$website] = 'http://'.$parse['host'];
                                                $allValidEmails[] = $res;
                                            }else{
                                               $res[$website] = '';
                                            }
                                        }
                                }
                                }
                                
                            }
                    }
                }
            }
            $allValidEmails = array_unique($allValidEmails, SORT_REGULAR);
                $allInvalidEmails = array_unique($allInvalidEmails, SORT_REGULAR);
                $allUnknownEmails = array_unique($allUnknownEmails, SORT_REGULAR);
            $allValidEmails = array_merge($headerValuesSort,$allValidEmails);

            $allInvalidEmails = array_merge($headerValuesSort,$allInvalidEmails); 

            $allUnknownEmails = array_merge($headerValuesSort,$allUnknownEmails); 
                $fpvalid = fopen($name.'-valid-corp.csv', 'w');
                $fpinvalid = fopen($name.'-invalid-corp.csv', 'w');
                $fpunknown = fopen($name.'-unknown-corp.csv', 'w');
               
                foreach($allValidEmails as $result){

                    fputcsv($fpvalid, $result);
                }
                foreach($allInvalidEmails as $res){

                    fputcsv($fpinvalid, $res);
                }
                foreach($allUnknownEmails as $res){
                    fputcsv($fpunknown, $res);
                }

        }
        // Http checked,valid corp checked and profession check end

        // Valid corp checked and profession check start
        elseif($this->is_allmail_checked == 0 && $this->is_profession_checked  == 1 && $this->check_http == 0 && $this->is_webmail_checked == 0 && $this->is_corp_checked == 1 ){
            foreach($this->csvArray as $key => $res){
                //$res = array_unique($res);
                array_unshift($res,$key);
                $count = count($this->allCorpEmail);
                    for($i = 0;$i<$count; $i++){
                        $professionValue = strtolower($res[$professionkey]); 
                        if($key == $this->allCorpEmail[$i]){
                            if(!empty($website)){
                                $parse = parse_url($res[$website]);
                                $res[$website] = $parse['scheme'].'://'.$parse['host'];
                            }
                            $curl = curl_init();
                            curl_setopt_array($curl, array(
                            CURLOPT_URL => 'https://api.neverbounce.com/v4/single/check?key=private_a858390e9dc3175c6e809053edc7349f&email='.$key.' ',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'GET',
                        ));
                        $response = curl_exec($curl);
                        curl_close($curl);
                        $validation_check = json_decode($response);

                        if($validation_check->result == "invalid"){
                           $professionValue = strtolower($res[$professionkey]);
                           $all_promails =  array_map('strtolower', $all_promails);
                           $all_promails =  array_map('trim', $all_promails);
                           $word_count = str_word_count($professionValue);
                        if($word_count>1){
                        $professionValue = explode(' ', $professionValue);
                         foreach($professionValue as $value){
                            foreach($all_promails as $sinle){
                                  $word_count = str_word_count($sinle);
                                   if($word_count>1){
                                    $sinle = explode(' ', $sinle);
                                      foreach($sinle as $s){
                                        if($s == $value ){
                                            $allInvalidEmails[]=$res;
                                        }
                                      }
                                   }else{
                                     if (in_array($value , $all_promails)) {
                                         $allInvalidEmails[]=$res;
                                    }
                                   }
                            }
                            
                        }
                       
                        }else{
                             foreach($all_promails as $sinle){
                                  $word_count = str_word_count($sinle);
                                   if($word_count>1){
                                    $sinle = explode(' ', $sinle);
                                      foreach($sinle as $s){
                                        if($s == $professionValue ){
                                            $allInvalidEmails[]=$res;
                                        }
                                      }
                                   }else{
                                     if (in_array($professionValue , $all_promails)) {
                                         $allInvalidEmails[]=$res;
                                    }
                                   }
                            }
                           
                        }
                                   
                         
                        }elseif($validation_check->result == "unknown"){
                            $professionValue = strtolower($res[$professionkey]);

                       $all_promails =  array_map('strtolower', $all_promails);
                       $all_promails =  array_map('trim', $all_promails);
                        $word_count = str_word_count($professionValue);

                        if($word_count>1){
                        $professionValue = explode(' ', $professionValue);

                         foreach($professionValue as $value){

                            foreach($all_promails as $sinle){
                                  $word_count = str_word_count($sinle);
                                   if($word_count>1){
                                    $sinle = explode(' ', $sinle);
                                      foreach($sinle as $s){
                                        if($s == $value ){
                                            
                                            $allUnknownEmails[]=$res;
                                        }
                                      }
                                   }else{
                                     if (in_array($value , $all_promails)) {
                                        
                                         $allUnknownEmails[]=$res;
                                    }
                                   }
                            }
 
                        }
                       
                        }else{
                             foreach($all_promails as $sinle){
                                  $word_count = str_word_count($sinle);
                                   if($word_count>1){
                                    $sinle = explode(' ', $sinle);
                                      foreach($sinle as $s){
                                        if($s == $professionValue ){
                                            $allUnknownEmails[]=$res;
                                        }
                                      }
                                   }else{
                                     if (in_array($professionValue , $all_promails)) {
                                         $allUnknownEmails[]=$res;
                                    }
                                   }
                            }
                           
                        }
                        }else{
                            $word_count = str_word_count($professionValue);
                        
                            if($word_count>1){

                            $professionValue = strtolower($res[$professionkey]);
                       $all_promails =  array_map('strtolower', $all_promails);
                       $all_promails =  array_map('trim', $all_promails);
                        $word_count = str_word_count($professionValue);

                        if($word_count>1){
                        $professionValue = explode(' ', $professionValue);

                         foreach($professionValue as $value){
                            foreach($all_promails as $sinle){
                                  $word_count = str_word_count($sinle);
                                   if($word_count>1){
                                    $sinle = explode(' ', $sinle);
                                      foreach($sinle as $s){
                                        if($s == $value ){

                                            $allValidEmails[]=$res;
                                        }
                                      }
                                   }else{
                                     if (in_array($value , $all_promails)) {
                                         $allValidEmails[]=$res;
                                    }
                                   }
                            }
                            
                        }
                       
                        }else{
                             foreach($all_promails as $sinle){
                                  $word_count = str_word_count($sinle);
                                   if($word_count>1){
                                    $sinle = explode(' ', $sinle);
                                      foreach($sinle as $s){
                                        if($s == $professionValue ){
                                            $allValidEmails[]=$res;
                                        }
                                      }
                                   }else{
                                     if (in_array($professionValue , $all_promails)) {
                                         $allValidEmails[]=$res;
                                    }
                                   }
                            }
                           
                        }
                         }
                              
                        }
                    }
                }
            }
        
          $allValidEmails = array_unique($allValidEmails, SORT_REGULAR);
          $allInvalidEmails = array_unique($allInvalidEmails, SORT_REGULAR);
          $allUnknownEmails = array_unique($allUnknownEmails, SORT_REGULAR);

            $allValidEmails = array_merge($headerValuesSort,$allValidEmails);

            $allInvalidEmails = array_merge($headerValuesSort,$allInvalidEmails); 

            $allUnknownEmails = array_merge($headerValuesSort,$allUnknownEmails); 
                $fpvalid = fopen($name.'-valid-corp.csv', 'w');
                $fpinvalid = fopen($name.'-invalid-corp.csv', 'w');
                $fpunknown = fopen($name.'-unknown-corp.csv', 'w');
               
                foreach($allValidEmails as $result){

                    fputcsv($fpvalid, $result);
                }
                foreach($allInvalidEmails as $res){

                    fputcsv($fpinvalid, $res);
                }
                foreach($allUnknownEmails as $res){
                    fputcsv($fpunknown, $res);
                } 

        }
        // Valid corp checked and profession check end

        // Valid Web checked and profession check start
        elseif($this->is_allmail_checked == 0 && $this->is_profession_checked  == 1 && $this->check_http == 0 && $this->is_webmail_checked == 1 && $this->is_corp_checked == 0 ){
            foreach($this->csvArray as $key => $res){
                //$res = array_unique($res);
                array_unshift($res,$key);
                $count = count($this->allWebEmail);
                    for($i = 0;$i<$count; $i++){
                        $professionValue = strtolower($res[$professionkey]);

                        if($key == $this->allWebEmail[$i]){
                            if(!empty($website)){
                                $parse = parse_url($res[$website]);
                                $res[$website] = $parse['scheme'].'://'.$parse['host'];
                            }
                            $curl = curl_init();
                            curl_setopt_array($curl, array(
                            CURLOPT_URL => 'https://api.neverbounce.com/v4/single/check?key=private_a858390e9dc3175c6e809053edc7349f&email='.$key.' ',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'GET',
                        ));
                        $response = curl_exec($curl);
                        curl_close($curl);
                        $validation_check = json_decode($response);
                        if($validation_check->result == "invalid"){
                            
                           $professionValue = strtolower($res[$professionkey]);
                       $all_promails =  array_map('strtolower', $all_promails);
                       $all_promails =  array_map('trim', $all_promails);
                        $word_count = str_word_count($professionValue);

                        if($word_count>1){
                        $professionValue = explode(' ', $professionValue);

                         foreach($professionValue as $value){
                            foreach($all_promails as $sinle){
                                  $word_count = str_word_count($sinle);
                                   if($word_count>1){
                                    $sinle = explode(' ', $sinle);
                                      foreach($sinle as $s){
                                        if($s == $value ){

                                            $allInvalidEmails[]=$res;
                                        }
                                      }
                                   }else{
                                     if (in_array($value , $all_promails)) {
                                         $allInvalidEmails[]=$res;
                                    }
                                   }
                            }
                            
                        }
                       
                        }else{
                             foreach($all_promails as $sinle){
                                  $word_count = str_word_count($sinle);
                                   if($word_count>1){
                                    $sinle = explode(' ', $sinle);
                                      foreach($sinle as $s){
                                        if($s == $professionValue ){
                                            $allInvalidEmails[]=$res;
                                        }
                                      }
                                   }else{
                                     if (in_array($professionValue , $all_promails)) {
                                         $allInvalidEmails[]=$res;
                                    }
                                   }
                            }
                           
                        }
                        
                        }elseif($validation_check->result == "unknown"){

                           $professionValue = strtolower($res[$professionkey]);
                       $all_promails =  array_map('strtolower', $all_promails);
                       $all_promails =  array_map('trim', $all_promails);
                        $word_count = str_word_count($professionValue);

                        if($word_count>1){
                        $professionValue = explode(' ', $professionValue);

                         foreach($professionValue as $value){
                            foreach($all_promails as $sinle){
                                  $word_count = str_word_count($sinle);
                                   if($word_count>1){
                                    $sinle = explode(' ', $sinle);
                                      foreach($sinle as $s){
                                        if($s == $value ){

                                            $allUnknownEmails[]=$res;
                                        }
                                      }
                                   }else{
                                     if (in_array($value , $all_promails)) {
                                         $allUnknownEmails[]=$res;
                                    }
                                   }
                            }
                            
                        }
                       
                        }else{
                             foreach($all_promails as $sinle){
                                  $word_count = str_word_count($sinle);
                                   if($word_count>1){
                                    $sinle = explode(' ', $sinle);
                                      foreach($sinle as $s){
                                        if($s == $professionValue ){
                                            $allUnknownEmails[]=$res;
                                        }
                                      }
                                   }else{
                                     if (in_array($professionValue , $all_promails)) {
                                         $allUnknownEmails[]=$res;
                                    }
                                   }
                            }
                           
                        }

                        }else{

                           $professionValue = strtolower($res[$professionkey]);
                       $all_promails =  array_map('strtolower', $all_promails);
                       $all_promails =  array_map('trim', $all_promails);
                        $word_count = str_word_count($professionValue);

                        if($word_count>1){
                        $professionValue = explode(' ', $professionValue);

                         foreach($professionValue as $value){
                            foreach($all_promails as $sinle){
                                  $word_count = str_word_count($sinle);
                                   if($word_count>1){
                                    $sinle = explode(' ', $sinle);
                                      foreach($sinle as $s){
                                        if($s == $value ){

                                            $allValidEmails[]=$res;
                                        }
                                      }
                                   }else{
                                     if (in_array($value , $all_promails)) {
                                         $allValidEmails[]=$res;
                                    }
                                   }
                            }
                            
                        }
                       
                        }else{
                             foreach($all_promails as $sinle){
                                  $word_count = str_word_count($sinle);
                                   if($word_count>1){
                                    $sinle = explode(' ', $sinle);
                                      foreach($sinle as $s){
                                        if($s == $professionValue ){
                                            $allValidEmails[]=$res;
                                        }
                                      }
                                   }else{
                                     if (in_array($professionValue , $all_promails)) {
                                         $allValidEmails[]=$res;
                                    }
                                   }
                            }
                           
                        }

                        }
                    }
                }
            }
            $allValidEmails = array_unique($allValidEmails, SORT_REGULAR);
          $allInvalidEmails = array_unique($allInvalidEmails, SORT_REGULAR);
          $allUnknownEmails = array_unique($allUnknownEmails, SORT_REGULAR);
            $allValidEmails = array_merge($headerValuesSort,$allValidEmails);

            $allInvalidEmails = array_merge($headerValuesSort,$allInvalidEmails); 

            $allUnknownEmails = array_merge($headerValuesSort,$allUnknownEmails); 
                $fpvalid = fopen($name.'-valid-web.csv', 'w');
                $fpinvalid = fopen($name.'-invalid-web.csv', 'w');
                $fpunknown = fopen($name.'-unknown-web.csv', 'w');
               
                foreach($allValidEmails as $result){

                    fputcsv($fpvalid, $result);
                }
                foreach($allInvalidEmails as $res){

                    fputcsv($fpinvalid, $res);
                }
                foreach($allUnknownEmails as $res){
                    fputcsv($fpunknown, $res);
                } 
             

        }
        // Valid Web checked and profession check end

        // Valid Web checked start
        elseif($this->is_allmail_checked == 0 && $this->is_profession_checked  == 0 && $this->check_http == 0 && $this->is_webmail_checked == 1 && $this->is_corp_checked == 0 ){
            foreach($this->csvArray as $key => $res){
                //$res = array_unique($res);
                array_unshift($res,$key);
                $count = count($this->allWebEmail);
                    for($i = 0;$i<$count; $i++){
                        if($key == $this->allWebEmail[$i]){
                            if(!empty($website)){
                                $parse = parse_url($res[$website]);
                                $res[$website] = $parse['scheme'].'://'.$parse['host'];
                            }
                            $curl = curl_init();
                            curl_setopt_array($curl, array(
                            CURLOPT_URL => 'https://api.neverbounce.com/v4/single/check?key=private_a858390e9dc3175c6e809053edc7349f&email='.$key.' ',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'GET',
                        ));
                        $response = curl_exec($curl);
                        curl_close($curl);
                        $validation_check = json_decode($response); 
                        if($validation_check->result == "invalid"){
                            $allInvalidEmails[] = $res;
                        }elseif($validation_check->result == "unknown"){
                            $allUnknownEmails[] = $res;
                        }else{
                            $allValidEmails[] = $res;
                        }
                    }
                }
            }
            $allValidEmails = array_unique($allValidEmails, SORT_REGULAR);
            $allInvalidEmails = array_unique($allInvalidEmails, SORT_REGULAR);
            $allUnknownEmails = array_unique($allUnknownEmails, SORT_REGULAR);
            $allValidEmails = array_merge($headerValuesSort,$allValidEmails);

            $allInvalidEmails = array_merge($headerValuesSort,$allInvalidEmails); 

            $allUnknownEmails = array_merge($headerValuesSort,$allUnknownEmails); 
                $fpvalid = fopen($name.'-valid-web.csv', 'w');
                $fpinvalid = fopen($name.'-invalid-web.csv', 'w');
                $fpunknown = fopen($name.'-unknown-web.csv', 'w');
               
                foreach($allValidEmails as $result){

                    fputcsv($fpvalid, $result);
                }
                foreach($allInvalidEmails as $res){

                    fputcsv($fpinvalid, $res);
                }
                foreach($allUnknownEmails as $res){
                    fputcsv($fpunknown, $res);
                }

        }
        // Valid Web checked end

        // Valid Corp checked start
        elseif($this->is_allmail_checked == 0 && $this->is_profession_checked  == 0 && $this->check_http == 0 && $this->is_webmail_checked == 0 && $this->is_corp_checked == 1 ){
            foreach($this->csvArray as $key => $res){
                //$res = array_unique($res);
                array_unshift($res,$key);
                $count = count($this->allCorpEmail);
                for($i = 0;$i<$count; $i++){
                if($key == $this->allCorpEmail[$i]){
                    if(!empty($website)){
                        $parse = parse_url($res[$website]);
                        $res[$website] = $parse['scheme'].'://'.$parse['host'];
                    }
                    $curl = curl_init();
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => 'https://api.neverbounce.com/v4/single/check?key=private_a858390e9dc3175c6e809053edc7349f&email='.$key.' ',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'GET',
                        ));
                        $response = curl_exec($curl);
                        curl_close($curl);
                        $validation_check = json_decode($response); 
                        if($validation_check->result == "invalid"){
                            $allInvalidEmails[] = $res;
                        }elseif($validation_check->result == "unknown"){
                            $allUnknownEmails[] = $res;
                        }else{
                            $allValidEmails[] = $res;
                        }
                    }
                }
            }
            $allValidEmails = array_unique($allValidEmails, SORT_REGULAR);
            $allInvalidEmails = array_unique($allInvalidEmails, SORT_REGULAR);
            $allUnknownEmails = array_unique($allUnknownEmails, SORT_REGULAR);
            $allValidEmails = array_merge($headerValuesSort,$allValidEmails);

            $allInvalidEmails = array_merge($headerValuesSort,$allInvalidEmails); 

            $allUnknownEmails = array_merge($headerValuesSort,$allUnknownEmails); 
                $fpvalid = fopen($name.'-valid-corp.csv', 'w');
                $fpinvalid = fopen($name.'-invalid-corp.csv', 'w');
                $fpunknown = fopen($name.'-unknown-corp.csv', 'w');
               
                foreach($allValidEmails as $result){

                    fputcsv($fpvalid, $result);
                }
                foreach($allInvalidEmails as $res){

                    fputcsv($fpinvalid, $res);
                }
                foreach($allUnknownEmails as $res){
                    fputcsv($fpunknown, $res);
                }
        }
        // Valid Corp checked end

        // Http only checked start
        elseif($this->check_http == 1 && $this->is_allmail_checked == 0 && $this->is_webmail_checked == 0 && $this->is_corp_checked == 0 && $this->is_profession_checked  == 0){
            foreach($this->csvArray as $key => $res){
                    //$res = array_unique($res);
                    array_unshift($res,$key);
                    $count = count($this->allWebEmail);
                    for($i = 0;$i<$count; $i++){
                        if($key == $this->allWebEmail[$i]){
                            $timeout = 10;
                            $url = $res[$website];
                            $parse = parse_url($url);
                            $url = substr($url, 0, strrpos( $url, '/'));
                            if(!empty($parse)){
                                $ch = curl_init($url);
                                curl_setopt ( $ch, CURLOPT_URL, $url );
                                curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                $http_respond = curl_exec($ch);
                                $http_respond = trim( strip_tags( $http_respond ) );
                                $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                if(( $http_code == "200" ) || ( $http_code == "302" ))
                                {
                                    $res[$website] = 'http://'.$parse['host'];
                                    $webmail[]=$res;
                                }
                                else
                                {
                                    $res[$website] = '';
                                }
                            }
                            
                        }
                    }
                }
                foreach($this->csvArray as $key => $res){
                    //$res = array_unique($res);
                    array_unshift($res,$key);
                    $count = count($this->allCorpEmail);
                    for($i = 0;$i<$count; $i++){
                        if($key == $this->allCorpEmail[$i]){
                            $timeout = 10;
                            $url = $res[$website];
                            $parse = parse_url($url);
                            $url = substr($url, 0, strrpos( $url, '/'));
                            if(!empty($parse)){
                                $ch = curl_init($url);
                                curl_setopt ( $ch, CURLOPT_URL, $url );
                                curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
                                $http_respond = curl_exec($ch);
                                $http_respond = trim( strip_tags( $http_respond ) );
                                $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                if(( $http_code == "200" ) || ( $http_code == "302" ))
                                {
                                    $res[$website] = 'http://'.$parse['host'];
                                    $corpmail[]=$res; 
                                }
                                else
                                {
                                    $res[$website] = '';
                                }
                            }
                            
                        }
                      }   
                }
                $allValidEmails = array_unique($webmail, SORT_REGULAR);
                $allInvalidEmails = array_unique($corpmail, SORT_REGULAR);
                $allValidEmails = array_merge($headerValuesSort,$allValidEmails);
                $allInvalidEmails = array_merge($headerValuesSort,$allInvalidEmails); 
          
                $fpvalid = fopen($name.'-allwebemail.csv', 'w');
                $fpinvalid = fopen($name.'-allcorpemail.csv', 'w');
                foreach($allValidEmails as $result){
                    fputcsv($fpvalid, $result);
                }
                foreach($allInvalidEmails as $res){
                    fputcsv($fpinvalid, $res);
                }
        }
        // Http only checked end

        // Profession checked start
        else if($this->is_profession_checked  == 1 && $this->check_http == 0 && $this->is_allmail_checked == 0 && $this->is_webmail_checked == 0 && $this->is_corp_checked == 0){ 
            foreach($this->csvArray as $key => $res){
                
                //$res = array_unique($res);
                array_unshift($res,$key);
                $count = count($this->allCorpEmail);
                for($i = 0;$i<$count; $i++){
                    if($key == $this->allCorpEmail[$i]){
                        if(!empty($website)){
                            $parse = parse_url($res[$website]);
                            $res[$website] = $parse['scheme'].'://'.$parse['host'];
                        }
                        $professionValue = strtolower($res[$professionkey]);
                        $word_count = str_word_count($professionValue);
                        $all_promails =  array_map('strtolower', $all_promails);
                        $all_promails =  array_map('trim', $all_promails);
                        if($word_count>1){
                        $professionValue = explode(' ', $professionValue);
                          
                         foreach($professionValue as $value){
                            foreach($all_promails as $sinle){

                                  $word_count = str_word_count($sinle);
                                   if($word_count>1){
                                    $sinle = explode(' ', $sinle);
                                      foreach($sinle as $s){
                                        if($s == $value ){
                                            
                                            $this->CorpEmail[$i]=$res;
                                        }
                                      }
                                   }else{
                                     if (in_array($value , $all_promails)) {
                                        
                                         $this->CorpEmail[$i]=$res;
                                    }
                                   }
                            }
                            
                        }
                        }else{
                             foreach($all_promails as $sinle){
                                  $word_count = str_word_count($sinle);
                                   if($word_count>1){
                                    $sinle = explode(' ', $sinle);
                                      foreach($sinle as $s){
                                        if($s == $professionValue ){
                                            
                                            $this->CorpEmail[$i]=$res;
                                        }
                                      }
                                   }else{
                                     if (in_array($professionValue , $all_promails)) {
                                         $this->CorpEmail[$i]=$res;
                                    }
                                   }
                            }
                           
                        }

                // $word_count = str_word_count($professionValue);
               
                //             if($word_count>1){
                //             $professionValue = explode(' ', $professionValue);
                //             foreach($professionValue as $value){
                //         if (in_array($value, array_map('strtolower', $all_promails))){
                //             $this->CorpEmail[$i]=$res;
                //         }
                //      }
                //    }else{
                //         if (in_array($professionValue, array_map('strtolower', $all_promails))){
                //             $this->CorpEmail[$i]=$res;
                //         }
                //     }
                }
                }
             
            }
           
            foreach($this->csvArray as $key => $res){
                //$res = array_unique($res);
                array_unshift($res,$key);
                $count = count($this->allWebEmail);

                for($i = 0;$i<$count; $i++){
                    if($key == $this->allWebEmail[$i]){
                        if(!empty($website)){
                            $parse = parse_url($res[$website]);
                            $res[$website] = $parse['scheme'].'://'.$parse['host'];
                        }
                       $professionValue = strtolower($res[$professionkey]);
                       $all_promails =  array_map('strtolower', $all_promails);
                       $all_promails =  array_map('trim', $all_promails);
                        $word_count = str_word_count($professionValue);

                        if($word_count>1){
                        $professionValue = explode(' ', $professionValue);

                         foreach($professionValue as $value){
                            foreach($all_promails as $sinle){
                                  $word_count = str_word_count($sinle);
                                   if($word_count>1){
                                    $sinle = explode(' ', $sinle);
                                      foreach($sinle as $s){
                                        if($s == $value ){

                                           $this->WebEmail[$i]=$res;
                                        }
                                      }
                                   }else{
                                     if (in_array($value , $all_promails)) {
                                         $this->WebEmail[$i]=$res;
                                    }
                                   }
                            }
                            
                        }
                       
                        }else{
                             foreach($all_promails as $sinle){
                                  $word_count = str_word_count($sinle);
                                   if($word_count>1){
                                    $sinle = explode(' ', $sinle);
                                      foreach($sinle as $s){
                                        if($s == $professionValue ){
                                            $this->WebEmail[$i]=$res;
                                        }
                                      }
                                   }else{
                                     if (in_array($professionValue , $all_promails)) {
                                         $this->WebEmail[$i]=$res;
                                    }
                                   }
                            }
                           
                        }
                }
                }
            }

            $this->WebEmail = array_unique($this->WebEmail, SORT_REGULAR);
            $this->CorpEmail = array_unique($this->CorpEmail, SORT_REGULAR);
            $allValidEmails = array_merge($headerValuesSort,$this->WebEmail);
            $allInvalidEmails = array_merge($headerValuesSort,$this->CorpEmail); 
                $fpvalid = fopen($name.'-allwebemail.csv', 'w');
                $fpinvalid = fopen($name.'-allcorpemail.csv', 'w');
                foreach($allValidEmails as $result){
                    fputcsv($fpvalid, $result);
                }
                foreach($allInvalidEmails as $res){
                    fputcsv($fpinvalid, $res);
                }
        }
        // Profession checked end

        //Only validation start      
        else{
            foreach($this->csvArray as $key => $res){
                //$res = array_unique($res);
                array_unshift($res,$key);
                if(!empty($website)){
                    $parse = parse_url($res[$website]);
                    $res[$website] = $parse['scheme'].'://'.$parse['host'];
                }
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api.neverbounce.com/v4/single/check?key=private_a858390e9dc3175c6e809053edc7349f&email='.$key.' ',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                $validation_check = json_decode($response); 
                if($validation_check->result == "invalid"){
                    $allInvalidEmails[] = $res;
                }elseif($validation_check->result == "unknown"){
                    $allUnknownEmails[] = $res;
                }else{
                    $allValidEmails[] = $res;
                }
            }
                $allValidEmails = array_unique($allValidEmails, SORT_REGULAR);
                $allInvalidEmails = array_unique($allInvalidEmails, SORT_REGULAR);
                $allUnknownEmails = array_unique($allUnknownEmails, SORT_REGULAR);
                $allValidEmails = array_merge($headerValuesSort,$allValidEmails);
    
                $allInvalidEmails = array_merge($headerValuesSort,$allInvalidEmails); 

                $allUnknownEmails = array_merge($headerValuesSort,$allUnknownEmails); 
               
                $fpvalid = fopen($name.'-valid.csv', 'w');
                $fpinvalid = fopen($name.'-invalid.csv', 'w');
                $fpunknown = fopen($name.'-unknown.csv', 'w');
               
                foreach($allValidEmails as $result){

                    fputcsv($fpvalid, $result);
                }
                foreach($allInvalidEmails as $res){

                    fputcsv($fpinvalid, $res);
                }
                foreach($allUnknownEmails as $res){
                    fputcsv($fpunknown, $res);
                }
        }
        //Only validation end 
        $this->uploaded = false;
        
        $this->emit('imported');
        session()->flash('message', 'File Validator Sucessfully!');
        $this->redirectRoute('leadvalidator.index');
    
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