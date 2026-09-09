<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Midnight Table Dashboard</title>
    @vite(['resources/admin/css/app.css','resources/admin/js/app.js'])
    @vite(['resources/js/realtime-supabase.js'])
    <style>
        /* Fallback / specific overrides for Tailwind if needed */
        .glass-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .text-glow {
            text-shadow: 0 0 10px rgba(234, 179, 8, 0.5);
        }

        /* Custom scrollbar for dark theme */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #0f172a;
        }

        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }
    </style>
</head>

<body class="bg-slate-900 text-slate-200 min-h-screen p-4 md:p-8 font-sans selection:bg-yellow-500/30">

    <div id="websocket" class="max-w-7xl mx-auto">
        <v-table-dashboard></v-table-dashboard>
    </div>

    <script>
        window.addEventListener("DOMContentLoaded", function(event) {
            adminVueApp.mount("#websocket");
        });
    </script>

    <!-- Template Definition -->
    <script type="text/x-template" id="v-table-dashboard-template">
        <div class="w-full flex flex-col items-center">
    <!-- Header -->
    <div class="flex flex-col items-center gap-2 mb-10 w-full">
      <h1 class="text-3xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-amber-600 tracking-wider text-center drop-shadow-lg">
        MIDNIGHT DASHBOARD
      </h1>
      <div class="bg-slate-800 border border-slate-700 text-yellow-500 font-mono px-6 py-2 rounded-full shadow-[0_0_15px_rgba(234,179,8,0.2)] text-glow">
        @{{ currentDateTime }}
      </div>
      <div class="flex flex-col md:flex-row items-center gap-4 mt-2">
        <div v-if="userName" class="text-sm font-bold text-slate-400 tracking-widest uppercase">
          Operator: <span class="text-slate-200">@{{ userName }}</span>
        </div>
        <button @click="resetAllTables" :disabled="isResettingAll" class="bg-red-900/50 hover:bg-red-900 text-xs px-4 py-1.5 rounded-full border border-red-700 text-red-200 transition-colors flex items-center shadow-lg">
            <span v-if="isResettingAll" class="w-3 h-3 mr-2 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
            <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-2 inline-block">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
            </svg>
            RESET ALL TABLES
        </button>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex flex-col items-center mt-20 gap-4">
      <div class="w-12 h-12 border-4 border-yellow-500 border-t-transparent rounded-full animate-spin"></div>
      <div class="text-xl font-bold text-slate-400">Syncing with Grid...</div>
    </div>

    <div v-if="!loading" class="w-full">
        <!-- Standard Tables Section -->
        <h2 class="text-2xl font-black text-slate-100 uppercase tracking-widest mb-6 border-b-2 border-slate-700 pb-2">Standard Tables</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">
            <template v-for="table in regularTables" :key="table.id">
                <table-card :table="table" :user-name="userName" :updating-status="updatingStatus" :local-unlocked="localUnlockedTables" @update-field="updateTableField" @broadcast="broadcastUpdating" @toggle-lock="toggleLock" @unlock-locally="unlockLocally" @open-transfer="openTransferModal" @reset-table="resetTable"></table-card>
            </template>
        </div>

        <!-- Standing Tables Section -->
        <h2 class="text-2xl font-black text-slate-100 uppercase tracking-widest mb-6 border-b-2 border-slate-700 pb-2">Standing Tables</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">
            <template v-for="table in specialTables" :key="table.id">
                <table-card :table="table" :user-name="userName" :updating-status="updatingStatus" :local-unlocked="localUnlockedTables" @update-field="updateTableField" @broadcast="broadcastUpdating" @toggle-lock="toggleLock" @unlock-locally="unlockLocally" @open-transfer="openTransferModal" @reset-table="resetTable"></table-card>
            </template>
        </div>
    </div>

        <!-- Transfer Modal -->
        <div v-if="transferModalOpen" class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4">
            <div class="bg-slate-900 border border-slate-700 rounded-xl p-6 w-full max-w-md shadow-2xl">
                <h3 class="text-xl font-bold text-yellow-500 mb-4">Transfer Table @{{ transferringFromTable?.table_number }}</h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-400 mb-2">Select Destination Table:</label>
                    <select v-model="transferToTableId" class="w-full bg-slate-800 text-slate-200 rounded border border-slate-600 px-3 py-2 focus:ring-2 focus:ring-yellow-500 outline-none">
                        <option value="">-- Select Table --</option>
                        <option v-for="t in availableTransferTables" :key="t.id" :value="t.id">
                            Table @{{ t.table_number }} - @{{ t.status }} (@{{ t.table_type }})
                        </option>
                    </select>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button @click="closeTransferModal" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 rounded transition-colors">Cancel</button>
                    <button @click="executeTransfer" :disabled="!transferToTableId || isTransferring" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 disabled:bg-indigo-600/50 text-white rounded transition-colors flex items-center gap-2">
                        <span v-if="isTransferring" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                        Transfer
                    </button>
                </div>
            </div>
        </div>
    </div>
