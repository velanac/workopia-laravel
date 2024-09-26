<x-layout>
    <h1>Avalible Job</h1>
    <ul>
        @forelse ($jobs as $job)
            <li>{{ $job->title }} - {{ $job->description }}</li>
        @empty
            <li>No jobs avalible</li>
        @endforelse
    </ul>
</x-layout>
