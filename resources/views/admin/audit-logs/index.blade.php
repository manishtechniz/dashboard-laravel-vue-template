<x-admin::layouts>
    <div class="page-header">
        <h1 class="page-title">Audit Logs</h1>
        <div class="page-breadcrumb">Home / Audit Logs</div>
    </div>

    <x-admin::datagrid
        :is-multi-row="false"
        ref="auditGrid"
        src="{{ route('admin.audit_logs.index') }}"
    />
</x-admin::layouts>
