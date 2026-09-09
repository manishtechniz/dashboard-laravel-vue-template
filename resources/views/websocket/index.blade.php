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
            text-shadow: 0 0 10px rgba(245, 158, 11, 0.5);
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

        @keyframes float-up {
            0% {
                transform: translate(-50%, 10px);
                opacity: 0;
            }

            20% {
                opacity: 1;
            }

            50% {
                transform: translate(-50%, -5px);
            }

            100% {
                transform: translate(-50%, -15px);
                opacity: 0.8;
            }
        }

        .animate-float-up {
            animation: float-up 3s ease-in-out infinite alternate;
        }
    </style>
</head>

<body class="bg-slate-900 text-slate-200 min-h-screen p-4 md:p-8 font-sans selection:bg-amber-500/30">

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
        <!-- Network / Connection Alert -->
        <div v-if="isOffline || connectionStatus === 'error' || connectionStatus === 'disconnected'" class="fixed top-0 left-0 w-full z-[110] bg-red-900/90 border-b-2 border-red-500 text-white px-4 py-3 shadow-[0_0_20px_rgba(239,68,68,0.5)] backdrop-blur-md flex items-center justify-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 animate-pulse text-red-300">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <span class="font-black uppercase tracking-wider text-sm md:text-base">
                    @{{ isOffline ? 'You are currently offline.' : 'Connection Lost.' }}
                </span>
                <span class="text-red-200 text-xs md:text-sm ml-2 hidden md:inline">
                    @{{ isOffline ? 'Please check your internet connection.' : 'Attempting to reconnect to the server...' }}
                </span>
            </div>
        </div>

    <!-- Header -->
    <div class="flex flex-col items-center gap-2 mb-10 w-full">
      <h1 class="text-2xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-orange-500 tracking-wider text-center drop-shadow-lg">
        MIDNIGHT TABLES
      </h1>
      <div class="bg-slate-800 border border-slate-700 text-amber-500 font-mono px-6 py-2 rounded-full shadow-[0_0_15px_rgba(245, 158, 11,0.2)] text-glow">
        @{{ currentDateTime }}
      </div>
      <div class="flex flex-col md:flex-row items-center gap-4 mt-2">
        <div v-if="userName" class="text-sm font-bold text-slate-400 tracking-widest uppercase flex items-center gap-2">
          Operator: <span class="text-slate-200">@{{ userName }}</span>
          <button @click="editOperatorName" class="text-amber-500 hover:text-amber-400 transition-colors" title="Edit Operator Name">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
              </svg>
          </button>
        </div>
        <div class="flex flex-row gap-2">
            <button @click="showAllRemarks = !showAllRemarks" :title="showAllRemarks ? 'Hide Remarks' : 'Show Remarks'" class="bg-amber-600/80 hover:bg-amber-500 p-2 md:px-4 md:py-1.5 rounded-full border border-amber-500 text-white font-bold transition-colors flex items-center justify-center shadow-[0_0_10px_rgba(245,158,11,0.5)]">
                <svg v-if="!showAllRemarks" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 md:w-4 md:h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 006 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337z" />
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 md:w-4 md:h-4">
                  <path fill-rule="evenodd" d="M4.804 21.644A6.707 6.707 0 006 21.75a6.721 6.721 0 003.583-1.029c.774.182 1.584.279 2.417.279 5.322 0 9.75-3.97 9.75-9 0-5.03-4.428-9-9.75-9s-9.75 3.97-9.75 9c0 2.409 1.025 4.587 2.674 6.192.232.226.277.428.254.543a3.73 3.73 0 01-.814 1.686.75.75 0 00.44 1.223zM8.25 10.875a1.125 1.125 0 100 2.25 1.125 1.125 0 000-2.25zM10.875 12a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0zm4.875-1.125a1.125 1.125 0 100 2.25 1.125 1.125 0 000-2.25z" clip-rule="evenodd" />
                </svg>
                <span class="hidden md:inline ml-1 text-xs whitespace-nowrap">@{{ showAllRemarks ? 'HIDE REMARKS' : 'SHOW REMARKS' }}</span>
            </button>
            <button @click="unlockAllTables" title="Unlock All" class="bg-slate-800 hover:bg-slate-700 p-2 md:px-4 md:py-1.5 rounded-full border border-slate-600 text-slate-300 transition-colors flex items-center shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 md:w-3 md:h-3 inline-block">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 1 1 9 0v3.75M3.75 21.75h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H3.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
                <span class="hidden md:inline ml-2 text-xs whitespace-nowrap">UNLOCK ALL</span>
            </button>
            <button @click="resetAllTables" :disabled="isResettingAll" title="Reset All Tables" class="bg-red-900/50 hover:bg-red-900 p-2 md:px-4 md:py-1.5 rounded-full border border-red-700 text-red-200 transition-colors flex items-center shadow-lg">
                <span v-if="isResettingAll" class="w-5 h-5 md:w-3 md:h-3 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 md:w-3 md:h-3 inline-block">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                <span class="hidden md:inline ml-2 text-xs whitespace-nowrap">RESET ALL TABLES</span>
            </button>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex flex-col items-center mt-20 gap-4">
      <div class="w-12 h-12 border-4 border-amber-500 border-t-transparent rounded-full animate-spin"></div>
      <div class="text-xl font-bold text-slate-400">Syncing with Grid...</div>
    </div>

    <div v-if="!loading" class="w-full">
        <!-- Standard Tables Section -->
        <h2 class="text-2xl font-black text-slate-100 uppercase tracking-widest mb-6 border-b-2 border-slate-700 pb-2">Standard Tables</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">
            <template v-for="table in regularTables" :key="table.id">
                <table-card :table="table" :user-name="userName" :updating-status="updatingStatus" :remark-updating-status="remarkUpdatingStatus" :show-all-remarks="showAllRemarks" :local-unlocked="localUnlockedTables" @update-field="updateTableField" @broadcast="broadcastUpdating" @toggle-lock="toggleLock" @unlock-locally="unlockLocally" @open-transfer="openTransferModal" @reset-table="resetTable" @edit-remark="editRemark"></table-card>
            </template>
        </div>

        <!-- Standing Tables Section -->
        <h2 class="text-2xl font-black text-slate-100 uppercase tracking-widest mb-6 border-b-2 border-slate-700 pb-2">Standing Tables</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">
            <template v-for="table in specialTables" :key="table.id">
                <table-card :table="table" :user-name="userName" :updating-status="updatingStatus" :remark-updating-status="remarkUpdatingStatus" :show-all-remarks="showAllRemarks" :local-unlocked="localUnlockedTables" @update-field="updateTableField" @broadcast="broadcastUpdating" @toggle-lock="toggleLock" @unlock-locally="unlockLocally" @open-transfer="openTransferModal" @reset-table="resetTable" @edit-remark="editRemark"></table-card>
            </template>
        </div>
    </div>

        <!-- Global Custom Dialog -->
        <div v-if="dialog.isOpen" class="fixed inset-0 bg-black/80 z-[100] flex items-center justify-center p-4">
            <div class="bg-slate-900 border border-slate-700 rounded-2xl p-6 w-full max-w-sm shadow-[0_0_40px_rgba(0,0,0,0.8)] transform transition-all">
                <div class="flex items-center gap-3 mb-4">
                    <div :class="{'text-blue-400': dialog.type === 'prompt', 'text-amber-400': dialog.type === 'confirm', 'text-red-400': dialog.type === 'alert'}">
                        <!-- Warning/Alert icon -->
                        <svg v-if="dialog.type === 'alert'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <!-- Info/Question icon -->
                        <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-100">@{{ dialog.title }}</h3>
                </div>
                
                <p class="text-slate-300 text-sm mb-6 leading-relaxed">@{{ dialog.message }}</p>
                
                <div v-if="dialog.type === 'prompt'" class="mb-6">
                    <textarea v-if="dialog.inputType === 'textarea'" v-model="dialog.inputValue" ref="dialogInput" class="w-full bg-slate-800 text-slate-100 rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 outline-none transition-shadow h-24" placeholder="Enter remark..."></textarea>
                    <input v-else :type="dialog.inputType" v-model="dialog.inputValue" ref="dialogInput" @keyup.enter="dialogConfirm" class="w-full bg-slate-800 text-slate-100 rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 outline-none transition-shadow" placeholder="Enter value..." />
                </div>
                
                <div class="flex justify-end gap-3">
                    <button v-if="dialog.type !== 'alert'" @click="dialogCancel" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium rounded-lg transition-colors border border-slate-700">Cancel</button>
                    <button @click="dialogConfirm" class="px-5 py-2 bg-gradient-to-r from-amber-600 to-orange-500 hover:from-amber-500 hover:to-orange-400 text-white rounded-lg transition-all shadow-lg hover:shadow-amber-500/20">
                        @{{ dialog.type === 'alert' ? 'OK' : 'Confirm' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Transfer Modal -->
        <div v-if="transferModalOpen" class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4">
            <div class="bg-slate-900 border border-slate-700 rounded-xl p-6 w-full max-w-md shadow-2xl">
                <h3 class="text-xl font-bold text-amber-500 mb-4">Transfer Table @{{ transferringFromTable?.table_number }}</h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-400 mb-2">Select Destination Table:</label>
                    <select v-model="transferToTableId" class="w-full bg-slate-800 text-slate-200 rounded border border-slate-600 px-3 py-2 focus:ring-2 focus:ring-amber-500 outline-none">
                        <option value="">-- Select Table --</option>
                        <option v-for="t in availableTransferTables" :key="t.id" :value="t.id">
                            Table @{{ t.table_number }} - (@{{ t.table_type }})
                        </option>
                    </select>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button @click="closeTransferModal" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 rounded transition-colors">Cancel</button>
                    <button @click="executeTransfer" :disabled="!transferToTableId || isTransferring" class="px-5 py-2 bg-gradient-to-r from-amber-600 to-orange-500 hover:from-amber-500 hover:to-orange-400 text-white rounded-lg transition-all shadow-lg hover:shadow-amber-500/20">
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
         :class="{'opacity-80 grayscale-[20%]': isEffectivelyLocked}"
         ref="cardContainer">
        
        <!-- Remark Icon (Draggable & Magnetizable) -->
        <div class="absolute w-10 h-10 rounded-full flex items-center justify-center cursor-move shadow-lg z-10 transition-all duration-300"
             :class="[
                 (remarkUpdatingStatus && remarkUpdatingStatus[table.table_number]) ? 'bg-indigo-500 text-white ring-4 ring-indigo-400 shadow-[0_0_15px_rgba(99,102,241,0.8)] animate-pulse' : 
                 (localTable.remark ? 'bg-amber-500 text-white shadow-amber-500/30' : 'bg-slate-700/80 text-slate-300 border border-slate-500'),
                 remarkJustUpdated ? 'animate-[bounce_1s_ease-in-out_1] ring-4 ring-amber-400 shadow-[0_0_20px_rgba(251,191,36,0.8)]' : '',
                 isDragging ? 'scale-110' : 'hover:scale-110 hover:bg-opacity-90 active:scale-95'
             ]"
             :style="{ 
                 left: remarkPos.x !== null ? remarkPos.x + 'px' : 'auto', 
                 top: remarkPos.y !== null ? remarkPos.y + 'px' : 'auto', 
                 right: remarkPos.x === null ? '10px' : 'auto', 
                 bottom: remarkPos.y === null ? '10px' : 'auto', 
                 transition: isDragging ? 'none' : 'left 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), top 0.3s cubic-bezier(0.34, 1.56, 0.64, 1)' 
             }"
             @mousedown.stop.prevent="startDrag"
             @touchstart.stop.prevent="startDrag"
             title="Table Remarks">
             
            <!-- Typing / Updating Indicator (Pulsing Dot) -->
            <span v-if="remarkUpdatingStatus && remarkUpdatingStatus[table.table_number]" class="absolute -top-1 -right-1 flex h-3.5 w-3.5 z-20 pointer-events-none">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-blue-500 border-2 border-slate-900"></span>
            </span>

            <!-- Operator Typing Name Popup -->
            <div v-if="remarkUpdatingStatus && remarkUpdatingStatus[table.table_number]" 
                 class="absolute -top-10 whitespace-nowrap bg-indigo-900 text-indigo-100 text-xs font-bold px-2 py-1 rounded shadow-lg border border-indigo-500 animate-bounce pointer-events-none"
                 :class="popupPositionClass">
                @{{ remarkUpdatingStatus[table.table_number] }} is typing...
                <div class="absolute -bottom-1 w-2 h-2 bg-indigo-900 border-b border-r border-indigo-500 rotate-45"
                     :class="popupArrowClass"></div>
            </div>  
            
            <!-- Has remark icon -->
            <svg v-if="localTable.remark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
              <path fill-rule="evenodd" d="M4.804 21.644A6.707 6.707 0 006 21.75a6.721 6.721 0 003.583-1.029c.774.182 1.584.279 2.417.279 5.322 0 9.75-3.97 9.75-9 0-5.03-4.428-9-9.75-9s-9.75 3.97-9.75 9c0 2.409 1.025 4.587 2.674 6.192.232.226.277.428.254.543a3.73 3.73 0 01-.814 1.686.75.75 0 00.44 1.223zM8.25 10.875a1.125 1.125 0 100 2.25 1.125 1.125 0 000-2.25zM10.875 12a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0zm4.875-1.125a1.125 1.125 0 100 2.25 1.125 1.125 0 000-2.25z" clip-rule="evenodd" />
            </svg>
            <!-- No remark icon -->
            <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 006 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337z" />
            </svg>
        </div>
        
        <!-- Top Header -->
        <div class="bg-black text-amber-400 font-black text-xl px-4 py-3 flex justify-between items-center border-b border-slate-800">
            <span class="tracking-widest">TABLE @{{ table.table_number }}</span>
            <div class="flex gap-1.5 items-center">
                <button v-if="!isEffectivelyLocked" @click="$emit('reset-table', table)" class="bg-red-900/40 hover:bg-red-900 p-1.5 rounded border border-red-700 text-red-200 transition-colors flex items-center" title="Reset Table">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                </button>
                <button v-if="!isEffectivelyLocked" @click="$emit('open-transfer', table)" class="bg-indigo-900/50 hover:bg-indigo-900 p-1.5 rounded border border-indigo-700 text-indigo-200 transition-colors flex items-center" title="Transfer Table">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                    </svg>
                </button>
                <!-- If locked, show local unlock button or permanent unlock button based on state -->
                <button v-if="table.is_locked && !localUnlocked[table.id]" @click="$emit('unlock-locally', table)" class="bg-slate-800 hover:bg-slate-700 p-1.5 rounded border border-slate-600 transition-colors flex items-center" title="Unlock for Editing">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-slate-300">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </button>
                <button v-if="table.is_locked && localUnlocked[table.id]" @click="$emit('toggle-lock', table)" class="bg-red-900/50 hover:bg-red-900 p-1.5 rounded border border-red-700 text-red-200 transition-colors flex items-center" title="Remove Lock Completely">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 1 1 9 0v3.75M3.75 21.75h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H3.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </button>
                <button v-if="!table.is_locked" @click="$emit('toggle-lock', table)" class="bg-slate-800 hover:bg-slate-700 p-1.5 rounded border border-slate-600 transition-colors flex items-center" title="Lock Table">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-slate-300">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 1 1 9 0v3.75M3.75 21.75h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H3.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
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
                <select v-model="localTable.table_type" @change="emitUpdate('table_type')" :disabled="isEffectivelyLocked" class="w-full bg-slate-800 text-slate-200 text-sm rounded border border-slate-600 px-2 py-1.5 focus:ring-2 focus:ring-amber-500 outline-none disabled:opacity-50 cursor-pointer">
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
                <select v-model="localTable.service_staff" @change="emitUpdate('service_staff')" :disabled="isEffectivelyLocked" class="w-full bg-slate-800 text-slate-200 text-sm rounded border border-slate-600 px-2 py-1.5 focus:ring-2 focus:ring-amber-500 outline-none disabled:opacity-50 cursor-pointer">
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
                <select v-model="localTable.status" @change="emitUpdate('status')" :disabled="isEffectivelyLocked" class="w-full bg-slate-800 text-sm rounded border border-slate-600 px-2 py-1.5 focus:ring-2 focus:ring-amber-500 outline-none disabled:opacity-50 cursor-pointer font-bold" :class="statusColorClass">
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

        <!-- Global Remark Pop Up (Instagram Style) -->
        <div v-if="showAllRemarks && localTable.remark" 
             class="absolute bottom-10 left-1/2 -translate-x-1/2 w-fit min-w-[120px] max-w-[90%] max-h-[80%] overflow-hidden bg-white/95 backdrop-blur text-slate-900 text-sm font-bold px-5 py-4 rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.7)] border-2 border-amber-300 z-40 text-center flex flex-col justify-center animate-float-up pointer-events-none">
            <div class="whitespace-normal break-words w-full overflow-hidden text-ellipsis" style="display: -webkit-box; -webkit-line-clamp: 6; -webkit-box-orient: vertical;">@{{ localTable.remark }}</div>
        </div>

        <!-- Updating Banner -->
        <div v-if="updatingStatus[table.table_number]" class="bg-amber-500/10 border-t border-amber-500/50 text-amber-500 text-xs font-bold text-center py-2 animate-pulse">
            @{{ updatingStatus[table.table_number] }} is updating...
        </div>
    </div>
