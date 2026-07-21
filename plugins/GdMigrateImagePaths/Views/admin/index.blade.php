@extends('admin::layouts.master')

@section('title', trans('GdMigrateImagePaths::migration.page_title'))

@section('content')
<style>
/* 图片路径迁移工具样式 */

[v-cloak] {
    display: none;
}

/* 步骤指引 */
.steps {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
}

.steps::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 0;
    right: 0;
    height: 2px;
    background: #e0e0e0;
    z-index: 0;
}

.step {
    flex: 1;
    text-align: center;
    position: relative;
    z-index: 1;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e0e0e0;
    color: #666;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-bottom: 8px;
    transition: all 0.3s;
}

.step.active .step-number {
    background: #007bff;
    color: white;
}

.step.completed .step-number {
    background: #28a745;
    color: white;
}

.step-title {
    font-size: 14px;
    color: #666;
}

.step.active .step-title {
    color: #007bff;
    font-weight: 500;
}

.step.completed .step-title {
    color: #28a745;
}

/* 统计卡片 */
.stat-card {
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    text-align: center;
    border: 1px solid #e0e0e0;
}

.stat-value {
    font-size: 32px;
    font-weight: bold;
    color: #333;
    margin-bottom: 8px;
}

.stat-card.text-success .stat-value {
    color: #28a745;
}

.stat-card.text-warning .stat-value {
    color: #ffc107;
}

.stat-card.text-danger .stat-value {
    color: #dc3545;
}

.stat-label {
    font-size: 14px;
    color: #666;
}

/* 表格样式 */
.table-responsive {
    margin-top: 20px;
}

.table th {
    background: #f8f9fa;
    font-weight: 600;
}

.table code {
    background: #f8f9fa;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 12px;
}

/* 徽章样式 */
.badge {
    padding: 4px 8px;
    font-size: 12px;
}

.badge.bg-primary {
    background-color: #007bff !important;
}

.badge.bg-success {
    background-color: #28a745 !important;
}

.badge.bg-warning {
    background-color: #ffc107 !important;
    color: #000;
}

/* 警告框样式 */
.alert-warning {
    background-color: #fff3cd;
    border-color: #ffc107;
}

.alert-warning .alert-heading {
    color: #856404;
}

/* 加载动画 */
.spinner-border-lg {
    width: 3rem;
    height: 3rem;
}

/* 响应式 */
@media (max-width: 768px) {
    .steps {
        flex-direction: column;
    }

    .steps::before {
        display: none;
    }

    .step {
        margin-bottom: 20px;
    }

    .stat-card {
        margin-bottom: 15px;
    }
}
</style>

