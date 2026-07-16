<div
    class="overflow-x-auto"
    style="
        scrollbar-width: thin;
        max-width: 100%;
        border: 1px solid var(--border);
        border-radius: 8px;
    "
>
    <!-- Table Skeleton -->
    <table class="w-full text-left border-collapse">
        <thead>
            <tr
                class="border-b"
                style="
                    background-color: var(--bg-subtle);
                    border-color: var(--border);
                "
            >
                <th
                    v-for="n in 5"
                    :key="'head_' + n"
                    class="p-3"
                >
                    <div class="flex items-center gap-2">
                        <div
                            class="h-3 w-20 rounded shimmer"
                        ></div>

                        <i
                            class="pi pi-sort-alt text-xs opacity-40"
                            style="color: var(--text-muted)"
                        ></i>

                        <i
                            class="pi pi-filter text-xs opacity-40"
                            style="color: var(--text-muted)"
                        ></i>
                    </div>
                </th>
            </tr>
        </thead>

        <tbody>
            <tr
                v-for="row in 6"
                :key="'row_' + row"
                class="border-b"
                style="border-color: var(--border)"
            >
                <td
                    v-for="col in 5"
                    :key="'cell_' + row + '_' + col"
                    class="p-3"
                >
                    <div
                        class="h-3 rounded shimmer"
                        :class="{
                            'w-10': col % 5 === 0,
                            'w-16': col % 4 === 0,
                            'w-20': col % 3 === 0,
                            'w-24': col % 2 === 0,
                            'w-32': col % 1 === 0
                        }"
                    ></div>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Pagination Skeleton -->
    <div
        class="flex items-center justify-center gap-5 px-4 py-3"
    >
        <div class="w-4 h-4 rounded shimmer"></div>
        <div class="w-4 h-4 rounded shimmer"></div>

        <div class="w-10 h-10 rounded-full shimmer"></div>

        <div class="w-3 h-4 rounded shimmer"></div>
        <div class="w-3 h-4 rounded shimmer"></div>
    </div>
</div>
