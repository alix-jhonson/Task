<!-- resources/views/partials/family-tree-member.blade.php -->
<li>{{ $member->name }}</li>
@if ($member->descendants->count() > 0)
    <ul class="list-group">
        @foreach ($member->descendants as $descendant)
            @include('partials.family-tree-member', ['member' => $descendant])
        @endforeach
    </ul>
@endif