<div id="migration-app" v-cloak>
    <!-- 警告提示框 -->
    <div class="alert alert-warning alert-dismissible fade show" role="alert" style="border-left: 4px solid #ffc107;">
        <h5 class="alert-heading">
            <i class="bi bi-exclamation-triangle-fill"></i>
            {{ trans('GdMigrateImagePaths::migration.warning_title') }}
        </h5>
        <hr>

        <!-- 适用范围 -->
        <p class="mb-2"><strong>{{ trans('GdMigrateImagePaths::migration.warning_scope') }}</strong></p>

        <!-- 为什么需要使用 -->
        <p class="mb-1"><strong>{{ trans('GdMigrateImagePaths::migration.warning_reason_title') }}</strong></p>
        <p class="mb-3">{{ trans('GdMigrateImagePaths::migration.warning_reason_desc') }}</p>

        <!-- 使用步骤 -->
        <p class="mb-1"><strong>{{ trans('GdMigrateImagePaths::migration.warning_step_title') }}</strong></p>
        <p class="mb-1">{{ trans('GdMigrateImagePaths::migration.warning_step_1') }}</p>
        <p class="mb-1">{{ trans('GdMigrateImagePaths::migration.warning_step_2') }}</p>
        <p class="mb-1">{{ trans('GdMigrateImagePaths::migration.warning_step_3') }}</p>
        <p class="mb-1">{{ trans('GdMigrateImagePaths::migration.warning_step_4') }}</p>
        <p class="mb-3">{{ trans('GdMigrateImagePaths::migration.warning_step_5') }}</p>

        <!-- 警告 -->
        <p class="mb-0 text-danger"><strong>{{ trans('GdMigrateImagePaths::migration.warning_consequence') }}</strong></p>
    </div>

    <!-- 步骤指引 -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="steps">
                <div class="step" :class="{ active: currentStep === 0, completed: currentStep > 0 }">
                    <div class="step-number">1</div>
                    <div class="step-title">{{ trans('GdMigrateImagePaths::migration.step_scan') }}</div>
                </div>
                <div class="step" :class="{ active: currentStep === 1, completed: currentStep > 1 }">
                    <div class="step-number">2</div>
                    <div class="step-title">{{ trans('GdMigrateImagePaths::migration.step_preview') }}</div>
                </div>
                <div class="step" :class="{ active: currentStep === 2, completed: currentStep > 2 }">
                    <div class="step-number">3</div>
                    <div class="step-title">{{ trans('GdMigrateImagePaths::migration.step_execute') }}</div>
                </div>
                <div class="step" :class="{ active: currentStep === 3 }">
                    <div class="step-number">4</div>
                    <div class="step-title">{{ trans('GdMigrateImagePaths::migration.step_report') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- 步骤 1: 扫描数据库 -->
    <div class="card" v-if="currentStep === 0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ trans('GdMigrateImagePaths::migration.step_scan') }}</h5>
            <button class="btn btn-primary" @click="scanDatabase" :disabled="scanning">
                <i class="bi bi-search"></i>
                <span v-if="!scanning">{{ trans('GdMigrateImagePaths::migration.scan_button') }}</span>
                <span v-else>{{ trans('GdMigrateImagePaths::migration.loading') }}</span>
            </button>
        </div>
        <div class="card-body">
            <div v-if="!scanResult" class="alert alert-info">
                {{ trans('GdMigrateImagePaths::migration.scan_info') }}
            </div>

            <div v-if="scanResult">
                <!-- 如果没有找到数据 -->
                <div v-if="scanResult.tables.length === 0" class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    {{ trans('GdMigrateImagePaths::migration.scan_no_data') }}
                </div>

                <!-- 如果找到了数据 -->
                <div v-else>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="stat-value">@{{ scanResult.tables.length }}</div>
                                <div class="stat-label">{{ trans('GdMigrateImagePaths::migration.scan_found_tables') }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="stat-value">@{{ scanResult.totalFields }}</div>
                                <div class="stat-label">{{ trans('GdMigrateImagePaths::migration.scan_found_fields') }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="stat-value">@{{ scanResult.totalRecords }}</div>
                                <div class="stat-label">{{ trans('GdMigrateImagePaths::migration.scan_found_records') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" @change="toggleAllTables" v-model="allTablesSelected">
                                    </th>
                                    <th>{{ trans('GdMigrateImagePaths::migration.table_name') }}</th>
                                    <th>{{ trans('GdMigrateImagePaths::migration.field_name') }}</th>
                                    <th>{{ trans('GdMigrateImagePaths::migration.field_type') }}</th>
                                    <th>{{ trans('GdMigrateImagePaths::migration.match_count') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="table in scanResult.tables">
                                    <tr v-for="(field, index) in table.fields" :key="table.name + '-' + field.name">
                                        <td>
                                            <input type="checkbox" :value="{ table: table.name, field: field.name, type: field.type }" v-model="selectedFields">
                                        </td>
                                        <td>@{{ table.name }}</td>
                                        <td>@{{ field.name }}</td>
                                        <td>
                                            <span class="badge" :class="getTypeBadgeClass(field.type)">
                                                @{{ getTypeLabel(field.type) }}
                                            </span>
                                        </td>
                                        <td>@{{ field.matchCount }}</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <button class="btn btn-primary" @click="nextStep" :disabled="selectedFields.length === 0">
                            {{ trans('GdMigrateImagePaths::migration.btn_next') }}
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 步骤 2: 预览迁移 -->
    <div class="card" v-if="currentStep === 1">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ trans('GdMigrateImagePaths::migration.step_preview') }}</h5>
            <button class="btn btn-secondary" @click="loadPreview" :disabled="previewing">
                <i class="bi bi-arrow-clockwise"></i>
                {{ trans('GdMigrateImagePaths::migration.btn_refresh') }}
            </button>
        </div>
        <div class="card-body">
            <div v-if="previewing" class="text-center py-5">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">{{ trans('GdMigrateImagePaths::migration.loading') }}</span>
                </div>
            </div>

            <div v-if="!previewing && previewData.length > 0">
                <p class="text-muted">{{ trans('GdMigrateImagePaths::migration.preview_sample') }}</p>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ trans('GdMigrateImagePaths::migration.preview_table') }}</th>
                                <th>{{ trans('GdMigrateImagePaths::migration.preview_field') }}</th>
                                <th>{{ trans('GdMigrateImagePaths::migration.preview_old_path') }}</th>
                                <th>{{ trans('GdMigrateImagePaths::migration.preview_new_path') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in previewData.slice(0, 10)" :key="index">
                                <td>@{{ item.table }}</td>
                                <td>@{{ item.field }}</td>
                                <td><code>@{{ item.oldPath }}</code></td>
                                <td><code class="text-success">@{{ item.newPath }}</code></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <button class="btn btn-secondary me-2" @click="prevStep">
                        <i class="bi bi-arrow-left"></i>
                        {{ trans('GdMigrateImagePaths::migration.btn_prev') }}
                    </button>
                    <button class="btn btn-primary" @click="confirmExecute">
                        {{ trans('GdMigrateImagePaths::migration.execute_button') }}
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 步骤 3: 执行迁移 -->
    <div class="card" v-if="currentStep === 2">
        <div class="card-header">
            <h5 class="mb-0">{{ trans('GdMigrateImagePaths::migration.step_execute') }}</h5>
        </div>
        <div class="card-body">
            <div v-if="executing" class="text-center py-5">
                <div class="spinner-border spinner-border-lg mb-3" role="status">
                    <span class="visually-hidden">{{ trans('GdMigrateImagePaths::migration.processing') }}</span>
                </div>
                <p class="text-muted">{{ trans('GdMigrateImagePaths::migration.execute_in_progress') }}</p>
            </div>
        </div>
    </div>

    <!-- 步骤 4: 查看报告 -->
    <div class="card" v-if="currentStep === 3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ trans('GdMigrateImagePaths::migration.report_title') }}</h5>
            <div>
                <button class="btn btn-sm btn-outline-primary me-2" @click="exportReport('json')">
                    <i class="bi bi-download"></i>
                    {{ trans('GdMigrateImagePaths::migration.report_export_json') }}
                </button>
                <button class="btn btn-sm btn-outline-primary" @click="exportReport('text')">
                    <i class="bi bi-download"></i>
                    {{ trans('GdMigrateImagePaths::migration.report_export_text') }}
                </button>
            </div>
        </div>
        <div class="card-body">
            <div v-if="report">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-value">@{{ report.summary.totalProcessed }}</div>
                            <div class="stat-label">{{ trans('GdMigrateImagePaths::migration.report_total_processed') }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card text-success">
                            <div class="stat-value">@{{ report.summary.totalUpdated }}</div>
                            <div class="stat-label">{{ trans('GdMigrateImagePaths::migration.report_total_updated') }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card text-warning">
                            <div class="stat-value">@{{ report.summary.totalSkipped }}</div>
                            <div class="stat-label">{{ trans('GdMigrateImagePaths::migration.report_total_skipped') }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card text-danger">
                            <div class="stat-value">@{{ report.summary.totalFailed }}</div>
                            <div class="stat-label">{{ trans('GdMigrateImagePaths::migration.report_total_failed') }}</div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ trans('GdMigrateImagePaths::migration.table_name') }}</th>
                                <th>{{ trans('GdMigrateImagePaths::migration.field_name') }}</th>
                                <th>{{ trans('GdMigrateImagePaths::migration.updated_count') }}</th>
                                <th>{{ trans('GdMigrateImagePaths::migration.skipped_count') }}</th>
                                <th>{{ trans('GdMigrateImagePaths::migration.failed_count') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="detail in report.details" :key="detail.table + '-' + detail.field">
                                <td>@{{ detail.table }}</td>
                                <td>@{{ detail.field }}</td>
                                <td class="text-success">@{{ detail.totalUpdated }}</td>
                                <td class="text-warning">@{{ detail.totalSkipped }}</td>
                                <td class="text-danger">@{{ detail.totalFailed }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <button class="btn btn-primary" @click="resetMigration">
                        <i class="bi bi-arrow-left"></i>
                        {{ trans('GdMigrateImagePaths::migration.btn_back') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('footer')
<script>
// 从后端传递语言变量到前端
const i18n = {
    scanNoDataFound: '{{ trans('GdMigrateImagePaths::migration.scan_no_data_found') }}',
    scanSuccess: '{{ trans('GdMigrateImagePaths::migration.scan_success') }}',
    scanFailed: '{{ trans('GdMigrateImagePaths::migration.scan_failed') }}',
    previewSuccess: '{{ trans('GdMigrateImagePaths::migration.preview_success') }}',
    previewFailed: '{{ trans('GdMigrateImagePaths::migration.preview_failed') }}',
    executeSuccess: '{{ trans('GdMigrateImagePaths::migration.execute_success') }}',
    executeFailed: '{{ trans('GdMigrateImagePaths::migration.execute_failed') }}',
    confirmExecuteTitle: '{{ trans('GdMigrateImagePaths::migration.confirm_execute_title') }}',
    confirmExecuteMessage: '{{ trans('GdMigrateImagePaths::migration.confirm_execute_message') }}',
    confirmButton: '{{ trans('GdMigrateImagePaths::migration.confirm_button') }}',
    cancelButton: '{{ trans('GdMigrateImagePaths::migration.cancel_button') }}',
    noReportToExport: '{{ trans('GdMigrateImagePaths::migration.no_report_to_export') }}',
    typePlain: '{{ trans('GdMigrateImagePaths::migration.type_plain') }}',
    typeJson: '{{ trans('GdMigrateImagePaths::migration.type_json') }}',
    typeSerialized: '{{ trans('GdMigrateImagePaths::migration.type_serialized') }}',
};

new Vue({
    el: '#migration-app',
    data: {
        currentStep: 0,
        scanning: false,
        previewing: false,
        executing: false,
        scanResult: null,
        selectedFields: [],
        allTablesSelected: false,
        previewData: [],
        report: null,
    },
    mounted() {
        // 初始化
    },
    methods: {
        /**
         * 扫描数据库
         */
        async scanDatabase() {
            this.scanning = true;
            try {
                const res = await $http.post('migration/scan');
                console.log('扫描响应:', res); // 调试信息

                // 兼容两种返回格式：success: true 或 status: "success"
                const isSuccess = res.success === true || res.status === 'success';

                if (isSuccess) {
                    this.scanResult = res.data;
                    console.log('扫描结果:', this.scanResult); // 调试信息

                    if (this.scanResult.tables.length === 0) {
                        this.$message.info(i18n.scanNoDataFound);
                    } else {
                        // 默认全选所有字段
                        this.selectAllFields();
                        this.$message.success(res.message || i18n.scanSuccess);
                    }
                } else {
                    this.$message.error(res.message || i18n.scanFailed);
                }
            } catch (error) {
                console.error('扫描错误:', error); // 调试信息
                this.$message.error(i18n.scanFailed + ': ' + error.message);
            } finally {
                this.scanning = false;
            }
        },

        /**
         * 默认全选所有字段
         */
        selectAllFields() {
            this.selectedFields = [];
            if (this.scanResult && this.scanResult.tables) {
                this.scanResult.tables.forEach(table => {
                    table.fields.forEach(field => {
                        this.selectedFields.push({
                            table: table.name,
                            field: field.name,
                            type: field.type
                        });
                    });
                });
                this.allTablesSelected = true;
            }
        },

        /**
         * 加载预览
         */
        async loadPreview() {
            this.previewing = true;
            try {
                const res = await $http.post('migration/preview', {
                    fields: this.selectedFields
                });

                const isSuccess = res.success === true || res.status === 'success';

                if (isSuccess) {
                    this.previewData = res.data.preview;
                    this.$message.success(res.message || i18n.previewSuccess);
                } else {
                    this.$message.error(res.message || i18n.previewFailed);
                }
            } catch (error) {
                this.$message.error(i18n.previewFailed + ': ' + error.message);
            } finally {
                this.previewing = false;
            }
        },

        /**
         * 确认执行迁移
         */
        confirmExecute() {
            this.$confirm(
                i18n.confirmExecuteMessage,
                i18n.confirmExecuteTitle,
                {
                    confirmButtonText: i18n.confirmButton,
                    cancelButtonText: i18n.cancelButton,
                    type: 'warning'
                }
            ).then(() => {
                this.executeMigration();
            }).catch(() => {
                // 用户取消
            });
        },

        /**
         * 执行迁移
         */
        async executeMigration() {
            this.currentStep = 2;
            this.executing = true;

            try {
                const res = await $http.post('migration/execute', {
                    fields: this.selectedFields,
                    batch_size: 1000
                });

                const isSuccess = res.success === true || res.status === 'success';

                if (isSuccess) {
                    this.report = res.data;
                    this.currentStep = 3;
                    this.$message.success(res.message || i18n.executeSuccess);
                } else {
                    this.$message.error(res.message || i18n.executeFailed);
                    this.currentStep = 1;
                }
            } catch (error) {
                this.$message.error(i18n.executeFailed + ': ' + error.message);
                this.currentStep = 1;
            } finally {
                this.executing = false;
            }
        },

        /**
         * 导出报告
         */
        exportReport(format) {
            if (!this.report) {
                this.$message.error(i18n.noReportToExport);
                return;
            }

            const url = `migration/export/${this.report.id}?format=${format}`;
            window.location.href = url;
        },

        /**
         * 切换所有表选择
         */
        toggleAllTables() {
            if (this.allTablesSelected) {
                // 全选
                this.selectAllFields();
            } else {
                // 取消全选
                this.selectedFields = [];
            }
        },

        /**
         * 下一步
         */
        async nextStep() {
            if (this.currentStep === 0) {
                // 从扫描到预览
                this.currentStep = 1;
                await this.loadPreview();
            } else if (this.currentStep === 1) {
                // 从预览到执行
                this.confirmExecute();
            }
        },

        /**
         * 上一步
         */
        prevStep() {
            if (this.currentStep > 0) {
                this.currentStep--;
            }
        },

        /**
         * 重置迁移
         */
        resetMigration() {
            this.currentStep = 0;
            this.scanResult = null;
            this.selectedFields = [];
            this.previewData = [];
            this.report = null;
        },

        /**
         * 获取类型徽章样式
         */
        getTypeBadgeClass(type) {
            switch (type) {
                case 'json':
                    return 'bg-primary';
                case 'serialized':
                    return 'bg-warning';
                default:
                    return 'bg-success';
            }
        },

        /**
         * 获取类型标签
         */
        getTypeLabel(type) {
            switch (type) {
                case 'json':
                    return i18n.typeJson;
                case 'serialized':
                    return i18n.typeSerialized;
                default:
                    return i18n.typePlain;
            }
        }
    },
    watch: {
        selectedFields(val) {
            // 检查是否全选
            if (!this.scanResult) return;

            let totalFields = 0;
            this.scanResult.tables.forEach(table => {
                totalFields += table.fields.length;
            });

            this.allTablesSelected = val.length === totalFields;
        }
    }
});
</script>
@endpush
