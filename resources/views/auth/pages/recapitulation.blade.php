
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if (isset($electionTitle))
                {{$title . " " .$electionTitle->title}}
            @else
                {{$title}}
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($title === "New Activites")
                        <form method="post" enctype="multipart/form-data" action="{{ route('post-activities') }}">
                            @csrf
                            @method('post')

                            <div>
                                <x-input-label for="title" :value="__('Title')" />
                                <x-text-input required id="title" name="title" type="text" class="mt-1 block w-full" autocomplete="title" />
                            </div>

                            <div>
                                <x-input-label for="type" :value="__('Type')" />
                                <x-select-option :slt="['Présidentiale','législative','Municipale']" :title="'Type'"/>
                            </div>

                            <div>
                                <x-input-label for="launching_date" :value="__('Launching date')" />
                                <x-text-input required id="launching_date" name="launching_date" type="date" class="mt-1 block w-full" autocomplete="launching_date" />
                                <x-input-error :messages="$errors->get('launching_date')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="ending_date" :value="__('Ending date')" />
                                <x-text-input required id="ending_date" name="ending_date" type="date" class="mt-1 block w-full" autocomplete="ending_date" />
                                <x-input-error :messages="$errors->get('ending_date')" class="mt-2" />
                            </div>

                            <div class="text-warning" id="error"></div>

                            <div>
                                <x-input-label for="pays" :value="__('Pays')" />
                                <x-text-input required id="pays" name="pays" type="text" class="mt-1 block w-full" autocomplete="pays" />
                            </div>

                            <div>
                                <x-input-label for="candidates" :value="__('Candidat List')" />
                                <x-text-input required id="candidates" name="file" type="file" accept=".xlsx,.xls,.csv,.ods" class="mt-1 block w-full"/>
                            </div>

                            <div class="flex items-center gap-4 mt-3">
                                <x-primary-button>{{ __('Create') }}</x-primary-button>
                            </div>
                        </form>
                    @elseif ($title == 'Overview')
                        <table style="border-collapse:collapse; width:100%;text-align:center;">
                            <thead>
                                <tr>
                                    <th scope="col" style="border: solid 1px blue">Rang</th>
                                    <th scope="col" style="border: solid 1px blue">Full Name</th>
                                    <th scope="col" style="border: solid 1px blue">Picture</th>
                                    <th scope="col" style="border: solid 1px blue">Number Of Votes</th>
                                    <th scope="col" style="border: solid 1px blue">Pays</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count = 1; ?>
                                @foreach ($electionPending as $elt)
                                    <tr>
                                        <td style="border: solid 1px blue">
                                            {{$count}}
                                        </td>
                                        <td style="border: solid 1px blue">
                                            {{$elt->fullName}}
                                        </td>
                                        <td style="border: solid 1px blue">
                                            IMAGE
                                        </td>
                                        <td style="border: solid 1px blue">
                                            {{$elt->nbreDeVoix}}
                                        </td>
                                        <td style="border: solid 1px blue">
                                            {{$elt->pays}}
                                        </td>
                                    </tr>
                                    <?php $count ++;?>
                                @endforeach

                            </tbody>
                        </table>
                    @else
                        <table style="border-collapse:collapse; width:100%;text-align:center;">
                            <thead>
                                <tr>
                                    <th scope="col" style="border: solid 1px blue">Title</th>
                                    <th scope="col" style="border: solid 1px blue">Type</th>
                                    <th scope="col" style="border: solid 1px blue">Launching Date</th>
                                    <th scope="col" style="border: solid 1px blue">Ending Date</th>
                                    <th scope="col" style="border: solid 1px blue">Candidats</th>
                                    <th scope="col" style="border: solid 1px blue">Status</th>
                                    {{-- @if (!Route::is('trash-can') ) --}}
                                    <th scope="col" style="border: solid 1px blue">Actions</th>
                                    {{-- @endif --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($electionPending as $elt)
                                <tr>
                                    <td style="border: solid 1px blue"> {{$elt->title}} </td>
                                    <td style="border: solid 1px blue"> {{$elt->type}} </td>
                                    <td style="border: solid 1px blue"> {{$elt->launchingDate}} </td>
                                    <td style="border: solid 1px blue"> {{$elt->endingDate}} </td>
                                    <td style="border: solid 1px blue">
                                        <form action="{{ route('overview', ['id' => $elt->id]) }}" method="get">
                                            @csrf
                                            <x-primary-button ><i class="fa fa-eye opacity-6 text-dark" aria-hidden="true"></i></x-primary-button>
                                        </form>
                                    </td>
                                    <td style="border: solid 1px blue"> {{$elt->status}} </td>
                                    @if ($elt->status === 'pending')
                                        <td style="border: solid 1px blue; display: flex;justify-content:space-evenly">
                                            <form action="{{ route('stripe', ['id' => $elt->id, 'nom' => $elt->nom]) }}" method="get">
                                                @csrf
                                                <x-primary-button ><i class="fa fa-rocket opacity-6 text-dark" aria-hidden="true"></i></x-primary-button>
                                            </form>
                                            <form action="{{ route('deleteAll', ['id' => $elt->id]) }}" method="POST">
                                                @csrf
                                                <x-danger-button ><i class="fa fa-trash opacity-6 text-dark" aria-hidden="true"></i></x-danger-button>
                                            </form>
                                        </td>
                                    @else
                                        <td style="border: solid 1px blue; display: flex;justify-content:space-evenly">
                                            <form action="{{ route('deleteAll', ['id' => $elt->id]) }}" method="POST">
                                                @csrf
                                                <x-badge-global ><i class="fa fa-check"></i></x-badge-global>
                                            </form>
                                        </td>
                                    @endif
                                    {{-- @if (!Route::is('trash-can'))
                                        <td style="border: solid 1px blue">
                                            <x-primary-button ><i class="fa fa-rocket opacity-6 text-dark" aria-hidden="true"></i></x-primary-button>
                                            <x-danger-button ><i class="fa fa-trash opacity-6 text-dark" aria-hidden="true"></i></x-danger-button>
                                        </td>
                                    @endif --}}
                                </tr>

                                @endforeach


                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
