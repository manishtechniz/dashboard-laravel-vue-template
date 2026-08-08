<x-admin::layouts>
    <div class="page-header flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="page-title text-2xl font-bold tracking-tight">Club Assets & Media</h1>
            <div class="page-breadcrumb text-sm text-(--text-muted)">Home / Clubs / Media Assets</div>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <Button
                label="Job Batches"
                icon="pi pi-history"
                size="small"
                severity="secondary"
                outlined
                @click="openBatchesDrawer" />
            <Button
                label="Refresh"
                icon="pi pi-refresh"
                size="small"
                severity="secondary"
                outlined
                :loading="loading"
                @click="fetchAssets(1)" />
            @if (hasPermission('admin.club_assets.bulk_upload'))
            <!-- <Button
                label="Bulk Upload"
                icon="pi pi-cloud-upload"
                size="small"
                class="shadow-sm"
                @click="openUploadDialog" /> -->


            <Button
                label="Upload Media Now"
                icon="pi pi-cloud-upload"
                size="small"
                @click="$refs.clubAssets.openUploadDialog()" />
            @endif
        </div>
    </div>

    <v-club-assets
        ref="clubAssets"
        :clubs-list='@json($clubs)'></v-club-assets>

    @pushOnce('scripts')
    <script type="text/x-template" id="v-club-assets-template">
        <div>
            <!-- Active Jobs Banner if running in background -->
            <div v-if="activeBatch && !activeBatch.finished" class="mb-6 p-4 rounded-xl border border-indigo-500/30 bg-indigo-500/10 backdrop-blur-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-indigo-500/20 text-indigo-400 flex items-center justify-center animate-pulse">
                        <i class="pi pi-spin pi-spinner text-xl"></i>
                    </div>
                    <div>
                        <div class="font-semibold text-sm">Background Job Batch in Progress...</div>
                        <div class="text-xs text-(--text-muted)">
                            <span class="font-medium text-(--text-base)">@{{ activeBatch.name }}</span> |
                            @{{ activeBatch.processed_jobs }} of @{{ activeBatch.total_jobs }} jobs done (@{{ activeBatch.progress }}%)
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="w-full md:w-48">
                        <ProgressBar :value="activeBatch.progress" :showValue="false" style="height: 8px;"></ProgressBar>
                    </div>
                    <Button label="View Details" size="small" text @click="showBatchModal(activeBatch)" />
                </div>
            </div>

            <!-- Top Filter & Search Bar -->
            <div class="p-4 mb-6 rounded-2xl border border-(--border) bg-(--bg-surface) shadow-sm space-y-4">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <!-- Left: Filters -->
                    <div class="flex flex-wrap items-center gap-3 flex-1">
                        <!-- Club Filter -->
                        <div class="w-full sm:w-56">
                            <Select
                                v-model="selectedClub"
                                :options="clubFilterOptions"
                                optionLabel="name"
                                optionValue="id"
                                placeholder="Filter by Club"
                                class="w-full"
                                filter
                                filterPlaceholder="Search club..."
                                @change="onFilterChange"
                            >
                                <template #value="slotProps">
                                    <div class="flex items-center gap-2" v-if="slotProps.value !== undefined">
                                        <i class="pi pi-building text-xs text-(--text-muted)"></i>
                                        <span>@{{ getClubLabel(slotProps.value) }}</span>
                                    </div>
                                    <span v-else>@{{ slotProps.placeholder }}</span>
                                </template>
                            </Select>
                        </div>

                        <!-- Media Type Filter Pills -->
                        <div class="flex items-center bg-(--bg-subtle) p-1 rounded-xl border border-(--border)">
                            <button
                                type="button"
                                class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all"
                                :class="selectedType === 'all' ? 'bg-(--bg-surface) text-(--text-base) shadow-xs font-semibold' : 'text-(--text-muted) hover:text-(--text-base)'"
                                @click="setTypeFilter('all')"
                            >
                                All Media
                            </button>
                            <button
                                type="button"
                                class="px-3 py-1.5 text-xs font-medium rounded-lg flex items-center gap-1.5 transition-all"
                                :class="selectedType === 'image' ? 'bg-(--bg-surface) text-indigo-500 shadow-xs font-semibold' : 'text-(--text-muted) hover:text-(--text-base)'"
                                @click="setTypeFilter('image')"
                            >
                                <i class="pi pi-image text-xs"></i>
                                Images
                            </button>
                            <button
                                type="button"
                                class="px-3 py-1.5 text-xs font-medium rounded-lg flex items-center gap-1.5 transition-all"
                                :class="selectedType === 'video' ? 'bg-(--bg-surface) text-pink-500 shadow-xs font-semibold' : 'text-(--text-muted) hover:text-(--text-base)'"
                                @click="setTypeFilter('video')"
                            >
                                <i class="pi pi-video text-xs"></i>
                                Videos (MP4)
                            </button>
                        </div>

                        <!-- Sort Dropdown -->
                        <div class="w-40 hidden sm:block">
                            <Select
                                v-model="selectedSort"
                                :options="sortOptions"
                                optionLabel="label"
                                optionValue="value"
                                class="w-full"
                                @change="onFilterChange"
                            />
                        </div>
                    </div>

                    <!-- Right: Search Bar & Actions -->
                    <div class="flex items-center gap-3 w-full lg:w-auto">
                        <div class="relative w-full lg:w-72">
                            <i class="pi pi-search absolute left-3 top-1/2 -translate-y-1/2 text-(--text-muted) text-xs"></i>
                            <input
                                type="text"
                                v-model="searchQuery"
                                placeholder="Search by name or file..."
                                class="w-full pl-9 pr-8 py-2 text-xs rounded-xl border border-(--border) bg-(--bg-subtle) text-(--text-base) placeholder:text-(--text-muted) focus:outline-none focus:border-indigo-500 transition"
                                @input="debouncedSearch"
                            />
                            <button
                                v-if="searchQuery"
                                @click="clearSearch"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2 text-(--text-muted) hover:text-(--text-base)"
                            >
                                <i class="pi pi-times text-xs"></i>
                            </button>
                        </div>

                        <!-- Mass Delete Action -->
                        <Button
                            v-if="selectedAssetIds.length > 0"
                            :label="'Delete (' + selectedAssetIds.length + ')'"
                            icon="pi pi-trash"
                            severity="danger"
                            size="small"
                            :loading="massDeleting"
                            @click="confirmMassDelete"
                        />
                    </div>
                </div>

                <!-- Active selection info bar -->
                <div v-if="assets.length > 0" class="flex items-center justify-between pt-2 border-t border-(--border)/60 text-xs text-(--text-muted)">
                    <div class="flex items-center gap-3">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input
                                type="checkbox"
                                :checked="isAllSelected"
                                @change="toggleSelectAll"
                                class="rounded border-(--border) text-indigo-600 focus:ring-indigo-500"
                            />
                            <span>Select All on Page (@{{ assets.length }})</span>
                        </label>
                        <span v-if="selectedAssetIds.length > 0" class="text-indigo-400 font-medium">
                            • @{{ selectedAssetIds.length }} selected
                        </span>
                    </div>
                    <div>
                        Showing @{{ totalAssets > 0 ? (currentPage - 1) * perPage + 1 : 0 }} - @{{ Math.min(currentPage * perPage, totalAssets) }} of @{{ totalAssets }} assets
                    </div>
                </div>
            </div>

            <!-- Loading Shimmer Skeleton -->
            <div v-if="loading && assets.length === 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                <div v-for="n in 12" :key="n" class="rounded-2xl border border-(--border) bg-(--bg-surface) p-2 animate-pulse space-y-2">
                    <div class="aspect-square w-full rounded-xl bg-(--bg-subtle)"></div>
                    <div class="h-3 bg-(--bg-subtle) rounded w-3/4"></div>
                    <div class="h-2 bg-(--bg-subtle) rounded w-1/2"></div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else-if="assets.length === 0" class="py-16 text-center rounded-2xl border border-dashed border-(--border) bg-(--bg-surface)">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-indigo-500/10 text-indigo-400 flex items-center justify-center">
                    <i class="pi pi-images text-2xl"></i>
                </div>
                <h3 class="text-base font-semibold text-(--text-base)">No media assets found</h3>
                <p class="text-xs text-(--text-muted) max-w-sm mx-auto mt-1 mb-6">
                    @{{ searchQuery || selectedClub !== 'all' || selectedType !== 'all' ? 'No media matching your filters. Try clearing some filters.' : 'Upload photos or videos for your clubs in bulk using multiple files or ZIP archives.' }}
                </p>
                <div class="flex items-center justify-center gap-3">
                    <Button
                        v-if="searchQuery || selectedClub !== 'all' || selectedType !== 'all'"
                        label="Reset Filters"
                        icon="pi pi-filter-slash"
                        size="small"
                        severity="secondary"
                        outlined
                        @click="resetFilters"
                    />
                    @if (hasPermission('admin.club_assets.bulk_upload'))
                    <Button
                        label="Upload Media Now"
                        icon="pi pi-cloud-upload"
                        size="small"
                        @click="openUploadDialog"
                    />
                    @endif
                </div>
            </div>

            <!-- Media Grid with Lazy Loading -->
            <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                <div
                    v-for="asset in assets"
                    :key="asset.id"
                    class="group relative rounded-2xl border border-(--border) bg-(--bg-surface) hover:border-indigo-500/50 hover:shadow-lg transition-all duration-200 flex flex-col overflow-hidden"
                    :class="{ 'ring-2 ring-indigo-500 border-transparent': selectedAssetIds.includes(asset.id) }"
                >
                    <!-- Media Thumbnail Container -->
                    <div class="relative aspect-square w-full bg-black/40 overflow-hidden cursor-pointer" @click="openPreview(asset)">
                        <!-- Checkbox Top-Left -->
                        <div class="absolute top-2 left-2 z-20" @click.stop>
                            <input
                                type="checkbox"
                                :value="asset.id"
                                v-model="selectedAssetIds"
                                class="w-4 h-4 rounded border-gray-400 text-indigo-600 focus:ring-indigo-500 cursor-pointer shadow"
                            />
                        </div>

                        <!-- Type Badge Top-Right -->
                        <div class="absolute top-2 right-2 z-20">
                            <span
                                v-if="asset.file_type === 'video'"
                                class="px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider rounded-md bg-pink-600/90 text-white backdrop-blur-md shadow flex items-center gap-1"
                            >
                                <i class="pi pi-video text-[9px]"></i> MP4
                            </span>
                            <span
                                v-else
                                class="px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider rounded-md bg-indigo-600/80 text-white backdrop-blur-md shadow flex items-center gap-1"
                            >
                                <i class="pi pi-image text-[9px]"></i> @{{ getExtension(asset.file_name) }}
                            </span>
                        </div>

                        <!-- Image Render (Lazy Loaded) -->
                        <template v-if="asset.file_type === 'image'">
                            <img
                                :src="asset.url"
                                :alt="asset.title || asset.original_name"
                                loading="lazy"
                                class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-300"
                                v-on:error="onImgError"
                            />
                        </template>

                        <!-- Video Render -->
                        <template v-else>
                            <div class="w-full h-full flex items-center justify-center bg-gray-900 group-hover:bg-gray-800 transition">
                                <video
                                    :src="asset.url"
                                    preload="metadata"
                                    muted
                                    class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition"
                                ></video>
                                <div class="absolute inset-0 flex items-center justify-center bg-black/30 group-hover:bg-black/10 transition">
                                    <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-md text-white flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                        <i class="pi pi-play text-sm ml-0.5"></i>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- Hover Overlay Actions -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-2.5 z-10">
                            <div class="flex items-center justify-between text-white" @click.stop>
                                <button
                                    type="button"
                                    class="w-7 h-7 rounded-lg bg-white/20 hover:bg-white/40 backdrop-blur-md flex items-center justify-center transition"
                                    title="Preview Fullscreen"
                                    @click="openPreview(asset)"
                                >
                                    <i class="pi pi-eye text-xs"></i>
                                </button>
                                <button
                                    type="button"
                                    class="w-7 h-7 rounded-lg bg-white/20 hover:bg-white/40 backdrop-blur-md flex items-center justify-center transition"
                                    title="Copy Direct Link"
                                    @click="copyUrl(asset.url)"
                                >
                                    <i class="pi pi-copy text-xs"></i>
                                </button>
                                <a
                                    :href="asset.url"
                                    download
                                    target="_blank"
                                    class="w-7 h-7 rounded-lg bg-white/20 hover:bg-white/40 backdrop-blur-md flex items-center justify-center transition"
                                    title="Download File"
                                >
                                    <i class="pi pi-download text-xs"></i>
                                </a>
                                @if (hasPermission('admin.club_assets.delete'))
                                <button
                                    type="button"
                                    class="w-7 h-7 rounded-lg bg-red-500/60 hover:bg-red-500 backdrop-blur-md flex items-center justify-center transition"
                                    title="Delete Asset"
                                    @click="deleteAsset(asset)"
                                >
                                    <i class="pi pi-trash text-xs"></i>
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Meta Information Footer -->
                    <div class="p-2.5 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="font-medium text-xs text-(--text-base) truncate" :title="asset.title || asset.original_name">
                                @{{ asset.title || asset.original_name }}
                            </div>
                            <div class="text-[11px] text-(--text-muted) flex items-center gap-1 mt-0.5 truncate">
                                <i class="pi pi-building text-[10px]"></i>
                                <span class="truncate">@{{ asset.club?.name || 'General' }}</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-[10px] text-(--text-muted) mt-2 pt-1.5 border-t border-(--border)/50">
                            <span>@{{ asset.formatted_size }}</span>
                            <span>@{{ asset.created_at }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination Bar -->
            <div v-if="totalPages > 1" class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4 p-4 rounded-2xl border border-(--border) bg-(--bg-surface)">
                <div class="text-xs text-(--text-muted)">
                    Page <span class="font-semibold text-(--text-base)">@{{ currentPage }}</span> of <span class="font-semibold text-(--text-base)">@{{ totalPages }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <Button
                        icon="pi pi-chevron-left"
                        size="small"
                        severity="secondary"
                        outlined
                        :disabled="currentPage <= 1"
                        @click="fetchAssets(currentPage - 1)"
                    />
                    <div class="flex items-center gap-1">
                        <button
                            v-for="p in visiblePages"
                            :key="p"
                            type="button"
                            class="w-8 h-8 rounded-lg text-xs font-medium transition"
                            :class="p === currentPage ? 'bg-indigo-600 text-white font-bold' : 'text-(--text-base) hover:bg-(--bg-subtle)'"
                            @click="fetchAssets(p)"
                        >
                            @{{ p }}
                        </button>
                    </div>
                    <Button
                        icon="pi pi-chevron-right"
                        size="small"
                        severity="secondary"
                        outlined
                        :disabled="currentPage >= totalPages"
                        @click="fetchAssets(currentPage + 1)"
                    />
                </div>
            </div>

            <!-- ======================================================== -->
            <!-- BULK UPLOAD MODAL (PrimeVue Dialog)                      -->
            <!-- ======================================================== -->
            <Dialog
                v-model:visible="uploadDialogVisible"
                :modal="true"
                :closable="!uploading"
                header="Bulk Upload Club Assets"
                :style="{ width: '640px', maxWidth: '95vw' }"
            >
                <div class="space-y-4 pt-2">
                    <!-- Progress Overlay State if Laravel Bus Batch is running -->
                    <div v-if="currentBatchTracking" class="p-6 rounded-2xl border border-(--border) bg-(--bg-subtle) space-y-4 text-center">
                        <div class="w-12 h-12 mx-auto rounded-full bg-indigo-500/20 text-indigo-400 flex items-center justify-center">
                            <i :class="getBatchStatusIcon(currentBatchTracking.status)"></i>
                        </div>

                        <div>
                            <h4 class="font-semibold text-base text-(--text-base)">
                                @{{ getBatchStatusTitle(currentBatchTracking.status) }}
                            </h4>
                            <p class="text-xs text-(--text-muted) mt-1">
                                Batch: <span class="font-semibold">@{{ currentBatchTracking.name }}</span>
                            </p>
                        </div>

                        <!-- Progress Bar -->
                        <div class="space-y-1 text-left">
                            <div class="flex justify-between text-xs font-medium text-(--text-base)">
                                <span>Progress</span>
                                <span>@{{ currentBatchTracking.progress }}%</span>
                            </div>
                            <ProgressBar :value="currentBatchTracking.progress" :showValue="false" style="height: 10px;"></ProgressBar>
                        </div>

                        <!-- Counters Cards -->
                        <div class="grid grid-cols-3 gap-2 text-center pt-2">
                            <div class="p-2.5 rounded-xl border border-(--border) bg-(--bg-surface)">
                                <div class="text-[11px] text-(--text-muted)">Total Jobs</div>
                                <div class="text-base font-bold text-(--text-base)">@{{ currentBatchTracking.total_jobs }}</div>
                            </div>
                            <div class="p-2.5 rounded-xl border border-(--border) bg-(--bg-surface)">
                                <div class="text-[11px] text-green-500">Processed</div>
                                <div class="text-base font-bold text-green-400">@{{ currentBatchTracking.processed_jobs }}</div>
                            </div>
                            <div class="p-2.5 rounded-xl border border-(--border) bg-(--bg-surface)">
                                <div class="text-[11px] text-red-500">Failed / Skipped</div>
                                <div class="text-base font-bold text-red-400">@{{ currentBatchTracking.failed_jobs }}</div>
                            </div>
                        </div>

                        <!-- Action Footer when Finished -->
                        <div v-if="currentBatchTracking.finished" class="pt-4 flex justify-end gap-2">
                            <Button
                                label="Close & Refresh Gallery"
                                icon="pi pi-check"
                                size="small"
                                @click="finishBatchTracking"
                            />
                        </div>
                    </div>

                    <!-- Regular Upload Form -->
                    <div v-else class="space-y-4">
                        <!-- Club Select -->
                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-(--text-base)">
                                Target Club <span class="text-red-500">*</span>
                            </label>
                            <Select
                                v-model="uploadForm.club_id"
                                :options="clubsList"
                                optionLabel="name"
                                optionValue="id"
                                placeholder="Select a club to upload assets for"
                                class="w-full"
                                filter
                                filterPlaceholder="Search club..."
                            />
                        </div>

                        <!-- Upload Type Tabs -->
                        <div class="flex rounded-xl border border-(--border) p-1 bg-(--bg-subtle)">
                            <button
                                type="button"
                                class="flex-1 py-2 text-xs font-medium rounded-lg flex items-center justify-center gap-2 transition"
                                :class="uploadForm.type === 'multiple' ? 'bg-(--bg-surface) text-(--text-base) font-semibold shadow-xs' : 'text-(--text-muted) hover:text-(--text-base)'"
                                @click="uploadForm.type = 'multiple'"
                            >
                                <i class="pi pi-images"></i>
                                Multiple Files (Photos & Videos)
                            </button>
                            <button
                                type="button"
                                class="flex-1 py-2 text-xs font-medium rounded-lg flex items-center justify-center gap-2 transition"
                                :class="uploadForm.type === 'zip' ? 'bg-(--bg-surface) text-(--text-base) font-semibold shadow-xs' : 'text-(--text-muted) hover:text-(--text-base)'"
                                @click="uploadForm.type = 'zip'"
                            >
                                <i class="pi pi-folder"></i>
                                ZIP Archive (.zip)
                            </button>
                        </div>

                        <!-- TAB 1: Multiple Files Drag and Drop -->
                        <div v-if="uploadForm.type === 'multiple'" class="space-y-3">
                            <div
                                class="border-2 border-dashed rounded-2xl p-6 text-center transition-colors cursor-pointer"
                                :class="isDragging ? 'border-indigo-500 bg-indigo-500/10' : 'border-(--border) hover:border-indigo-500/60 bg-(--bg-subtle)/50'"
                                @dragover.prevent="isDragging = true"
                                @dragleave.prevent="isDragging = false"
                                @drop.prevent="handleFilesDrop"
                                @click="$refs.multiFileInput.click()"
                            >
                                <input
                                    type="file"
                                    ref="multiFileInput"
                                    multiple
                                    accept=".jpg,.jpeg,.png,.webp,.mp4"
                                    class="hidden"
                                    @change="handleMultiFileSelect"
                                />
                                <div class="w-12 h-12 mx-auto rounded-full bg-indigo-500/10 text-indigo-400 flex items-center justify-center mb-3">
                                    <i class="pi pi-cloud-upload text-xl"></i>
                                </div>
                                <div class="text-sm font-semibold text-(--text-base)">
                                    Drop images or videos here, or <span class="text-indigo-500 underline">browse</span>
                                </div>
                                <div class="text-[11px] text-(--text-muted) mt-1">
                                    Supported: JPG, JPEG, PNG, WEBP, MP4 (Max 100MB per file)
                                </div>
                            </div>

                            <!-- Selected Files List -->
                            <div v-if="uploadForm.files.length > 0" class="space-y-2">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-semibold text-(--text-base)">Selected Files (@{{ uploadForm.files.length }})</span>
                                    <button type="button" class="text-red-400 hover:underline" @click="uploadForm.files = []">Clear all</button>
                                </div>
                                <div class="max-h-44 overflow-y-auto rounded-xl border border-(--border) p-2 space-y-1.5 bg-(--bg-subtle)">
                                    <div
                                        v-for="(f, i) in uploadForm.files"
                                        :key="i"
                                        class="flex items-center justify-between gap-2 p-1.5 rounded-lg bg-(--bg-surface) text-xs"
                                    >
                                        <div class="flex items-center gap-2 truncate">
                                            <i :class="f.type.startsWith('video') ? 'pi pi-video text-pink-400' : 'pi pi-image text-indigo-400'"></i>
                                            <span class="truncate font-mono text-[11px]">@{{ f.name }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 flex-shrink-0">
                                            <span class="text-[10px] text-(--text-muted)">@{{ formatBytes(f.size) }}</span>
                                            <button type="button" class="text-(--text-muted) hover:text-red-400" @click="removeFile(i)">
                                                <i class="pi pi-times text-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 2: ZIP Archive Upload -->
                        <div v-else class="space-y-3">
                            <div
                                class="border-2 border-dashed rounded-2xl p-6 text-center transition-colors cursor-pointer"
                                :class="isDragging ? 'border-indigo-500 bg-indigo-500/10' : 'border-(--border) hover:border-indigo-500/60 bg-(--bg-subtle)/50'"
                                @dragover.prevent="isDragging = true"
                                @dragleave.prevent="isDragging = false"
                                @drop.prevent="handleZipDrop"
                                @click="$refs.zipFileInput.click()"
                            >
                                <input
                                    type="file"
                                    ref="zipFileInput"
                                    accept=".zip"
                                    class="hidden"
                                    @change="handleZipSelect"
                                />
                                <div class="w-12 h-12 mx-auto rounded-full bg-pink-500/10 text-pink-400 flex items-center justify-center mb-3">
                                    <i class="pi pi-folder text-xl"></i>
                                </div>
                                <div class="text-sm font-semibold text-(--text-base)">
                                    Drop .zip archive here, or <span class="text-indigo-500 underline">browse</span>
                                </div>
                                <div class="text-[11px] text-(--text-muted) mt-1">
                                    Archive can only contain: JPG, JPEG, PNG, WEBP, MP4 (Max 512MB)
                                </div>
                            </div>

                            <!-- Selected ZIP Card -->
                            <div v-if="uploadForm.zipFile" class="flex items-center justify-between p-3 rounded-xl border border-indigo-500/30 bg-indigo-500/10 text-xs">
                                <div class="flex items-center gap-2 truncate">
                                    <i class="pi pi-file text-indigo-400 text-base"></i>
                                    <div class="truncate">
                                        <div class="font-medium text-(--text-base) truncate">@{{ uploadForm.zipFile.name }}</div>
                                        <div class="text-[10px] text-(--text-muted)">@{{ formatBytes(uploadForm.zipFile.size) }}</div>
                                    </div>
                                </div>
                                <button type="button" class="text-(--text-muted) hover:text-red-400" @click="uploadForm.zipFile = null">
                                    <i class="pi pi-times"></i>
                                </button>
                            </div>

                            <div class="p-3 rounded-xl border border-(--border) bg-(--bg-subtle) text-[11px] text-(--text-muted) space-y-1">
                                <div class="font-semibold text-(--text-base) flex items-center gap-1">
                                    <i class="pi pi-info-circle text-indigo-400"></i> Laravel Job Batch Processing
                                </div>
                                <div>ZIP contents are extracted and processed using native Laravel job batching. Allowed file types are stored into club media, and any disallowed formats automatically mark corresponding batch items as failed.</div>
                            </div>
                        </div>

                        <!-- Upload Progress Bar during HTTP send -->
                        <div v-if="uploading && uploadHttpProgress > 0" class="space-y-1">
                            <div class="flex justify-between text-xs text-(--text-muted)">
                                <span>Uploading file to server...</span>
                                <span>@{{ uploadHttpProgress }}%</span>
                            </div>
                            <ProgressBar :value="uploadHttpProgress" :showValue="false" style="height: 6px;"></ProgressBar>
                        </div>

                        <!-- Dialog Buttons -->
                        <div class="flex justify-end gap-2 pt-4 border-t border-(--border)">
                            <Button
                                type="button"
                                label="Cancel"
                                severity="secondary"
                                text
                                size="small"
                                :disabled="uploading"
                                @click="uploadDialogVisible = false"
                            />
                            <Button
                                type="button"
                                label="Start Upload & Dispatch Batch"
                                icon="pi pi-cloud-upload"
                                size="small"
                                :loading="uploading"
                                :disabled="!isUploadValid"
                                @click="submitBulkUpload"
                            />
                        </div>
                    </div>
                </div>
            </Dialog>

            <!-- ======================================================== -->
            <!-- PREVIEW MODAL (Image Lightbox & Video Player)           -->
            <!-- ======================================================== -->
            <Dialog
                v-model:visible="previewDialogVisible"
                :modal="true"
                :header="previewAsset ? (previewAsset.title || previewAsset.original_name) : 'Media Preview'"
                :style="{ width: '800px', maxWidth: '95vw' }"
            >
                <div v-if="previewAsset" class="space-y-4 pt-2">
                    <div class="rounded-2xl overflow-hidden bg-black/90 flex items-center justify-center max-h-[70vh]">
                        <!-- Image Fullscreen Preview -->
                        <template v-if="previewAsset.file_type === 'image'">
                            <img
                                :src="previewAsset.url"
                                :alt="previewAsset.title"
                                class="max-h-[65vh] w-auto object-contain mx-auto rounded-lg"
                            />
                        </template>

                        <!-- HTML5 Video Player -->
                        <template v-else>
                            <video
                                :src="previewAsset.url"
                                controls
                                autoplay
                                class="max-h-[65vh] w-full rounded-lg"
                            ></video>
                        </template>
                    </div>

                    <!-- Asset Metadata & Action Bar -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-3 rounded-xl border border-(--border) bg-(--bg-subtle) text-xs">
                        <div class="space-y-0.5">
                            <div class="font-medium text-(--text-base)">
                                Club: <span class="font-semibold">@{{ previewAsset.club?.name || 'General' }}</span>
                            </div>
                            <div class="text-(--text-muted) text-[11px]">
                                Size: @{{ previewAsset.formatted_size }}
                                <span v-if="previewAsset.width && previewAsset.height"> | Resolution: @{{ previewAsset.width }}x@{{ previewAsset.height }}</span>
                                | Uploaded: @{{ previewAsset.created_at }}
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <Button
                                label="Copy Link"
                                icon="pi pi-copy"
                                size="small"
                                severity="secondary"
                                outlined
                                @click="copyUrl(previewAsset.url)"
                            />
                            <a :href="previewAsset.url" download target="_blank">
                                <Button
                                    label="Download"
                                    icon="pi pi-download"
                                    size="small"
                                />
                            </a>
                        </div>
                    </div>
                </div>
            </Dialog>

            <!-- ======================================================== -->
            <!-- BATCHES & JOBS HISTORY DRAWER (Laravel job_batches)     -->
            <!-- ======================================================== -->
            <Drawer
                v-model:visible="batchesDrawerVisible"
                header="Laravel Job Batches History"
                position="right"
                :style="{ width: '500px', maxWidth: '95vw' }"
            >
                <div class="space-y-4 pt-2">
                    <div class="flex items-center justify-between text-xs text-(--text-muted)">
                        <span>Recent media batches (job_batches)</span>
                        <Button icon="pi pi-refresh" size="small" text @click="fetchBatchesList" />
                    </div>

                    <div v-if="batchesLoading" class="space-y-3 animate-pulse">
                        <div v-for="n in 5" :key="n" class="h-20 bg-(--bg-subtle) rounded-xl"></div>
                    </div>

                    <div v-else-if="batchesList.length === 0" class="py-12 text-center text-xs text-(--text-muted)">
                        No recent job batches recorded.
                    </div>

                    <div v-else class="space-y-3">
                        <div
                            v-for="b in batchesList"
                            :key="b.id"
                            class="p-3.5 rounded-2xl border border-(--border) bg-(--bg-subtle) space-y-2 cursor-pointer hover:border-indigo-500/50 transition"
                            @click="showBatchModal(b)"
                        >
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <div class="font-semibold text-xs text-(--text-base) truncate max-w-[280px]">
                                        @{{ b.name }}
                                    </div>
                                    <div class="text-[11px] text-(--text-muted) mt-0.5">
                                        ID: <span class="font-mono text-[10px]">@{{ b.id.substring(0, 8) }}...</span> • @{{ b.created_at }}
                                    </div>
                                </div>
                                <span
                                    class="px-2 py-0.5 text-[10px] font-semibold uppercase rounded-md"
                                    :class="getBatchBadgeClass(b.status)"
                                >
                                    @{{ b.status }}
                                </span>
                            </div>

                            <ProgressBar :value="b.progress" :showValue="false" style="height: 6px;"></ProgressBar>

                            <div class="flex items-center justify-between text-[11px] text-(--text-muted) pt-1">
                                <span>@{{ b.processed_jobs }} / @{{ b.total_jobs }} jobs done</span>
                                <span v-if="b.failed_jobs > 0" class="text-red-400 font-medium">@{{ b.failed_jobs }} failed</span>
                                <span>@{{ b.progress }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </Drawer>
        </div>
    </script>

    <script type="module">
        adminVueApp.component('v-club-assets', {
            template: '#v-club-assets-template',

            props: {
                clubsList: {
                    type: Array,
                    default: () => []
                }
            },

            data() {
                return {
                    loading: false,
                    assets: [],
                    totalAssets: 0,
                    currentPage: 1,
                    totalPages: 1,
                    perPage: 24,

                    // Filters
                    selectedClub: 'all',
                    selectedType: 'all',
                    selectedSort: 'created_at_desc',
                    searchQuery: '',
                    searchTimeout: null,

                    // Selection
                    selectedAssetIds: [],
                    massDeleting: false,

                    // Bulk Upload Modal
                    uploadDialogVisible: false,
                    uploading: false,
                    uploadHttpProgress: 0,
                    isDragging: false,
                    uploadForm: {
                        club_id: null,
                        type: 'multiple',
                        files: [],
                        zipFile: null
                    },

                    // Real-time Batch Progress Tracking
                    currentBatchTracking: null,
                    activeBatch: null,
                    pollInterval: null,

                    // Preview Modal
                    previewDialogVisible: false,
                    previewAsset: null,

                    // Batches History Drawer
                    batchesDrawerVisible: false,
                    batchesLoading: false,
                    batchesList: [],

                    sortOptions: [{
                            label: 'Newest First',
                            value: 'created_at_desc'
                        },
                        {
                            label: 'Oldest First',
                            value: 'created_at_asc'
                        },
                        {
                            label: 'File Size (Largest)',
                            value: 'file_size_desc'
                        },
                        {
                            label: 'File Size (Smallest)',
                            value: 'file_size_asc'
                        },
                    ]
                };
            },

            computed: {
                clubFilterOptions() {
                    return [{
                            id: 'all',
                            name: 'All Clubs'
                        },
                        ...this.clubsList
                    ];
                },

                isAllSelected() {
                    return this.assets.length > 0 && this.selectedAssetIds.length === this.assets.length;
                },

                isUploadValid() {
                    if (!this.uploadForm.club_id) return false;
                    if (this.uploadForm.type === 'multiple') {
                        return this.uploadForm.files.length > 0;
                    } else {
                        return !!this.uploadForm.zipFile;
                    }
                },

                visiblePages() {
                    const pages = [];
                    const start = Math.max(1, this.currentPage - 2);
                    const end = Math.min(this.totalPages, this.currentPage + 2);
                    for (let i = start; i <= end; i++) {
                        pages.push(i);
                    }
                    return pages;
                }
            },

            methods: {
                getClubLabel(clubId) {
                    if (clubId === 'all') return 'All Clubs';
                    const found = this.clubsList.find(c => c.id === clubId);
                    return found ? found.name : 'Unknown Club';
                },

                getExtension(filename) {
                    if (!filename) return 'IMG';
                    const parts = filename.split('.');
                    return parts.length > 1 ? parts.pop().toUpperCase() : 'IMG';
                },

                formatBytes(bytes) {
                    if (!bytes) return '0 B';
                    const k = 1024;
                    const sizes = ['B', 'KB', 'MB', 'GB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
                },

                onImgError(e) {
                    e.target.src = '{{ asset("preview-image.webp") }}';
                },

                setTypeFilter(type) {
                    this.selectedType = type;
                    this.fetchAssets(1);
                },

                onFilterChange() {
                    this.fetchAssets(1);
                },

                debouncedSearch() {
                    clearTimeout(this.searchTimeout);
                    this.searchTimeout = setTimeout(() => {
                        this.fetchAssets(1);
                    }, 350);
                },

                clearSearch() {
                    this.searchQuery = '';
                    this.fetchAssets(1);
                },

                resetFilters() {
                    this.selectedClub = 'all';
                    this.selectedType = 'all';
                    this.searchQuery = '';
                    this.selectedSort = 'created_at_desc';
                    this.fetchAssets(1);
                },

                toggleSelectAll(e) {
                    if (e.target.checked) {
                        this.selectedAssetIds = this.assets.map(a => a.id);
                    } else {
                        this.selectedAssetIds = [];
                    }
                },

                // Fetch Assets Ajax
                fetchAssets(page = 1) {
                    this.loading = true;
                    this.currentPage = page;

                    const [sortBy, sortOrder] = this.selectedSort.split('_');

                    const params = {
                        page: page,
                        per_page: this.perPage,
                        club_id: this.selectedClub,
                        file_type: this.selectedType !== 'all' ? this.selectedType : undefined,
                        search: this.searchQuery ? this.searchQuery.trim() : undefined,
                        sort_by: sortBy,
                        sort_order: sortOrder
                    };

                    this.$axios.get('{{ route("admin.club_assets.index") }}', {
                            params
                        })
                        .then(res => {
                            this.assets = res.data.data || [];
                            this.totalAssets = res.data.total || 0;
                            this.totalPages = res.data.last_page || 1;
                            this.selectedAssetIds = [];
                        })
                        .catch(err => {
                            this.$emitter.emit('add-flash', {
                                type: 'error',
                                message: 'Failed to load media assets.'
                            });
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                },

                // Preview Modal
                openPreview(asset) {
                    this.previewAsset = asset;
                    this.previewDialogVisible = true;
                },

                copyUrl(url) {
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(url).then(() => {
                            this.$emitter.emit('add-flash', {
                                type: 'success',
                                message: 'Direct URL copied to clipboard!'
                            });
                        });
                    }
                },

                // Delete Single Asset
                deleteAsset(asset) {
                    if (!confirm('Are you sure you want to delete this asset? This action cannot be undone.')) {
                        return;
                    }

                    this.$axios.delete(`{{ url('admin/club-assets') }}/${asset.id}`)
                        .then(res => {
                            this.$emitter.emit('add-flash', {
                                type: 'success',
                                message: res.data.message || 'Asset deleted successfully.'
                            });
                            this.fetchAssets(this.currentPage);
                        })
                        .catch(err => {
                            this.$emitter.emit('add-flash', {
                                type: 'error',
                                message: 'Failed to delete asset.'
                            });
                        });
                },

                // Mass Delete Assets
                confirmMassDelete() {
                    if (!confirm(`Are you sure you want to delete ${this.selectedAssetIds.length} selected assets?`)) {
                        return;
                    }

                    this.massDeleting = true;
                    this.$axios.post('{{ route("admin.club_assets.mass_delete") }}', {
                            indices: this.selectedAssetIds
                        })
                        .then(res => {
                            this.$emitter.emit('add-flash', {
                                type: 'success',
                                message: res.data.message || 'Selected assets deleted.'
                            });
                            this.selectedAssetIds = [];
                            this.fetchAssets(this.currentPage);
                        })
                        .catch(err => {
                            this.$emitter.emit('add-flash', {
                                type: 'error',
                                message: 'Failed to delete selected assets.'
                            });
                        })
                        .finally(() => {
                            this.massDeleting = false;
                        });
                },

                // Upload Dialog Management
                openUploadDialog() {
                    this.currentBatchTracking = null;
                    this.uploadHttpProgress = 0;
                    this.uploading = false;
                    if (this.selectedClub !== 'all') {
                        this.uploadForm.club_id = this.selectedClub;
                    } else if (this.clubsList.length > 0) {
                        this.uploadForm.club_id = this.clubsList[0].id;
                    }
                    this.uploadDialogVisible = true;
                },

                handleMultiFileSelect(e) {
                    const files = Array.from(e.target.files);
                    this.uploadForm.files = [...this.uploadForm.files, ...files];
                },

                handleFilesDrop(e) {
                    this.isDragging = false;
                    const files = Array.from(e.dataTransfer.files).filter(f => {
                        const ext = f.name.split('.').pop().toLowerCase();
                        return ['jpg', 'jpeg', 'png', 'webp', 'mp4'].includes(ext);
                    });
                    this.uploadForm.files = [...this.uploadForm.files, ...files];
                },

                removeFile(index) {
                    this.uploadForm.files.splice(index, 1);
                },

                handleZipSelect(e) {
                    const file = e.target.files[0];
                    if (file && file.name.endsWith('.zip')) {
                        this.uploadForm.zipFile = file;
                    }
                },

                handleZipDrop(e) {
                    this.isDragging = false;
                    const file = e.dataTransfer.files[0];
                    if (file && file.name.endsWith('.zip')) {
                        this.uploadForm.zipFile = file;
                    }
                },

                // Submit Upload & Start Polling
                submitBulkUpload() {
                    if (!this.isUploadValid) return;

                    this.uploading = true;
                    this.uploadHttpProgress = 1;

                    const formData = new FormData();
                    formData.append('club_id', this.uploadForm.club_id);
                    formData.append('upload_type', this.uploadForm.type);

                    if (this.uploadForm.type === 'zip') {
                        formData.append('zip_file', this.uploadForm.zipFile);
                    } else {
                        this.uploadForm.files.forEach((file, idx) => {
                            formData.append(`files[${idx}]`, file);
                        });
                    }

                    this.$axios.post('{{ route("admin.club_assets.bulk_upload") }}', formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            },
                            onUploadProgress: (progressEvent) => {
                                if (progressEvent.total) {
                                    this.uploadHttpProgress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                                }
                            }
                        })
                        .then(res => {
                            const batchId = res.data.batch_id;
                            this.currentBatchTracking = res.data.batch;
                            this.activeBatch = res.data.batch;

                            this.$emitter.emit('add-flash', {
                                type: 'success',
                                message: res.data.message || 'Upload received and batch queued.'
                            });

                            // Clear inputs
                            this.uploadForm.files = [];
                            this.uploadForm.zipFile = null;

                            // Start Polling batch status
                            this.startBatchPolling(batchId);
                        })
                        .catch(err => {
                            const errorMsg = err.response?.data?.message || 'Upload failed. Please check file sizes and formats.';
                            this.$emitter.emit('add-flash', {
                                type: 'error',
                                message: errorMsg
                            });
                        })
                        .finally(() => {
                            this.uploading = false;
                        });
                },

                startBatchPolling(batchId) {
                    clearInterval(this.pollInterval);

                    this.pollInterval = setInterval(() => {
                        this.$axios.get(`{{ url('admin/club-assets/batch-status') }}/${batchId}`)
                            .then(res => {
                                const batch = res.data.batch;
                                this.currentBatchTracking = batch;
                                this.activeBatch = batch;

                                if (batch.finished) {
                                    clearInterval(this.pollInterval);
                                    this.fetchAssets(1);
                                    if (batch.status === 'completed') {
                                        this.$emitter.emit('add-flash', {
                                            type: 'success',
                                            message: `Batch complete! ${batch.processed_jobs} jobs processed successfully.`
                                        });
                                    } else if (batch.status === 'partial_failure') {
                                        this.$emitter.emit('add-flash', {
                                            type: 'warning',
                                            message: `Batch finished with ${batch.failed_jobs} failed items.`
                                        });
                                    }
                                }
                            })
                            .catch(err => {
                                clearInterval(this.pollInterval);
                            });
                    }, 1500);
                },

                finishBatchTracking() {
                    this.uploadDialogVisible = false;
                    this.currentBatchTracking = null;
                    this.fetchAssets(1);
                },

                getBatchStatusIcon(status) {
                    switch (status) {
                        case 'completed':
                            return 'pi pi-check text-green-400 text-2xl';
                        case 'partial_failure':
                            return 'pi pi-exclamation-triangle text-amber-400 text-2xl';
                        case 'failed':
                            return 'pi pi-times-circle text-red-400 text-2xl';
                        default:
                            return 'pi pi-spin pi-spinner text-indigo-400 text-2xl';
                    }
                },

                getBatchStatusTitle(status) {
                    switch (status) {
                        case 'completed':
                            return 'All Jobs Completed Successfully!';
                        case 'partial_failure':
                            return 'Completed with Some Warnings / Failed Items';
                        case 'failed':
                            return 'Batch Upload Failed';
                        default:
                            return 'Processing Media Batch...';
                    }
                },

                getBatchBadgeClass(status) {
                    switch (status) {
                        case 'completed':
                            return 'bg-green-500/20 text-green-400';
                        case 'processing':
                            return 'bg-indigo-500/20 text-indigo-400 animate-pulse';
                        case 'partial_failure':
                            return 'bg-amber-500/20 text-amber-400';
                        case 'failed':
                            return 'bg-red-500/20 text-red-400';
                        default:
                            return 'bg-gray-500/20 text-gray-400';
                    }
                },

                // Batches History Drawer
                openBatchesDrawer() {
                    this.batchesDrawerVisible = true;
                    this.fetchBatchesList();
                },

                fetchBatchesList() {
                    this.batchesLoading = true;
                    this.$axios.get('{{ route("admin.club_assets.batches") }}')
                        .then(res => {
                            this.batchesList = res.data.batches || [];
                        })
                        .finally(() => {
                            this.batchesLoading = false;
                        });
                },

                showBatchModal(batch) {
                    this.currentBatchTracking = batch;
                    this.uploadDialogVisible = true;
                    if (!batch.finished) {
                        this.startBatchPolling(batch.id);
                    }
                }
            },

            mounted() {
                this.fetchAssets(1);
            },

            beforeUnmount() {
                clearInterval(this.pollInterval);
                clearTimeout(this.searchTimeout);
            }
        });
    </script>
    @endPushOnce
</x-admin::layouts>