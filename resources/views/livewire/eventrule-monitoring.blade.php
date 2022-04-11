<div>
    <x-jet-action-section>
        <x-slot name="title">
            Monitoring
        </x-slot>

        <x-slot name="description">
            Exports analysis.
        </x-slot>

        <x-slot name="content">
            <div>Total emails in list: <strong>{{ $this->event->totalEmailsCount }}</strong></div>
            <div>Emails left: <strong>{{ $this->event->emailsInPoolCount }}</strong></div>
            <div>Emails per action: <strong>{{ $this->event->emails_count }}</strong></div>
            <div>Actions total: <strong>{{ $this->event->actionsTotal }}</strong></div>
            <div>Actions performed: <strong>{{ $this->event->actionsPerformed }}</strong></div>
            <div>Actions left: <strong>{{ $this->event->actionsLeft }}</strong></div>
            <div>Estimate date: <strong>~{{ $this->event->estimatedDate->format('d.m.Y') }}</strong></div>
        </x-slot>
    </x-jet-action-section>
</div>
