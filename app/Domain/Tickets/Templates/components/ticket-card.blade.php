@props([
    'ticket' => [], //ticket object or array
    'efforts' => [],
    'milestones' => [],
    'statusLabels' => [],
    'id' => '',
])

@if(empty($id) === false)
    <div hx-get="{{ BASE_URL }}/hx/tickets/ticketCard/get?id={{ $id }}"
         hx-trigger="load"
         hx-swap="innerhtml"
    >
        <x-global::content.card>
            <x-global::elements.loadingText type="card"/>
        </x-global::content.card>

    </div>

@else

    <x-global::content.card>
        <div class="join">
            @if ($ticket['dependingTicketId'] > 0)
                <a href="#/tickets/showTicket/{{ $ticket['dependingTicketId'] }}"
                   class="join-item">{{ $ticket['parentHeadline'] }}</a>
                //
            @endif

            <a href="#/tickets/showTicket/{{ $ticket['id'] }}"
               class="join-item"><strong>{{ $ticket['headline'] }}</strong></a>
        </div>
        <div class="row">
            <div class="col-md-4 px-[15px] py-0">

                <i class="fa-solid fa-business-time infoIcon"
                   data-tippy-content=" {{ __('label.due') }}"></i>

                <input type="text" title="{{ __('label.due') }}"
                       value="{{ format($ticket['dateToFinish'])->date(__('text.anytime')) }}"
                       class="duedates secretInput" data-id="{{ $ticket['id'] }}" name="date" />
            </div>
            <div class="col-md-8 mt-[3px]">
                <div class="right">
                    <x-global::actions.dropdown
                        label-text="<span class='text'>
                            {{ $ticket['storypoints'] != '' && $ticket['storypoints'] > 0 ? $efforts['' . $ticket['storypoints'] . ''] : __('label.story_points_unkown') }}
                                                        </span>&nbsp;<i class='fa fa-caret-down' aria-hidden='true'></i>"
                        contentRole="link" position="bottom" align="start"
                        class="dropdown ticketDropdown effortDropdown show"
                        id="effortDropdownMenuLink{{ $ticket['id'] }}">

                        <x-slot:menu>
                            <!-- Menu Header -->
                            <li class="nav-header border">{{ __('dropdown.how_big_todo') }}</li>

                            <!-- Dynamic Effort Menu Items -->
                            @foreach ($efforts as $effortKey => $effortValue)
                                <x-global::actions.dropdown.item variant="link"
                                                                 href="javascript:void(0)" :data-value="$ticket['id'] . '_' . $effortKey" :id="'ticketEffortChange_' . $ticket['id'] . $effortKey">
                                    {{ $effortValue }}
                                </x-global::actions.dropdown.item>
                            @endforeach
                        </x-slot:menu>
                    </x-global::actions.dropdown>

                    <x-global::actions.dropdown
                        label-text="<span class='text'>
                                        {{ $ticket['milestoneid'] != '' && $ticket['milestoneid'] != 0 ? $ticket['milestoneHeadline'] : __('label.no_milestone') }}
                                    </span>&nbsp;<i class='fa fa-caret-down' aria-hidden='true'></i>"
                        contentRole="link" position="bottom" align="start"
                        class="dropdown ticketDropdown milestoneDropdown colorized show"
                        style="background-color:{{ __( ($ticket['milestoneColor'] ?? '#ccc')) }}"
                        id="milestoneDropdownMenuLink{{ $ticket['id'] }}">

                        <x-slot:menu>
                            <!-- Menu Header -->
                            <li class="nav-header border">{{ __('dropdown.choose_milestone') }}</li>

                            <!-- No Milestone Menu Item -->
                            <x-global::actions.dropdown.item variant="link" href="javascript:void(0);"
                                                             data-label="{{ __('label.no_milestone') }}"
                                                             data-value="{{ $ticket['id'] }}_0_#b0b0b0" class="bg-[#b0b0b0]">
                                {{ __('label.no_milestone') }}
                            </x-global::actions.dropdown.item>

                            <!-- Dynamic Milestone Menu Items -->
                            @foreach ($milestones as $milestone)
                                <x-global::actions.dropdown.item variant="link"
                                                                 href="javascript:void(0);" :data-label="$milestone->headline" :data-value="$ticket['id'] .
                                                                '_' .
                                                                $milestone->id .
                                                                '_' .
                                                                $milestone->tags"
                                                                 :id="'ticketMilestoneChange_' .
                                                                $ticket['id'] .
                                                                $milestone->id" style="background-color:{{ $milestone->tags }}">
                                    {{ $milestone->headline }}
                                </x-global::actions.dropdown.item>
                            @endforeach
                        </x-slot:menu>

                    </x-global::actions.dropdown>


                    <x-global::actions.dropdown
                        label-text="<span class='text'>{!! $statusLabels[$ticket['status']]['name'] !!}</span>&nbsp;<i class='fa fa-caret-down' aria-hidden='true'></i>"
                        contentRole="link" position="bottom" align="start"
                        class="dropdown ticketDropdown statusDropdown colorized show {!! $statusLabels[$ticket['status']]['class'] !!}"
                        id="statusDropdownMenuLink{{ $ticket['id'] }}">

                        <x-slot:menu>
                            <!-- Menu Header -->
                            <li class="nav-header border">{{ __('dropdown.choose_status') }}</li>

                            <!-- Dynamic Status Menu Items -->
                            @foreach ($statusLabels as $key => $label)
                                <x-global::actions.dropdown.item variant="link"
                                                                 href="javascript:void(0);" :class="$label['class']" :data-label="$label['name']"
                                                                 :data-value="$ticket['id'] . '_' . $key . '_' . $label['class']" :id="'ticketStatusChange' . $ticket['id'] . $key">
                                    {{ $label['name'] }}
                                </x-global::actions.dropdown.item>
                            @endforeach
                        </x-slot:menu>
                    </x-global::actions.dropdown>

                </div>
            </div>
        </div>
    </x-global::content.card>

@endif