</script>

    <!-- Table Card Component Template -->
    <script type="text/x-template" id="table-card-template">
        <div class="glass-card rounded-xl overflow-hidden shadow-2xl transition-all duration-300 hover:shadow-[0_0_20px_rgba(0,0,0,0.5)] relative"
         :class="{'opacity-80 grayscale-[20%]': isEffectivelyLocked}">
        
        <!-- Top Header -->
        <div class="bg-black text-yellow-400 font-black text-xl px-4 py-3 flex justify-between items-center border-b border-slate-800">
            <span class="tracking-widest">TABLE @{{ table.table_number }}</span>
            <div class="flex gap-2 items-center">
                <button v-if="!isEffectivelyLocked" @click="$emit('reset-table', table)" class="bg-red-900/40 hover:bg-red-900 text-[10px] px-2 py-1 rounded border border-red-700 text-red-200 transition-colors flex items-center" title="Reset Table">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-1">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Reset
                </button>
                <button v-if="!isEffectivelyLocked" @click="$emit('open-transfer', table)" class="bg-indigo-900/50 hover:bg-indigo-900 text-[10px] px-2 py-1 rounded border border-indigo-700 text-indigo-200 transition-colors flex items-center" title="Transfer Table">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-1">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                    </svg>
                    Transfer
                </button>
                <!-- If locked, show local unlock button or permanent unlock button based on state -->
                <button v-if="table.is_locked && !localUnlocked[table.id]" @click="$emit('unlock-locally', table)" class="bg-slate-800 hover:bg-slate-700 text-[10px] px-2 py-1 rounded border border-slate-600 transition-colors flex items-center" title="Unlock for Editing">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-1">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                    Edit
                </button>
                <button v-if="table.is_locked && localUnlocked[table.id]" @click="$emit('toggle-lock', table)" class="bg-red-900/50 hover:bg-red-900 text-[10px] px-2 py-1 rounded border border-red-700 text-red-200 transition-colors flex items-center" title="Remove Lock Completely">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-1">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 1 1 9 0v3.75M3.75 21.75h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H3.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                    Remove Lock
                </button>
                <button v-if="!table.is_locked" @click="$emit('toggle-lock', table)" class="bg-slate-800 hover:bg-slate-700 text-[10px] px-2 py-1 rounded border border-slate-600 transition-colors flex items-center" title="Lock Table">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-1">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 1 1 9 0v3.75M3.75 21.75h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H3.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                    Lock
                </button>
            </div>
        </div>

        <!-- Subtitle -->
        <div class="bg-amber-500/10 text-amber-500 font-bold px-4 py-3 text-center border-b border-slate-700/50">
            @{{ table.table_type || '"Empty Table"' }}
        </div>

        <!-- Grid Fields -->
        <div class="grid grid-cols-[90px_1fr] divide-y divide-slate-700/50" @input="$emit('broadcast', table.table_number)" @change="$emit('broadcast', table.table_number)">
            
            <!-- Type -->
            <div class="p-3 bg-slate-800/50 flex items-center text-xs font-bold text-slate-400 uppercase tracking-wider">Type</div>
            <div class="p-2 bg-slate-900/50">
                <select v-model="localTable.table_type" @change="emitUpdate('table_type')" :disabled="isEffectivelyLocked" class="w-full bg-slate-800 text-slate-200 text-sm rounded border border-slate-600 px-2 py-1.5 focus:ring-2 focus:ring-yellow-500 outline-none disabled:opacity-50 cursor-pointer">
                    <option value='Empty Table'>Empty</option>
                    <option value='Company Table'>Company</option>
                    <option value='TW Table'>TW</option>
                    <option value='Nirmal Table'>Nirmal</option>
                    <option value='R/B Table'>R/B</option>
                </select>
            </div>

            <!-- Service -->
            <div class="p-3 bg-slate-800/50 flex items-center text-xs font-bold text-slate-400 uppercase tracking-wider">Service</div>
            <div class="p-2 bg-slate-900/50">
                <select v-model="localTable.service_staff" @change="emitUpdate('service_staff')" :disabled="isEffectivelyLocked" class="w-full bg-slate-800 text-slate-200 text-sm rounded border border-slate-600 px-2 py-1.5 focus:ring-2 focus:ring-yellow-500 outline-none disabled:opacity-50 cursor-pointer">
                    <option value="None">None</option>
                    <option value="Anand">Anand</option>
                    <option value="Rohit">Rohit</option>
                    <option value="Lalchand">Lalchand</option>
                    <option value="Laxman">Laxman</option>
                    <option value="Mahesh">Mahesh</option>
                    <option value="Raj">Raj</option>
                    <option value="Bappi">Bappi</option>
                </select>
            </div>

            <!-- Time -->
            <div class="p-3 bg-slate-800/50 flex items-center text-xs font-bold text-slate-400 uppercase tracking-wider">Time</div>
            <div class="p-2 bg-slate-900/50">
                <input type="time" v-model="localTable.booking_time" @change="emitUpdate('booking_time')" :disabled="isEffectivelyLocked" class="w-full bg-transparent text-slate-200 text-sm px-2 py-1 focus:bg-slate-800 rounded outline-none disabled:opacity-50" />
            </div>

            <!-- Status -->
            <div class="p-3 bg-slate-800/50 flex items-center text-xs font-bold text-slate-400 uppercase tracking-wider">Status</div>
            <div class="p-2 bg-slate-900/50">
                <select v-model="localTable.status" @change="emitUpdate('status')" :disabled="isEffectivelyLocked" class="w-full bg-slate-800 text-sm rounded border border-slate-600 px-2 py-1.5 focus:ring-2 focus:ring-yellow-500 outline-none disabled:opacity-50 cursor-pointer font-bold" :class="statusColorClass">
                    <option value="Empty">Empty</option>
                    <option value="Booked">Booked</option>
                    <option value="Seated">Seated</option>
                    <option value="Billed">Billed</option>
                    <option value="Cleaned">Cleaned</option>
                </select>
            </div>

            <!-- Bill -->
            <div class="p-3 bg-slate-800/50 flex items-center text-xs font-bold text-slate-400 uppercase tracking-wider">Bill</div>
            <div class="p-2 bg-slate-900/50 flex items-center px-3">
                <span class="text-slate-400 font-bold mr-1">₹</span>
                <input type="number" v-model="localTable.bill_amount" @input="debouncedEmitUpdate('bill_amount')" :disabled="isEffectivelyLocked" placeholder="0.00" min="0" step="1" class="w-full bg-transparent text-slate-200 text-sm py-1 focus:bg-slate-800 rounded outline-none disabled:opacity-50" />
            </div>

            <!-- Guest -->
            <div class="p-3 bg-slate-800/50 flex items-center text-xs font-bold text-slate-400 uppercase tracking-wider">Guest</div>
            <div class="p-2 bg-slate-900/50">
                <input 
                    type="text" v-model="localTable.guest_name" @input="debouncedEmitUpdate('guest_name')" :disabled="isEffectivelyLocked" placeholder="Guest name..." class="w-full bg-transparent text-slate-200 text-sm px-2 py-1 focus:bg-slate-800 rounded outline-none disabled:opacity-50" />
            </div>
        </div>

        <!-- Updating Banner -->
        <div v-if="updatingStatus[table.table_number]" class="bg-yellow-500/20 border-t border-yellow-500/50 text-yellow-500 text-xs font-bold text-center py-2 animate-pulse">
            Updating by @{{ updatingStatus[table.table_number] }}...
        </div>
    </div>
