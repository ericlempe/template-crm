<div class="p-2 grid grid-cols-3 gap-4 h-full">
    @foreach($this->opportunities->groupBy('status') as $status => $opportunities)
        <div class="">
            <x-header :title="$status" :subtitle="'Total ' . $opportunities->count()  . ' opportunities'" size="text-xl"
                      separator progress-indicator/>
            <div class="space-y-2 p-2">
                @foreach($opportunities as $opportunity)
                    <x-card>{{ $opportunity->title }}</x-card>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
