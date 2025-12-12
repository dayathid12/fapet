<div x-data="{ activeTab: @entangle('activeTab') }">
    <div class="border-b border-gray-200 dark:border-gray-700 mb-4">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab"
            data-tabs-toggle="#default-tab-content" role="tablist">
            <li class="me-2" role="presentation">
                <button class="inline-block p-4 border-b-2 rounded-t-lg" id="kendaraan-tab"
                    x-bind:class="{ 'border-primary-500 text-primary-600': activeTab === 'kendaraan' }"
                    x-on:click="activeTab = 'kendaraan'" type="button" role="tab"
                    aria-controls="kendaraan" aria-selected="false">Jadwal Kendaraan</button>
            </li>
            <li class="me-2" role="presentation">
                <button class="inline-block p-4 border-b-2 rounded-t-lg" id="pengemudi-tab"
                    x-bind:class="{ 'border-primary-500 text-primary-600': activeTab === 'pengemudi' }"
                    x-on:click="activeTab = 'pengemudi'" type="button" role="tab"
                    aria-controls="pengemudi" aria-selected="false">Jadwal Pengemudi</button>
            </li>
        </ul>
    </div>

    <div id="default-tab-content">
        <div x-show="activeTab === 'kendaraan'" x-cloak>
            @livewire('booking-kendaraan-calendar')
        </div>
        <div x-show="activeTab === 'pengemudi'" x-cloak>
            @livewire('jadwal-pengemudi-calendar')
        </div>
    </div>
</div>