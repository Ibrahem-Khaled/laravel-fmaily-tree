<li class="{{ $person->gender }}">
    <div data-toggle="modal" data-target="#showPersonModal{{ $person->id }}">
        <img src="{{ $person->photo_url ? asset('storage/' . $person->photo_url) : ($person->gender == 'male' ? asset('img/male-avatar.png') : asset('img/female-avatar.png')) }}"
            alt="{{ $person->full_name }}">
        <h6>{{ $person->full_name }}</h6>
        <p>{{ $person->life_span ?? '' }}</p>
    </div>

    @if ($person->children->count() > 0)
        <ul>
            @foreach ($person->children as $child)
                @include('people.partials.tree-node', ['person' => $child])
            @endforeach
        </ul>
    @endif
</li>