</script>

    <script type="module">
        // Child Component for Table Cards
        adminVueApp.component('table-card', {
            template: '#table-card-template',
            props: ['table', 'userName', 'updatingStatus', 'remarkUpdatingStatus', 'showAllRemarks', 'localUnlocked'],
            data() {
                return {
                    localTable: {
                        ...this.table
                    },
                    remarkPos: {
                        x: null,
                        y: null
                    },
                    isDragging: false,
                    dragMoved: false,
                    dragStartX: 0,
                    dragStartY: 0,
                    initialIconX: 0,
                    initialIconY: 0,
                    remarkJustUpdated: false
                }
            },
            watch: {
                'table.remark'(newVal, oldVal) {
                    if (newVal !== oldVal && oldVal !== undefined) {
                        this.remarkJustUpdated = true;
                        setTimeout(() => {
                            this.remarkJustUpdated = false;
                        }, 2000);
                    }
                },
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
                popupPositionClass() {
                    if (this.remarkPos.x === null) return 'right-0';
                    const container = this.$refs.cardContainer;
                    if (!container) return 'right-0';

                    const width = container.offsetWidth;
                    if (this.remarkPos.x < 80) return 'left-0';
                    if (this.remarkPos.x > width - 120) return 'right-0';
                    return 'left-1/2 -translate-x-1/2';
                },
                popupArrowClass() {
                    if (this.remarkPos.x === null) return 'right-4';
                    const container = this.$refs.cardContainer;
                    if (!container) return 'right-4';

                    const width = container.offsetWidth;
                    if (this.remarkPos.x < 80) return 'left-4';
                    if (this.remarkPos.x > width - 120) return 'right-4';
                    return 'left-1/2 -translate-x-1/2';
                },
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
                },
                startDrag(e) {
                    this.isDragging = true;
                    this.dragMoved = false;

                    const clientX = e.clientX || (e.touches && e.touches[0].clientX);
                    const clientY = e.clientY || (e.touches && e.touches[0].clientY);

                    this.dragStartX = clientX;
                    this.dragStartY = clientY;

                    if (this.remarkPos.x === null || this.remarkPos.y === null) {
                        const container = this.$refs.cardContainer;
                        if (container) {
                            this.remarkPos.x = container.offsetWidth - 40 - 10;
                            this.remarkPos.y = container.offsetHeight - 40 - 10;
                        } else {
                            this.remarkPos.x = 0;
                            this.remarkPos.y = 0;
                        }
                    }

                    this.initialIconX = this.remarkPos.x;
                    this.initialIconY = this.remarkPos.y;

                    document.addEventListener('mousemove', this.onDrag);
                    document.addEventListener('mouseup', this.stopDrag);
                    document.addEventListener('touchmove', this.onDrag, {
                        passive: false
                    });
                    document.addEventListener('touchend', this.stopDrag);
                },
                onDrag(e) {
                    if (!this.isDragging) return;

                    const clientX = e.clientX || (e.touches && e.touches[0].clientX);
                    const clientY = e.clientY || (e.touches && e.touches[0].clientY);

                    const dx = clientX - this.dragStartX;
                    const dy = clientY - this.dragStartY;

                    if (Math.abs(dx) > 3 || Math.abs(dy) > 3) {
                        this.dragMoved = true;
                    }

                    const container = this.$refs.cardContainer;
                    if (!container) return;
                    const width = container.offsetWidth;
                    const height = container.offsetHeight;
                    const iconSize = 40;

                    let newX = this.initialIconX + dx;
                    let newY = this.initialIconY + dy;

                    newX = Math.max(0, Math.min(newX, width - iconSize));
                    newY = Math.max(0, Math.min(newY, height - iconSize));

                    this.remarkPos.x = newX;
                    this.remarkPos.y = newY;

                    if (e.preventDefault) e.preventDefault();
                },
                stopDrag(e) {
                    this.isDragging = false;
                    document.removeEventListener('mousemove', this.onDrag);
                    document.removeEventListener('mouseup', this.stopDrag);
                    document.removeEventListener('touchmove', this.onDrag);
                    document.removeEventListener('touchend', this.stopDrag);

                    this.applyMagnetEffect();

                    if (!this.dragMoved) {
                        this.$emit('edit-remark', this.table);
                    }
                },
                applyMagnetEffect() {
                    const container = this.$refs.cardContainer;
                    if (!container) return;
                    const width = container.offsetWidth;
                    const height = container.offsetHeight;
                    const iconSize = 40;
                    const padding = 10;

                    const x = this.remarkPos.x;
                    const y = this.remarkPos.y;

                    const distLeft = x;
                    const distRight = width - iconSize - x;
                    const distTop = y;
                    const distBottom = height - iconSize - y;

                    const minLeftRight = Math.min(distLeft, distRight);
                    const minTopBottom = Math.min(distTop, distBottom);

                    if (minLeftRight < minTopBottom) {
                        this.remarkPos.x = distLeft < distRight ? padding : width - iconSize - padding;
                        this.remarkPos.y = Math.max(padding, Math.min(y, height - iconSize - padding));
                    } else {
                        this.remarkPos.y = distTop < distBottom ? padding : height - iconSize - padding;
                        this.remarkPos.x = Math.max(padding, Math.min(x, width - iconSize - padding));
                    }
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
                    remarkUpdatingStatus: {},
                    showAllRemarks: false,
                    transferModalOpen: false,
                    transferringFromTable: null,
                    transferToTableId: '',
                    isTransferring: false,
                    isResettingAll: false,
                    isOffline: !navigator.onLine,
                    connectionStatus: 'connecting',
                    dialog: {
                        isOpen: false,
                        type: 'alert',
                        title: '',
                        message: '',
                        inputValue: '',
                        inputType: 'text',
                        resolve: null,
                        reject: null
                    },
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
                window.addEventListener('online', this.updateOnlineStatus);
                window.addEventListener('offline', this.updateOnlineStatus);
                await this.initUser();
                await this.fetchTables();
                this.initRealtime();
                this.initBroadcast();
            },
            beforeUnmount() {
                if (this.clockInterval) clearInterval(this.clockInterval);
                window.removeEventListener('online', this.updateOnlineStatus);
                window.removeEventListener('offline', this.updateOnlineStatus);
                if (this.realtimeChannel) supabase.removeChannel(this.realtimeChannel);
                if (this.broadcastChannel) supabase.removeChannel(this.broadcastChannel);
            },
            methods: {
                updateOnlineStatus() {
                    this.isOffline = !navigator.onLine;
                },
                showDialog(options) {
                    return new Promise((resolve, reject) => {
                        this.dialog = {
                            isOpen: true,
                            type: options.type || 'alert',
                            title: options.title || 'Notification',
                            message: options.message || '',
                            inputValue: options.initialValue || '',
                            inputType: options.inputType || 'text',
                            resolve,
                            reject
                        };
                        if (options.type === 'prompt') {
                            this.$nextTick(() => {
                                if (this.$refs.dialogInput) this.$refs.dialogInput.focus();
                            });
                        }
                    });
                },
                dialogConfirm() {
                    if (this.dialog.type === 'prompt') {
                        this.dialog.resolve(this.dialog.inputValue);
                    } else if (this.dialog.type === 'confirm') {
                        this.dialog.resolve(true);
                    } else {
                        this.dialog.resolve(true); // alert
                    }
                    this.dialog.isOpen = false;
                },
                dialogCancel() {
                    if (this.dialog.type === 'prompt') {
                        this.dialog.resolve(null);
                    } else if (this.dialog.type === 'confirm') {
                        this.dialog.resolve(false);
                    } else {
                        this.dialog.resolve(false);
                    }
                    this.dialog.isOpen = false;
                },
                customAlert(message, title = "System Alert") {
                    return this.showDialog({
                        type: 'alert',
                        title,
                        message
                    });
                },
                customConfirm(message, title = "Confirmation Required") {
                    return this.showDialog({
                        type: 'confirm',
                        title,
                        message
                    });
                },
                customPrompt(message, inputType = 'text', title = "Input Required") {
                    return this.showDialog({
                        type: 'prompt',
                        title,
                        message,
                        inputType
                    });
                },
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
                async initUser() {
                    let storedName = localStorage.getItem('club_userName');
                    if (!storedName) {
                        storedName = await this.customPrompt("Please enter your name for the Dashboard:", 'text', 'Welcome');
                        if (storedName) {
                            localStorage.setItem('club_userName', storedName);
                        } else {
                            storedName = "Anonymous";
                        }
                    }
                    this.userName = storedName;
                },
                async editOperatorName() {
                    const newName = await this.customPrompt("Enter new Operator Name:", 'text', 'Edit Operator');
                    if (newName && newName.trim() !== '') {
                        this.userName = newName.trim();
                        localStorage.setItem('club_userName', this.userName);
                    }
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
                        .subscribe((status) => {
                            if (status === 'SUBSCRIBED') {
                                this.connectionStatus = 'connected';
                            } else if (status === 'TIMED_OUT' || status === 'CHANNEL_ERROR') {
                                this.connectionStatus = 'error';
                            } else if (status === 'CLOSED') {
                                this.connectionStatus = 'disconnected';
                            }
                        });
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
                        .on(
                            'broadcast', {
                                event: 'remark-updating'
                            },
                            (payload) => {
                                const {
                                    table_number,
                                    user,
                                    isUpdating
                                } = payload.payload;
                                if (user !== this.userName) {
                                    if (isUpdating) {
                                        this.remarkUpdatingStatus[table_number] = user;
                                    } else {
                                        delete this.remarkUpdatingStatus[table_number];
                                    }
                                }
                            }
                        )
                        .subscribe((status) => {
                            if (status === 'SUBSCRIBED') {
                                this.connectionStatus = 'connected';
                            } else if (status === 'TIMED_OUT' || status === 'CHANNEL_ERROR') {
                                this.connectionStatus = 'error';
                            } else if (status === 'CLOSED') {
                                this.connectionStatus = 'disconnected';
                            }
                        });
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
                broadcastRemarkUpdating(table_number, isUpdating) {
                    if (!this.broadcastChannel) return;
                    this.broadcastChannel.send({
                        type: 'broadcast',
                        event: 'remark-updating',
                        payload: {
                            table_number: table_number,
                            user: this.userName,
                            isUpdating: isUpdating
                        },
                    });
                },
                async validateInput(field, value) {
                    // Frontend Validations
                    if (field === 'bill_amount' && parseFloat(value) < 0) {
                        await this.customAlert("Bill amount cannot be negative.", "Validation Error");
                        return false;
                    }
                    if (field === 'guest_name' && value && /<script|javascript:|onload|onerror/i.test(value)) {
                        await this.customAlert("Invalid characters in guest name. Please remove HTML tags.", "Validation Error");
                        return false;
                    }
                    return true;
                },
                async editRemark(table) {
                    if (table.is_locked && !this.localUnlockedTables[table.id]) {
                        await this.customAlert("Table is locked. Please unlock first.", "Locked");
                        return;
                    }

                    this.broadcastRemarkUpdating(table.table_number, true);

                    let hasCleanedUp = false;

                    const performCleanup = () => {
                        if (hasCleanedUp) return; // Prevent duplicate network calls

                        // If triggered by visibilitychange, only clean up if actually hidden
                        if (event && event.type === 'visibilitychange' && document.visibilityState !== 'hidden') {
                            return;
                        }

                        hasCleanedUp = true;

                        // Use standard broadcast if it's a websocket, OR fetch with keepalive if it's an API
                        this.broadcastRemarkUpdating(table.table_number, false);
                    };

                    // Desktop relies heavily on this:
                    window.addEventListener('beforeunload', performCleanup);
                    // Mobile relies heavily on this:
                    document.addEventListener('visibilitychange', performCleanup);

                    try {
                        const newRemark = await this.showDialog({
                            type: 'prompt',
                            title: `Edit Remark (Table ${table.table_number})`,
                            inputType: 'textarea',
                            message: 'Enter any remarks or notes for this table:',
                            initialValue: table.remark || ''
                        });

                        if (newRemark !== null && newRemark !== (table.remark || '')) {
                            this.updateTableField(table.id, 'remark', newRemark);
                        }
                    } finally {
                        performCleanup();

                        window.removeEventListener('beforeunload', performCleanup);
                        document.removeEventListener('visibilitychange', performCleanup);
                    }
                },
                async updateTableField(table_id, field, value) {
                    if (!(await this.validateInput(field, value))) {
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

                        if (error.message.toLowerCase().includes('password')) {
                            delete this.localUnlockedTables[table_id]; // wrong password
                            if (await this.customConfirm("Incorrect password. Would you like to try again?", "Authentication Failed")) {
                                const newPass = await this.customPrompt("Enter correct password:", "password", "Retry Password");
                                if (newPass) {
                                    this.localUnlockedTables[table_id] = newPass;
                                    return this.updateTableField(table_id, field, value); // Retry without reverting
                                }
                            }
                        } else {
                            await this.customAlert(`Update failed: ${error.message}`, "Error");
                        }

                        await this.fetchTables(); // Revert local change if cancelled or other error
                    }
                },
                async unlockLocally(table) {
                    const pass = await this.customPrompt(`Enter password to edit Table ${table.table_number}:`, 'password', 'Unlock Table');
                    if (pass) {
                        this.localUnlockedTables[table.id] = pass;
                    }
                },
                async toggleLock(table) {
                    if (table.is_locked) {
                        // This is only shown if the user has unlocked it locally, allowing them to completely remove the lock
                        if (await this.customConfirm(`Remove lock permanently for Table ${table.table_number}?`, 'Confirm Remove Lock')) {
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
                                if (error.message.toLowerCase().includes('password')) {
                                    delete this.localUnlockedTables[table.id];
                                    if (await this.customConfirm("Incorrect password. Would you like to try again?", "Authentication Failed")) {
                                        const newPass = await this.customPrompt("Enter correct password:", "password", "Retry Password");
                                        if (newPass) {
                                            this.localUnlockedTables[table.id] = newPass;
                                            return this.toggleLock(table); // Retry
                                        }
                                    }
                                } else {
                                    await this.customAlert("Failed to unlock: " + error.message, "Error");
                                }
                            } else {
                                delete this.localUnlockedTables[table.id];
                            }
                        }
                    } else {
                        // Lock flow
                        const pass = await this.customPrompt(`Set a password to lock Table ${table.table_number}:`, 'text', 'Lock Table');

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

                            if (error) await this.customAlert("Failed to lock table.", "Error");
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
                        guest_name: sourceTable.guest_name,
                        remark: sourceTable.remark
                    };

                    const sourceUpdates = {
                        table_type: 'Empty Table',
                        service_staff: 'None',
                        booking_time: '',
                        status: 'Empty',
                        bill_amount: 0,
                        guest_name: '',
                        remark: ''
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
                        if (error.message && error.message.toLowerCase().includes('password')) {
                            if (await this.customConfirm("Incorrect password for one of the tables. Would you like to try again?", "Authentication Failed")) {
                                const newPass = await this.customPrompt("Enter correct password:", "password", "Retry Password");
                                if (newPass) {
                                    // Try updating both tables with the new password
                                    if (this.transferringFromTable.is_locked) this.localUnlockedTables[this.transferringFromTable.id] = newPass;
                                    if (this.transferToTableId && this.tables.find(t => t.id === this.transferToTableId)?.is_locked) this.localUnlockedTables[this.transferToTableId] = newPass;
                                    this.isTransferring = false;
                                    return this.executeTransfer(); // Retry
                                }
                            }
                        } else {
                            await this.customAlert("Transfer failed: " + error.message, "Error");
                        }
                    } finally {
                        this.isTransferring = false;
                    }
                },
                async resetTable(table) {
                    if (!(await this.customConfirm(`Are you sure you want to reset Table ${table.table_number}?`, "Confirm Reset"))) return;

                    const resetUpdates = {
                        table_type: 'Empty Table',
                        service_staff: 'None',
                        booking_time: '',
                        status: 'Empty',
                        bill_amount: 0,
                        guest_name: '',
                        remark: ''
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
                        if (error.message && error.message.toLowerCase().includes('password')) {
                            delete this.localUnlockedTables[table.id];
                            if (await this.customConfirm("Incorrect password. Would you like to try again?", "Authentication Failed")) {
                                const newPass = await this.customPrompt("Enter correct password:", "password", "Retry Password");
                                if (newPass) {
                                    this.localUnlockedTables[table.id] = newPass;
                                    return this.resetTable(table); // Retry
                                }
                            }
                        } else {
                            await this.customAlert("Reset failed: " + error.message, "Error");
                        }
                    }
                },
                async unlockAllTables() {
                    const masterPass = await this.customPrompt("Enter Master Password to unlock all tables:", "password", "Unlock All Tables");
                    if (!masterPass) return;

                    const lockedTables = this.tables.filter(t => t.is_locked);
                    if (lockedTables.length === 0) {
                        await this.customAlert("No locked tables found.", "Info");
                        return;
                    }

                    let successCount = 0;
                    let failCount = 0;

                    const promises = lockedTables.map(async (table) => {
                        const {
                            error
                        } = await supabase.rpc('secure_update_club_table', {
                            p_table_id: table.id,
                            p_updates: {
                                is_locked: false,
                                lock_password: null,
                                locked_by_name: null
                            },
                            p_provided_password: masterPass
                        });

                        if (error) {
                            failCount++;
                        } else {
                            successCount++;
                            delete this.localUnlockedTables[table.id];
                        }
                    });

                    await Promise.all(promises);

                    if (failCount > 0) {
                        await this.customAlert(`Unlocked ${successCount} tables. Failed to unlock ${failCount} tables (incorrect password?).`, "Partial Success");
                    } else {
                        await this.customAlert(`Successfully unlocked all ${successCount} locked tables.`, "Success");
                    }
                },
                async resetAllTables() {
                    if (!(await this.customConfirm("Are you sure you want to RESET ALL TABLES? This action cannot be undone.", "Global Reset"))) return;

                    this.isResettingAll = true;

                    const resetUpdates = {
                        table_type: 'Empty Table',
                        service_staff: 'None',
                        booking_time: '',
                        status: 'Empty',
                        bill_amount: 0,
                        guest_name: '',
                        remark: ''
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
                            await this.customAlert(`Reset complete. ${skippedCount} locked tables were skipped.`, "Info");
                        }
                    } catch (error) {
                        console.error("Global Reset error:", error);
                        await this.customAlert("Some tables failed to reset.", "Error");
                    } finally {
                        this.isResettingAll = false;
                    }
                }
            },
        });
    </script>
</body>

</html>