<script setup>
import { ref, computed, onMounted } from 'vue';

const cryptoData = ref([]);
const loading = ref(true);
const searchTerm = ref('');
const sortKey = ref('rank');
const sortDirection = ref('asc');
const error = ref(null);
const viewMode = ref('table');
const itemsPerPage = ref(10);
const currentPage = ref(1); 

const tableHeaders = [
    { key: 'rank', label: 'Rank' },
    { key: 'name', label: 'Name' },
    { key: 'marketCapUsd', label: 'Market Cap' },
    { key: 'volumeUsd24Hr', label: '24h Volume' },
    { key: 'supply', label: 'Supply' },
    { key: 'priceUsd', label: 'Price' }
];

const sortedAndFilteredData = computed(() => {
    let filtered = cryptoData.value;

    // Apply search filter
    if (searchTerm.value) {
        const searchLower = searchTerm.value.toLowerCase();
        filtered = filtered.filter(crypto => 
            crypto.name.toLowerCase().includes(searchLower) ||
            crypto.symbol.toLowerCase().includes(searchLower)
        );
    }

    // Apply sorting
    return filtered.sort((a, b) => {
        let aVal = a[sortKey.value];
        let bVal = b[sortKey.value];

        // Convert to numbers for numerical fields
        if (['marketCapUsd', 'volumeUsd24Hr', 'supply', 'priceUsd', 'rank'].includes(sortKey.value)) {
            aVal = parseFloat(aVal);
            bVal = parseFloat(bVal);
        }

        if (sortDirection.value === 'asc') {
            return aVal > bVal ? 1 : -1;
        }
        return aVal < bVal ? 1 : -1;
    });
});
const totalPages = computed(() => Math.ceil(sortedAndFilteredData.value.length / itemsPerPage.value));

const paginatedData = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return sortedAndFilteredData.value.slice(start, end);
});
const changePage = (newPage) => {
    if (newPage >= 1 && newPage <= totalPages.value) {
        currentPage.value = newPage;
    }
};
const fetchData = async () => {
    try {
        loading.value = true;
        const response = await fetch('api/crypto'); 
        const data = await response.json();
        cryptoData.value = data.data;
    } catch (err) {
        console.error('Error fetching data:', err);
        error.value = 'Failed to load cryptocurrency data';
    } finally {
        loading.value = false;
    }
};

const updateSort = (key) => {
    if (sortKey.value === key) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortKey.value = key;
        sortDirection.value = 'asc';
    }
};

const toggleSortDirection = () => {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
};

const formatUSD = (value) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(parseFloat(value));
};

const formatNumber = (value) => {
    return new Intl.NumberFormat('en-US').format(parseFloat(value));
};

const getColorClass = (rank) => {
    if (rank <= 10) return 'bg-green-100 text-green-800';
    if (rank <= 50) return 'bg-blue-100 text-blue-800';
    return 'bg-gray-100 text-gray-800';
};

onMounted(() => {
    fetchData();
});
</script>

<template>
    <div class="p-6">
        <!-- Loading State -->
        <div v-if="loading" class="text-center py-10">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div>
            <p class="mt-4 text-gray-600">Loading cryptocurrency data...</p>
        </div>

        <div v-else>
            <!-- Search and Sort Controls -->
            <div class="mb-6 space-y-4 md:space-y-0 md:flex md:justify-between md:items-center">
                <div class="w-full md:w-64">
                    <input
                        type="text"
                        v-model="searchTerm"
                        placeholder="Search by name or symbol..."
                        class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    />
                </div>
                <div class="flex space-x-4">
                    <select
                        v-model="sortKey"
                        class="px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="rank">Rank</option>
                        <option value="name">Name</option>
                        <option value="marketCapUsd">Market Cap</option>
                        <option value="volumeUsd24Hr">24h Volume</option>
                        <option value="supply">Supply</option>
                    </select>
                    <button
                        @click="toggleSortDirection"
                        class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-500"
                    >
                        {{ sortDirection === 'asc' ? '↑' : '↓' }}
                    </button>
                    <button
                        @click="viewMode = 'table'"
                        :class="viewMode === 'table' ? 'bg-gray-700 text-white' : 'bg-gray-300'"
                        class="px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500"
                    >
                        Table View
                    </button>
                    <button
                        @click="viewMode = 'card'"
                        :class="viewMode === 'card' ? 'bg-gray-700 text-white' : 'bg-gray-300'"
                        class="px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500"
                    >
                        Card View
                    </button>

                </div>
            </div>

            <!-- Card View -->
            <div v-if="viewMode === 'card'" class="mb-8">
                <h2 class="text-2xl font-bold mb-4">Card View</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div
                        v-for="crypto in paginatedData"
                        :key="crypto.id"
                        class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden"
                    >
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">{{ crypto.name }}</h3>
                                    <p class="text-sm text-gray-500">{{ crypto.symbol }}</p>
                                </div>
                                <span 
                                    class="px-3 py-1 text-sm rounded-full"
                                    :class="getColorClass(crypto.rank)"
                                >
                                    #{{ crypto.rank }}
                                </span>
                            </div>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Market Cap:</span>
                                    <span class="font-medium">{{ formatUSD(crypto.marketCapUsd) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">24h Volume:</span>
                                    <span class="font-medium">{{ formatUSD(crypto.volumeUsd24Hr) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Supply:</span>
                                    <span class="font-medium">{{ formatNumber(crypto.supply) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Price:</span>
                                    <span class="font-medium">{{ formatUSD(crypto.priceUsd) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table View -->
            <div v-if="viewMode === 'table'">
                <h2 class="text-2xl font-bold mb-4">Table View</h2>
                <div class="overflow-x-auto bg-white rounded-xl shadow">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th v-for="header in tableHeaders" 
                                    :key="header.key"
                                    @click="updateSort(header.key)"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                                >
                                    {{ header.label }}
                                    <span v-if="sortKey === header.key">
                                        {{ sortDirection === 'asc' ? '↑' : '↓' }}
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="crypto in paginatedData" 
                                :key="crypto.id"
                                class="hover:bg-gray-50"
                            >
                                <td class="px-6 py-4 whitespace-nowrap">{{ crypto.rank }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ crypto.name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ crypto.symbol }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ formatUSD(crypto.marketCapUsd) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ formatUSD(crypto.volumeUsd24Hr) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ formatNumber(crypto.supply) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ formatUSD(crypto.priceUsd) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <!-- Pagination -->
            <div class="mt-6 flex justify-between items-center">
                <button
                    @click="changePage(currentPage - 1)"
                    :disabled="currentPage === 1"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Previous
                </button>
                <span class="text-gray-600">
                    Page {{ currentPage }} of {{ totalPages }}
                </span>
                <button
                    @click="changePage(currentPage + 1)"
                    :disabled="currentPage === totalPages"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Next
                </button>
            </div>
            </div>
        </div>
    </div>
</template>