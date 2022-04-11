<?php

namespace App\Http\Livewire;
use App\Actions\DeployPage;
use App\Models\PremiumPages;
use App\Models\Connection;
use App\Models\PremiumTemplates;
use App\Tools;
use Livewire\WithFileUploads;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class PremiumPagesForm extends Component
{
    use WithFileUploads;
    public $file = null;
    public $file1 = null;
    public $file2 = null;
    public $file3 = null;
    public array $data = [];
    public PremiumPages $premiumpage;

    public function getConnectionsProperty()
    {
        return Connection::byTool(Tools::current())->get();
    }

    public function getPremiumTemplatesProperty()
    {
        return PremiumTemplates::all();
    }

    public function mount(PremiumPages $premiumpage): void
    {
        $this->premiumpage = $premiumpage;

    }

    public function rules(): array
    {

        return [
            'premiumpage.connection_id' => ['required', 'exists:connections,id'],
            'premiumpage.premium_templates_id' => ['required', 'exists:premium_templates,id'],
            'premiumpage.slug' => ['required', 'string'],
            'premiumpage.product_name' => ['nullable', 'string'],
            'premiumpage.affiliate_link' => ['', 'string'],
            'premiumpage.header_text' => ['nullable', 'string'],
            'premiumpage.file' => ['required', 'image'],
            'premiumpage.hero_text1' => ['nullable', 'string'],
            'premiumpage.hero_text2' => ['nullable', 'string'],
            'premiumpage.button_color' => ['nullable'],
            'premiumpage.product_header' => ['nullable', 'string'],
            'premiumpage.file1' => ['required', 'image'],
            'premiumpage.product_image1_link' => ['required', 'string'],
            'premiumpage.file2' => ['required', 'image'],
            'premiumpage.product_image2_link' => ['required', 'string'],
            'premiumpage.file3' => ['required', 'image'],
            'premiumpage.product_image3_link' => ['required', 'string'],

        ];
    }   
    public function updatedFile(): void
    {
        if(!empty($this->file)){
            $filename = 'profile' . time() .'.'. $this->file->getClientOriginalExtension();

            $this->file->storeAs('/public', $filename);
            $this->data['profile'] = $filename;
        }
        
    }
    public function updatedFile1(): void
    {
       if(!empty($this->file1)){
            $filename = 'product_img1' . time() .'.'. $this->file1->getClientOriginalExtension();
            $this->file1->storeAs('/public', $filename);
            $this->data['product_img1'] = $filename;
        }
    }
    public function updatedFile2(): void
    {
       if(!empty($this->file2)){
            $filename = 'product_img2' . time() .'.'. $this->file2->getClientOriginalExtension();
            

            $this->file2->storeAs('/public', $filename);
             
            $this->data['product_img2'] = $filename;
        }
    }
    public function updatedFile3(): void
    {
       if(!empty($this->file3)){
            $filename = 'product_img3' . time() .'.'. $this->file3->getClientOriginalExtension();

            $this->file3->storeAs('/public', $filename);

            $this->data['product_img3'] = $filename;
        }
    }
    public function submit(DeployPage $deployer)
    {
        if($this->premiumpage->id)
        {
            $premiumpages = PremiumPages::find($this->premiumpage->id);
            if(empty($this->data['profile'])){
                $this->data['profile']= $premiumpages['hero_image'];
            }
            if(empty($this->data['product_img1'])){
                $this->data['product_img1']= $premiumpages['product_image1'];
            }
            if(empty($this->data['product_img2'])){
                $this->data['product_img2']= $premiumpages['product_image2'];
            }
            if(empty($this->data['product_img3'])){
                $this->data['product_img3']= $premiumpages['product_image3'];
            }

            
            $premiumpages->update([
                'connection_id' => $this->premiumpage->connection_id,
                'premium_templates_id' => $this->premiumpage->premium_templates_id,
                'slug' => $this->premiumpage->slug,
                'product_name' => $this->premiumpage->product_name,
                'affiliate_link' => $this->premiumpage->affiliate_link,
                'header_text' => $this->premiumpage->header_text,
                'hero_image' => $this->data['profile'],
                'hero_text1' => $this->premiumpage->hero_text1,
                'hero_text2' => $this->premiumpage->hero_text2,
                'button' => $this->premiumpage->button_color,
                'product_header' => $this->premiumpage->product_header,
                'product_image1' => $this->data['product_img1'],
                'product_image1_link' => $this->premiumpage->product_image1_link,
                'product_image2' => $this->data['product_img2'],
                'product_image2_link' => $this->premiumpage->product_image2_link,
                'product_image3' => $this->data['product_img3'],
                'product_image3_link' => $this->premiumpage->product_image3_link,
            ]);
            $premiumpagesdata = PremiumPages::find($this->premiumpage->id);
            $deployer->deployPremium($premiumpagesdata);

            
        }
        else
        {
            if(empty($this->data['product_img3'])){
                $prod_img3 = '';
                
            }
            else{
                $prod_img3 = $this->data['product_img3'];
            }

            if(empty($this->premiumpage->product_image3_link)){
                $prod_img3_link = '';
            }
            else{
                $prod_img3_link = $this->premiumpage->product_image3_link;
            }
            if(empty($this->data['product_img2'])){
                $prod_img2 = '';
                
            }
            else{
                $prod_img2 = $this->data['product_img2'];
            }

            if(empty($this->premiumpage->product_image2_link)){
                $prod_img2_link = '';
            }
            else{
                $prod_img2_link = $this->premiumpage->product_image2_link;
            }

            if(empty($this->premiumpage->header_text)){
                $header_text = '';
            }
            else{
                $header_text = $this->premiumpage->header_text;
            }

            if(empty($this->premiumpage->affiliate_link)){
                $affiliate_link = '';
            }
            else{
                $affiliate_link = $this->premiumpage->affiliate_link;
            }
            


            $premiumpages_id = PremiumPages::create([
                'connection_id' => $this->premiumpage->connection_id,
                'premium_templates_id' => $this->premiumpage->premium_templates_id,
                'slug' => $this->premiumpage->slug,
                'product_name' => $this->premiumpage->product_name,
                'affiliate_link' => $affiliate_link,
                'header_text' => $header_text,
                'hero_image' => $this->data['profile'],
                'hero_text1' => $this->premiumpage->hero_text1,
                'hero_text2' => $this->premiumpage->hero_text2,
                'button' => $this->premiumpage->button_color,
                'product_header' => $this->premiumpage->product_header,
                'product_image1' => $this->data['product_img1'],
                'product_image1_link' => $this->premiumpage->product_image1_link,
                'product_image2' =>$prod_img2,
                'product_image2_link' => $prod_img2_link,
                'product_image3' => $prod_img3,
                'product_image3_link' => $prod_img3_link,
            ])->id;
            
            $premiumpagesdata = PremiumPages::find($premiumpages_id);
            $deployer->deployPremium($premiumpagesdata);
        }

        session()->flash('copyToClipboard', [
            'text' => 'Page link copied to clipboard',
            'value' => $this->premiumpage->full_url
        ]);
        $this->redirectRoute('premiumpages.index');

    }
}
