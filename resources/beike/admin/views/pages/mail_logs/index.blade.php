@extends('admin::layouts.master')

@section('title', __('admin/common.mail_logs_index'))

@section('page-title-back', true)

@section('content')
  @if ($errors->has('error'))
    <x-admin-alert type="danger" msg="{{ $errors->first('error') }}" class="mt-4"/>
  @endif

  <div class="card h-min-600">
    <div class="card-body">
      <!-- 统计卡片 -->
      <div class="row mb-4">
        <div class="col-md-3">
          <div class="card bg-primary text-white border-0">
            <div class="card-body">
              <div class="d-flex justify-content-between">
                <div>
                  <h6 class="card-title">{{ __('admin/mail_log.total_count') }}</h6>
                  <h4>{{ $total_count }}</h4>
                </div>
                <div class="align-self-center">
                  <i class="bi bi-envelope fs-2"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card bg-success text-white border-0">
            <div class="card-body">
              <div class="d-flex justify-content-between">
                <div>
                  <h6 class="card-title">{{ __('admin/mail_log.sent_count') }}</h6>
                  <h4>{{ $sent_count }}</h4>
                </div>
                <div class="align-self-center">
                  <i class="bi bi-check-circle fs-2"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card bg-danger text-white border-0">
            <div class="card-body">
              <div class="d-flex justify-content-between">
                <div>
                  <h6 class="card-title">{{ __('admin/mail_log.failed_count') }}</h6>
                  <h4>{{ $failed_count }}</h4>
                </div>
                <div class="align-self-center">
                  <i class="bi bi-x-circle fs-2"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card bg-warning text-white border-0">
            <div class="card-body">
              <div class="d-flex justify-content-between">
                <div>
                  <h6 class="card-title">{{ __('admin/mail_log.pending_count') }}</h6>
                  <h4>{{ $pending_count }}</h4>
                </div>
                <div class="align-self-center">
                  <i class="bi bi-clock fs-2"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- 筛选表单 -->
      <div class="bg-light p-4 mb-3 rounded-3">
        <form method="GET" action="{{ admin_route('mail_logs.index') }}" class="row g-3">
          <div class="col-md-2">
            <label class="form-label">{{ __('admin/mail_log.filter_status') }}</label>
            <select name="status" class="form-select">
              <option value="">{{ __('admin/mail_log.all_status') }}</option>
              @foreach($status_options as $key => $value)
                <option value="{{ $key }}" {{ ($filters['status'] ?? '') == $key ? 'selected' : '' }}>
                  {{ $value }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2">
            <label class="form-label">{{ __('admin/mail_log.filter_mail_type') }}</label>
            <select name="mail_type" class="form-select">
              <option value="">{{ __('admin/mail_log.all_types') }}</option>
              @foreach($mail_type_options as $key => $value)
                <option value="{{ $key }}" {{ ($filters['mail_type'] ?? '') == $key ? 'selected' : '' }}>
                  {{ $value }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2">
            <label class="form-label">{{ __('admin/mail_log.filter_transport') }}</label>
            <select name="transport" class="form-select">
              <option value="">{{ __('admin/mail_log.all_transports') }}</option>
              @foreach($transport_options as $key => $value)
                <option value="{{ $key }}" {{ ($filters['transport'] ?? '') == $key ? 'selected' : '' }}>
                  {{ $value }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2">
            <label class="form-label">{{ __('admin/mail_log.filter_recipient') }}</label>
            <input type="text" name="recipient" class="form-control" placeholder="{{ __('admin/mail_log.recipient_placeholder') }}"
                   value="{{ $filters['recipient'] ?? '' }}">
          </div>
          <div class="col-md-2">
            <label class="form-label">{{ __('admin/mail_log.filter_start_date') }}</label>
            <input type="date" name="start_date" class="form-control"
                   value="{{ $filters['start_date'] ?? '' }}">
          </div>
          <div class="col-md-2">
            <label class="form-label">{{ __('admin/mail_log.filter_end_date') }}</label>
            <input type="date" name="end_date" class="form-control"
                   value="{{ $filters['end_date'] ?? '' }}">
          </div>
          <div class="col-md-2">
            <label class="form-label">&nbsp;</label>
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary">{{ __('admin/mail_log.filter_submit') }}</button>
              <a href="{{ admin_route('mail_logs.index') }}" class="btn btn-outline-secondary">{{ __('admin/mail_log.filter_reset') }}</a>
            </div>
          </div>
        </form>
      </div>

      <!-- 批量操作 -->
      <div class="d-flex justify-content-between mb-3">
        <div>
          <button type="button" class="btn btn-outline-danger btn-sm" onclick="batchDelete()" disabled id="batch-delete-btn">
            <i class="bi bi-trash"></i> {{ __('admin/mail_log.batch_delete') }}
          </button>
        </div>
        <div>
          <span class="text-muted">{{ __('admin/mail_log.total_records', ['count' => $mail_logs->total()]) }}</span>
        </div>
      </div>

      <!-- 邮件记录表格 -->
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th width="50">
                <input type="checkbox" id="select-all" onchange="selectAll(this)">
              </th>
              <th>ID</th>
              <th>{{ __('admin/mail_log.to_email') }}</th>
              <th>{{ __('admin/mail_log.subject') }}</th>
              <th>{{ __('admin/mail_log.mail_type') }}</th>
              <th>{{ __('admin/mail_log.transport') }}</th>
              <th>{{ __('admin/mail_log.status') }}</th>
              <th>{{ __('admin/mail_log.sent_at') }}</th>
              <th>{{ __('admin/mail_log.created_at') }}</th>
              <th>{{ __('common.action') }}</th>
            </tr>
          </thead>
          <tbody>
            @forelse($mail_logs as $log)
              <tr class="cursor-pointer row-link" data-to-url="{{ admin_route("mail_logs.show", [$log['id']]) }}">
                <td onclick="event.stopPropagation();">
                  <input type="checkbox" class="record-checkbox" value="{{ $log->id }}"
                         onchange="updateBatchButtons()">
                </td>
                <td>
                  <div>
                    {{ $log->id }}
                  </div>
                </td>
                <td>
                  <div>
                    <strong>{{ $log->to_email }}</strong>
                    @if($log->to_name)
                      <br><small class="text-muted">{{ $log->to_name }}</small>
                    @endif
                  </div>
                </td>
                <td>
                  <div title="{{ $log->subject }}">
                    {{ Str::limit($log->subject, 50) }}
                  </div>
                </td>
                <td>
                  <span class="badge bg-info">{{ $log->mail_type_text ?? $log->mail_type }}</span>
                </td>
                <td>
                  <span class="badge bg-secondary">{{ $log->transport_text ?? $log->transport }}</span>
                </td>
                <td>
                  <span class="badge bg-{{ $log->status === 'sent' ? 'success' : ($log->status === 'failed' ? 'danger' : 'warning') }}">
                    {{ $log->status_text ?? $log->status }}
                  </span>
                </td>
                <td>
                  {{ $log->sent_at ? $log->sent_at->format('Y-m-d H:i:s') : '-' }}
                </td>
                <td>
                  {{ $log->created_at->format('Y-m-d H:i:s') }}
                </td>
                <td>
                  <div class="btn-group btn-group-sm">
                    <a href="{{ admin_route('mail_logs.show', $log->id) }}"
                       class="btn btn-default" title="{{ __('admin/mail_log.view_detail') }}">
                      <i class="bi bi-eye"></i>
                    </a>
{{--                    @if($log->status === 'failed')--}}
{{--                      <button type="button" class="btn btn-outline-warning"--}}
{{--                              onclick="resendMail({{ $log->id }})" title="{{ __('admin/mail_log.resend') }}">--}}
{{--                        <i class="bi bi-arrow-clockwise"></i>--}}
{{--                      </button>--}}
{{--                    @endif--}}
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="9" class="text-center py-4">
                  <div class="text-muted">
                    <i class="bi bi-inbox fs-1"></i>
                    <p class="mt-2">{{ __('admin/mail_log.no_records') }}</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{ $mail_logs->withQueryString()->links('admin::vendor/pagination/bootstrap-4') }}
    </div>
  </div>
@endsection

@push('footer')
<script>
// 全选/取消全选
function selectAll(checkbox) {
  const checkboxes = document.querySelectorAll('.record-checkbox');
  checkboxes.forEach(cb => cb.checked = checkbox.checked);
  updateBatchButtons();
}

// 更新批量操作按钮状态
function updateBatchButtons() {
  const checkedBoxes = document.querySelectorAll('.record-checkbox:checked');
  const batchDeleteBtn = document.getElementById('batch-delete-btn');
  if (batchDeleteBtn) {
    batchDeleteBtn.disabled = checkedBoxes.length === 0;
  }
}

// 批量删除
function batchDelete() {
  const checkedBoxes = document.querySelectorAll('.record-checkbox:checked');
  const ids = Array.from(checkedBoxes).map(cb => cb.value);

  if (ids.length === 0) {
    layer.msg('{{ __('admin/mail_log.please_select_records') }}');
    return;
  }

  layer.confirm('{{ __('admin/mail_log.batch_delete_confirm') }}', {icon: 3, title: '{{ __('common.text_hint') }}'}, (index) => {
    axios.delete('{{ admin_route("mail_logs.destroy") }}', {
      data: { ids: ids }
    }).then(res => {
      layer.msg(res.data.message);
      if (res.data.status) {
        location.reload();
      }
    }).catch(err => {
      layer.msg('{{ __('admin/mail_log.delete_failed') }}');
    });
    layer.close(index);
  });
}

// 删除单条记录
function deleteRecord(id) {
  layer.confirm('{{ __('admin/mail_log.delete_confirm') }}', {icon: 3, title: '{{ __('common.text_hint') }}'}, (index) => {
    axios.delete('{{ admin_route("mail_logs.destroy") }}', {
      data: { ids: [id] }
    }).then(res => {
      layer.msg(res.data.message);
      if (res.data.status) {
        location.reload();
      }
    }).catch(err => {
      layer.msg('{{ __('admin/mail_log.delete_failed') }}');
    });
    layer.close(index);
  });
}

// 重新发送邮件
function resendMail(id) {
  layer.confirm('{{ __('admin/mail_log.resend_confirm') }}', {icon: 3, title: '{{ __('common.text_hint') }}'}, (index) => {
    axios.post(`{{ admin_route("mail_logs.index") }}/${id}/resend`).then(res => {
      layer.msg(res.data.message);
      if (res.data.status) {
        location.reload();
      }
    }).catch(err => {
      layer.msg('{{ __('admin/mail_log.resend_failed') }}');
    });
    layer.close(index);
  });
}

// 清理旧记录
function cleanupOldRecords() {
  layer.prompt({
    title: '{{ __('admin/mail_log.cleanup_title') }}',
    formType: 0,
    value: '30',
    content: '{{ __('admin/mail_log.cleanup_confirm') }}'
  }, (value, index) => {
    axios.post('{{ admin_route("mail_logs.cleanup") }}', {
      days: value
    }).then(res => {
      layer.msg(res.data.message);
      if (res.data.status) {
        location.reload();
      }
    }).catch(err => {
      layer.msg('{{ __('admin/mail_log.cleanup_failed') }}');
    });
    layer.close(index);
  });
}

// 统计信息
function showStatistics() {
  layer.msg('{{ __('admin/mail_log.statistics_developing') }}');
}
</script>
@endpush
