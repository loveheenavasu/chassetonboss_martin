<?php
namespace App\Http\Livewire;
use App\Tools;
use Laravel\Jetstream\Http\Livewire\NavigationDropdown;
class Navigation extends NavigationDropdown
{
    public string $tool;
    public function links(): array
    {
        $this->tool = Tools::current();
        switch ($this->tool) {
            case Tools::REFERER:
                $links = [
                    [
                        'href' => route('connections.index'),
                        'label' => __('Connections'),
                        'active' => request()->routeIs('connections.*')
                    ],
                    [
                        'href' => route('templates.index'),
                        'label' => __('Templates'),
                        'active' => request()->routeIs('templates.*')
                    ],
                    [
                        'href' => route('pages.index'),
                        'label' => __('Pages'),
                        'active' => request()->routeIs('pages.*')
                    ],

                    [
                        'href' => route('premiumtemplates.index'),
                        'label' => __('Premium Templates'),
                        'active' => request()->routeIs('premiumtemplates.*')
                    ],
                    [
                        'href' => route('premiumpages.index'),
                        'label' => __('Premium Pages'),
                        'active' => request()->routeIs('premiumpages.*')
                    ],
                ];
                break;
            case Tools::SYNDICATION:
                $links = [
                    [
                        'href' => route('connections.index'),
                        'label' => __('Connections'),
                        'active' => request()->routeIs('connections.*')
                    ],
                    [
                        'href' => route('contents.index'),
                        'label' => __('Content'),
                        'active' => request()->routeIs('contents.*')
                    ],
                    [
                        'href' => route('syndications.index'),
                        'label' => __('Syndication'),
                        'active' => request()->routeIs('syndications.*')
                    ],
                ];
                break;
            case Tools::DRIP_FEED:
                $links = [
                    [
                        'href' => route('connections.index'),
                        'label' => __('Connections'),
                        'active' => request()->routeIs('connections.*')
                    ],
                    [
                        'href' => route('listings.index'),
                        'label' => __('Lists'),
                        'active' => request()->routeIs('listings.*')
                    ],
                    [
                        'href' => route('rules.index'),
                        'label' => __('Rules'),
                        'active' => request()->routeIs('rules.*')
                    ],
                    [
                        'href' => route('invalidemail.index'),
                        'label' => __('Invalid Email'),
                        'active' => request()->routeIs('invalidemail.*')
                    ],
                    [
                        'href' => route('emaillogs.index'),
                        'label' => __('Email Logs'),
                        'active' => request()->routeIs('emaillogs.*')
                    ],
                    [
                        'href' => route('cron.index'),
                        'label' => __('Cron'),
                        'active' => request()->routeIs('cron.*')
                    ],
                    [
                        'href' => route('mauticlogs.index'),
                        'label' => __('Mautic Logs'),
                        'active' => request()->routeIs('mauticlogs.*')
                    ],
                    [
                        'href' => route('logindetails.index'),
                        'label' => __('Login&List Logs'),
                        'active' => request()->routeIs('logindetails.*')
                    ]
                ];
                break;
            case Tools::CUTTER:
                $links = [
                    [
                        'href' => route('cutter.index'),
                        'label' => __('Files Cutter'),
                        'active' => request()->routeIs('cutter.*')
                    ]
                ];
                break;
            case Tools::LEAD_VALIDATOR:
                $links = [
                    [
                        'href' => route('leadvalidator.index'),
                        'label' => __('Lead Validator'),
                        'active' => request()->routeIs('leadvalidator.*')
                    ],
                    [
                        'href' => route('keyword.index'),
                        'label' => __('Key Words'),
                        'active' => request()->routeIs('keyword.*')
                    ],
                    [
                        'href' => route('profession.index'),
                        'label' => __('Professions'),
                        'active' => request()->routeIs('profession.*')
                    ],
                    [
                        'href' => route('blacklist.index'),
                        'label' => __('BlackList'),
                        'active' => request()->routeIs('blacklist.*')
                    ]
                ];
                break;
            case Tools::EVENT_CALENDER:
                $links = [
                   
                    [
                        'href' => route('gmailconnection.index'),
                        'label' => __('Gmail Connection'),
                        'active' => request()->routeIs('gmailconnection.*')
                    ],
                    [
                        'href' => route('projectlist.index'),
                        'label' => __('Project List'),
                        'active' => request()->routeIs('projectlist.*')
                    ],
                    [
                        'href' => route('eventlistings.index'),
                        'label' => __('Event Lists'),
                        'active' => request()->routeIs('eventlistings.*')
                    ],
                    [
                        'href' => route('groups.index'),
                        'label' => __('Groups'),
                        'active' => request()->routeIs('groups.*')
                    ],
                    [
                        'href' => route('eventtemplate.index'),
                        'label' => __('Content Template'),
                        'active' => request()->routeIs('eventtemplate.*')
                    ],
                    [
                        'href' => route('eventcalender.index'),
                        'label' => __('Events'),
                        'active' => request()->routeIs('eventcalender.*')
                    ],
                    [
                        'href' => route('eventinvalidemail.index'),
                        'label' => __('Invalid Email'),
                        'active' => request()->routeIs('eventinvalidemail.*')
                    ],
                    [
                        'href' => route('eventemaillogs.index'),
                        'label' => __('Email Logs'),
                        'active' => request()->routeIs('eventemaillogs.*')
                    ],
                    [
                        'href' => route('eventplaceholders.index'),
                        'label' => __('Event Placeholders'),
                        'active' => request()->routeIs('eventplaceholders.*')
                    ],
                    

                ];

            case Tools::LANDING_PAGE:
                $links = [
                    [
                        'href' => route('connections.index'),
                        'label' => __('Connections'),
                        'active' => request()->routeIs('connections.*')
                    ],
                    [
                        'href' => route('landingtemplates.index'),
                        'label' => __('Landing Templates'),
                        'active' => request()->routeIs('landingtemplates.*')
                    ],
                    [
                        'href' => route('landingpages.index'),
                        'label' => __('Landing Pages'),
                        'active' => request()->routeIs('landingpages.*')
                    ],
                    [
                        'href' => route('tokens.index'),
                        'label' => __('Token'),
                        'active' => request()->routeIs('tokens.*')
                    ],
                    [
                        'href' => route('tokenprofile.index'),
                        'label' => __('Profile'),
                        'active' => request()->routeIs('tokenprofile.*')
                    ]
                ];
                break;
            
            case Tools::TYPOGENERATOR:
                $links = [
                    [
                        'href' => route('typogenerator.index'),
                        'label' => __('Typo Generator'),
                        'active' => request()->routeIs('typogenerator.*')
                    ]
                ];
            break;
            default:
                $links = [];
                break;
        }
        return $links;
    }
    public function tools(): array
    {
        return [
            [
                'key' => 'referer',
                'label' => 'Referer'
            ],
            [
                'key' => 'syndication',
                'label' => 'Syndication'
            ],
            [
                'key' => 'drip_feed',
                'label' => 'Drip feed'
            ],
            [
                'key' => 'cutter',
                'label' => 'File cutter'
            ],
            [
                'key' => 'event_calender',
                'label' => 'Event Calender'
            ],
            [
                'key' => 'leadvalidator',
                'label' => 'Lead Validator'
            ],
            [
                'key' => 'landingpage',
                'label' => 'Landing Page 3.0'
            ],
            [
                'key' => 'typogenerator',
                'label' => 'Typo Generator'
            ],
        ];
    }
    public function selectTool($tool): void
    {
        $this->tool = $tool;
        Tools::switch($tool);
        if (count($this->links())) {
            $this->redirect(head($this->links())['href']);
        }
    }
    public function getSelectedToolProperty()
    {
        $tools = collect($this->tools());
        return $tools->first(fn ($tool) => $tool['key'] === $this->tool) ?? $tools->first();
    }
    public function render()
    {
        return view('navigation-dropdown', [
            'tools' => $this->tools(),
            'links' => $this->links()
        ]);
    }
}