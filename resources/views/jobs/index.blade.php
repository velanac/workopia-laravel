<x-layout>
    <h1>Avalible Job</h1>
    <ul>
        @forelse ($jobs as $job)
            <li><a href="{{ route('jobs.show', $job->id) }}">{{ $job->title }} - {{ $job->description }}</a></li>
        @empty
            <li>No jobs avalible</li>
        @endforelse
    </ul>
</x-layout>