</script>

    <script type="module">
        // Child Component for Table Cards
        adminVueApp.component('table-card', {
            template: '#table-card-template',
            props: ['table', 'userName', 'updatingStatus', 'localUnlocked'],
            data() {
                return {
                    localTable: {
                        ...this.table
                    }
                }
            },
            watch: {
                table: {
                    handler(newVal) {
                        // Sync external changes to local state if we aren't currently editing
                        this.localTable = {
                            ...newVal
                        };
                    },
                    deep: true
                }
            },
            computed: {
                isEffectivelyLocked() {
                    return this.table.is_locked && !this.localUnlocked[this.table.id];
                },
                statusColorClass() {
                    switch (this.localTable.status) {
                        case 'Empty':
                            return 'text-slate-300';
                        case 'Booked':
                            return 'text-red-400';
                        case 'Seated':
                            return 'text-blue-400';
                        case 'Billed':
                            return 'text-amber-400';
                        case 'Cleaned':
                            return 'text-green-400';
                        default:
                            return 'text-slate-200';
                    }
                }
            },
            methods: {
                emitUpdate(field) {
                    this.$emit('update-field', this.table.id, field, this.localTable[field]);
                },
                debouncedEmitUpdate(field) {
                    if (this.timeoutId) {
                        clearTimeout(this.timeoutId);
                    }
                    this.timeoutId = setTimeout(() => {
                        this.emitUpdate(field);
                    }, 1000);
                }
            }
        });

        // Main Dashboard Component
        adminVueApp.component('v-table-dashboard', {
            template: '#v-table-dashboard-template',
            data() {
                return {
                    loading: true,
                    userName: '',
                    tables: [],
                    updatingStatus: {},
                    transferModalOpen: false,
                    transferringFromTable: null,
                    transferToTableId: '',
                    isTransferring: false,
                    isResettingAll: false,
                    localUnlockedTables: {}, // { id: 'password' }
                    updateTimeouts: {},
                    currentDateTime: '',
                    clockInterval: null,
                    realtimeChannel: null,
                    broadcastChannel: null,
                    expectedTableNumbers: [
                        '1', '2', '3', '4', '5', '6', '7', '8', '9',
                        'S1', 'S2', 'S3', 'S4', 'S5', 'S6', 'S7', 'S8', 'S9'
                    ]
                };
            },
            computed: {
                regularTables() {
                    return this.tables.filter(t => !t.table_number.startsWith('S'))
                        .sort((a, b) => parseInt(a.table_number) - parseInt(b.table_number));
                },
                specialTables() {
                    return this.tables.filter(t => t.table_number.startsWith('S'))
                        .sort((a, b) => {
                            let numA = parseInt(a.table_number.replace('S', ''));
                            let numB = parseInt(b.table_number.replace('S', ''));
                            return numA - numB;
                        });
                },
                availableTransferTables() {
                    if (!this.transferringFromTable) return [];
                    return this.tables.filter(t => t.id !== this.transferringFromTable.id);
                }
            },
            async mounted() {
                this.initClock();
                this.initUser();
                await this.fetchTables();
                this.initRealtime();
                this.initBroadcast();
            },
            beforeUnmount() {
                if (this.clockInterval) clearInterval(this.clockInterval);
                if (this.realtimeChannel) supabase.removeChannel(this.realtimeChannel);
                if (this.broadcastChannel) supabase.removeChannel(this.broadcastChannel);
            },
            methods: {
                initClock() {
                    const update = () => {
                        const now = new Date();
                        const dateStr = now.toLocaleDateString('en-GB');
                        const timeStr = now.toLocaleTimeString('en-US', {
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit',
                            hour12: true
                        });
                        this.currentDateTime = `${dateStr} | ${timeStr}`;
                    };
                    update();
                    this.clockInterval = setInterval(update, 1000);
                },
                initUser() {
                    let storedName = localStorage.getItem('club_userName');
                    if (!storedName) {
                        storedName = prompt("Please enter your name for the Dashboard:");
                        if (storedName) {
                            localStorage.setItem('club_userName', storedName);
                        } else {
                            storedName = "Anonymous";
                        }
                    }
                    this.userName = storedName;
                },
                async fetchTables() {
                    this.loading = true;
                    const {
                        data,
                        error
                    } = await supabase
                        .from('club_tables')
                        .select('*');

                    this.loading = false;

                    if (error) {
                        console.error("Error fetching tables:", error);
                        return;
                    }

                    this.tables = data || [];

                },
                initRealtime() {
                    this.realtimeChannel = supabase
                        .channel('public:club_tables')
                        .on(
                            'postgres_changes', {
                                event: '*',
                                schema: 'public',
                                table: 'club_tables'
                            },
                            (payload) => {
                                if (payload.eventType === 'UPDATE') {
                                    const index = this.tables.findIndex(t => t.id === payload.new.id);
                                    if (index !== -1) {
                                        this.tables[index] = payload.new;
                                    }
                                } else if (payload.eventType === 'INSERT') {
                                    this.tables.push(payload.new);
                                } else if (payload.eventType === 'DELETE') {
                                    this.tables = this.tables.filter(t => t.id !== payload.old.id);
                                }
                            }
                        )
                        .subscribe();
                },
                initBroadcast() {
                    this.broadcastChannel = supabase
                        .channel('club_tables_presence')
                        .on(
                            'broadcast', {
                                event: 'table-updating'
                            },
                            (payload) => {
                                const {
                                    table_number,
                                    user
                                } = payload.payload;
                                if (user !== this.userName) {
                                    this.updatingStatus[table_number] = user;
                                    if (this.updateTimeouts[table_number]) {
                                        clearTimeout(this.updateTimeouts[table_number]);
                                    }
                                    this.updateTimeouts[table_number] = setTimeout(() => {
                                        delete this.updatingStatus[table_number];
                                    }, 3000);
                                }
                            }
                        )
                        .subscribe();
                },
                broadcastUpdating(table_number) {
                    if (!this.broadcastChannel) return;
                    this.broadcastChannel.send({
                        type: 'broadcast',
                        event: 'table-updating',
                        payload: {
                            table_number: table_number,
                            user: this.userName
                        },
                    });
                },
                validateInput(field, value) {
                    // Frontend Validations
                    if (field === 'bill_amount' && parseFloat(value) < 0) {
                        alert("Bill amount cannot be negative.");
                        return false;
                    }
                    if (field === 'guest_name' && value && /<script|javascript:|onload|onerror/i.test(value)) {
                        alert("Invalid characters in guest name. Please remove HTML tags.");
                        return false;
                    }
                    return true;
                },
                async updateTableField(table_id, field, value) {
                    if (!this.validateInput(field, value)) {
                        await this.fetchTables(); // Revert local change
                        return;
                    }

                    const updateData = {};
                    if (field === 'bill_amount') {
                        value = parseFloat(value) || 0;
                    }
                    updateData[field] = value;

                    // Send password if table is unlocked locally
                    const provided_password = this.localUnlockedTables[table_id] || null;

                    // Use the secure RPC function
                    const {
                        error
                    } = await supabase.rpc('secure_update_club_table', {
                        p_table_id: table_id,
                        p_updates: updateData,
                        p_provided_password: provided_password
                    });

                    if (error) {
                        console.error(`Error updating ${field} for table ${table_id}:`, error);
                        alert(`Update failed: ${error.message}`);
                        if (error.message.toLowerCase().includes('password')) {
                            delete this.localUnlockedTables[table_id]; // wrong password
                        }
                        await this.fetchTables(); // Revert local change
                    }
                },
                unlockLocally(table) {
                    const pass = prompt(`Enter password to edit Table ${table.table_number}:`);
                    if (pass) {
                        this.localUnlockedTables[table.id] = pass;
                    }
                },
                async toggleLock(table) {
                    if (table.is_locked) {
                        // This is only shown if the user has unlocked it locally, allowing them to completely remove the lock
                        if (confirm(`Remove lock permanently for Table ${table.table_number}?`)) {
                            const provided_password = this.localUnlockedTables[table.id];

                            // We also need to update lock status. We can reuse the RPC function.
                            const {
                                error
                            } = await supabase.rpc('secure_update_club_table', {
                                p_table_id: table.id,
                                p_updates: {
                                    is_locked: false,
                                    lock_password: null,
                                    locked_by_name: null
                                },
                                p_provided_password: provided_password
                            });

                            if (error) {
                                alert("Failed to unlock: " + error.message);
                            } else {
                                delete this.localUnlockedTables[table.id];
                            }
                        }
                    } else {
                        // Lock flow
                        const pass = prompt(`Set a password to lock Table ${table.table_number}:`);

                        if (pass) {
                            // For a table that is NOT locked yet, we can use the RPC function (provided_password can be null since it's not locked yet)
                            const {
                                error
                            } = await supabase.rpc('secure_update_club_table', {
                                p_table_id: table.id,
                                p_updates: {
                                    is_locked: true,
                                    lock_password: pass,
                                    locked_by_name: this.userName
                                },
                                p_provided_password: null
                            });

                            if (error) alert("Failed to lock table.");
                        }
                    }
                },
                openTransferModal(table) {
                    this.transferringFromTable = table;
                    this.transferToTableId = '';
                    this.transferModalOpen = true;
                },
                closeTransferModal() {
                    this.transferModalOpen = false;
                    this.transferringFromTable = null;
                    this.transferToTableId = '';
                },
                async executeTransfer() {
                    if (!this.transferToTableId || !this.transferringFromTable) return;

                    this.isTransferring = true;

                    const sourceTable = this.transferringFromTable;
                    const destTable = this.tables.find(t => t.id === this.transferToTableId);

                    const destUpdates = {
                        table_type: sourceTable.table_type,
                        service_staff: sourceTable.service_staff,
                        booking_time: sourceTable.booking_time,
                        status: sourceTable.status,
                        bill_amount: sourceTable.bill_amount,
                        guest_name: sourceTable.guest_name
                    };

                    const sourceUpdates = {
                        table_type: 'Empty Table',
                        service_staff: 'None',
                        booking_time: null,
                        status: 'Empty',
                        bill_amount: 0,
                        guest_name: ''
                    };

                    try {
                        let res = await supabase.rpc('secure_update_club_table', {
                            p_table_id: destTable.id,
                            p_updates: destUpdates,
                            p_provided_password: this.localUnlockedTables[destTable.id] || null
                        });

                        if (res.error) throw res.error;

                        res = await supabase.rpc('secure_update_club_table', {
                            p_table_id: sourceTable.id,
                            p_updates: sourceUpdates,
                            p_provided_password: this.localUnlockedTables[sourceTable.id] || null
                        });

                        if (res.error) throw res.error;

                        this.closeTransferModal();
                    } catch (error) {
                        console.error("Transfer error:", error);
                        alert("Transfer failed: " + error.message);
                    } finally {
                        this.isTransferring = false;
                    }
                },
                async resetTable(table) {
                    if (!confirm(`Are you sure you want to reset Table ${table.table_number}?`)) return;

                    const resetUpdates = {
                        table_type: 'Empty Table',
                        service_staff: 'None',
                        booking_time: null,
                        status: 'Empty',
                        bill_amount: 0,
                        guest_name: ''
                    };

                    try {
                        const {
                            error
                        } = await supabase.rpc('secure_update_club_table', {
                            p_table_id: table.id,
                            p_updates: resetUpdates,
                            p_provided_password: this.localUnlockedTables[table.id] || null
                        });

                        if (error) throw error;
                    } catch (error) {
                        console.error("Reset error:", error);
                        alert("Reset failed: " + error.message);
                    }
                },
                async resetAllTables() {
                    if (!confirm("Are you sure you want to RESET ALL TABLES? This action cannot be undone.")) return;

                    this.isResettingAll = true;

                    const resetUpdates = {
                        table_type: 'Empty Table',
                        service_staff: 'None',
                        booking_time: null,
                        status: 'Empty',
                        bill_amount: 0,
                        guest_name: ''
                    };

                    try {
                        const promises = this.tables.map(table => {
                            if (table.is_locked && !this.localUnlockedTables[table.id]) {
                                return Promise.resolve(); // Skip
                            }
                            return supabase.rpc('secure_update_club_table', {
                                p_table_id: table.id,
                                p_updates: resetUpdates,
                                p_provided_password: this.localUnlockedTables[table.id] || null
                            });
                        });

                        await Promise.all(promises);

                        let skippedCount = this.tables.filter(t => t.is_locked && !this.localUnlockedTables[t.id]).length;
                        if (skippedCount > 0) {
                            alert(`Reset complete. ${skippedCount} locked tables were skipped.`);
                        }
                    } catch (error) {
                        console.error("Global Reset error:", error);
                        alert("Some tables failed to reset.");
                    } finally {
                        this.isResettingAll = false;
                    }
                }
            },
        });
    </script>
</body>

</html>