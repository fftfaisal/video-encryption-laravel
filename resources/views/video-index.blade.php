<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-auth-session-status :status="session('success')" class="mb-6" />
                    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="py-3 px-6">
                                        Material
                                    </th>
                                    <th scope="col" class="py-3 px-6">
                                        Description
                                    </th>
                                    <th scope="col" class="py-3 px-6">
                                        Filename
                                    </th>
                                    <th scope="col" class="py-3 px-6">
                                        Processing At
                                    </th>
                                    <th scope="col" class="py-3 px-6">
                                        Completed At
                                    </th>
                                    <th scope="col" class="py-3 px-6">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($videos as $video)
                                    <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                                        <th scope="row"
                                            class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ $video->title }}
                                        </th>
                                        <td class="py-4 px-6">
                                            {{ $video->description }}
                                        </td>
                                        <td class="py-4 px-6">
                                            {{ $video->original_name }}
                                        </td>
                                        <td class="py-4 px-6">
                                            {{ $video->convert_start_for_streaming_at?->format('d M Y, h:i:s A') }}
                                        </td>
                                        <td class="py-4 px-6">
                                            {{ $video->converted_for_streaming_at?->format('d M Y, h:i:s A') }}
                                        </td>
                                        <td class="py-4 px-6 flex gap-2">
                                            <a href="{{ route('videos.show', $video) }}"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">View</a>
                                            <a href="{{ route('videos.process', $video) }}"
                                                class="flex align-center font-medium text-green-600 dark:text-green-500 hover:underline">
                                                @if ($video->processing_percentage == 100)
                                                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor"
                                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                @else
                                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-yellow-600"
                                                        id="loader" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12"
                                                            r="10" stroke="currentColor" stroke-width="4">
                                                        </circle>
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                        </path>
                                                    </svg>
                                                @endif
                                                Status
                                            </a>
                                        </td>
                                    </tr>

                                @empty
                                    <tr
                                        class="bg-white border-b dark:bg-gray-900 dark:border-gray-700 text-center text-red-800 font-bold">
                                        <td class="py-4 px-6" colspan="99">
                                            No data found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
