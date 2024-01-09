@props(['row'])

<x-card>
    <div class="flex">
        <img
            class="hidden w-48 mr-6 md:block"
            src="{{ $row->logo ? asset('storage/'.$row->logo) : asset('/images/no-image.png') }}"
            alt=""
        />
        <div>
            <h3 class="text-2xl">
                <a href="listings/{{ $row->id }}">{{ $row->title }}</a>
            </h3>
            <div class="text-xl font-bold mb-4">{{ $row->company }}</div>
            <x-listing-tags :tagsCsv="$row->tags" />
            <div class="text-lg mt-4">
                <i class="fa-solid fa-location-dot"></i>
                {{ $row->location }}
            </div>
        </div>
    </div>
</x-card>
