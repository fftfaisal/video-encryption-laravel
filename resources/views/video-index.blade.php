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
                                        Encrypted
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
                                            {{ $video->converted_for_streaming_at?->format('d M Y') }}
                                        </td>
                                        <td class="py-4 px-6">
                                            <a href="{{ route('videos.show',$video) }}"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">View<a>
                                        </td>
                                    </tr>
                                    
                                @empty
                                    <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700 text-center text-red-800 font-bold">
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
