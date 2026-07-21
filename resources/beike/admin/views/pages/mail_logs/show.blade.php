@extends('admin::layouts.master')

@section('title', __('admin/mail_log.detail'))

@section('page-title-back', true)

@section('content')
  @section('page-title-right')
    @if($mail_log->status === 'failed')
      <button type="button" class="btn btn-outline-info" onclick="app.resend()">
        <i class="bi bi-arrow-clockwise"></i> {{ __('admin/mail_log.resend') }}
      </button>
    @endif
  @endsection

  <div class="row">
    <!-- 基本信息 -->
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">{{ __('admin/mail_log.basic_info') }}</h5>
        </div>
        <div class="card-body">
          <div class="row mb-3">
            <div class="col-sm-3"><strong>{{ __('admin/mail_log.subject') }}：</strong></div>
            <div class="col-sm-9">{{ $mail_log->subject }}</div>
          </div>
          <div class="row mb-3">
            <div class="col-sm-3"><strong>{{ __('admin/mail_log.to_email') }}：</strong></div>
            <div class="col-sm-9">
              {{ $mail_log->to_email }}
              @if($mail_log->to_name)
                <span class="text-muted">({{ $mail_log->to_name }})</span>
              @endif
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-sm-3"><strong>{{ __('admin/mail_log.from_email') }}：</strong></div>
            <div class="col-sm-9">
              {{ $mail_log->from_email ?: __('admin/mail_log.system_default') }}
              @if($mail_log->from_name)
                <span class="text-muted">({{ $mail_log->from_name }})</span>
              @endif
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-sm-3"><strong>{{ __('admin/mail_log.mail_type') }}：</strong></div>
            <div class="col-sm-9">
              <span class="badge bg-info">{{ $mail_log->mail_type_text }}</span>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-sm-3"><strong>{{ __('admin/mail_log.transport') }}：</strong></div>
            <div class="col-sm-9">
              <span class="badge bg-secondary">{{ $mail_log->transport_text }}</span>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-sm-3"><strong>发送状态：</strong></div>
            <div class="col-sm-9">
              <span class="badge bg-{{ $mail_log->status_color }}">{{ $mail_log->status_text }}</span>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-sm-3"><strong>创建时间：</strong></div>
            <div class="col-sm-9">{{ $mail_log->created_at->format('Y-m-d H:i:s') }}</div>
          </div>
          <div class="row mb-3">
            <div class="col-sm-3"><strong>发送时间：</strong></div>
            <div class="col-sm-9">
              {{ $mail_log->sent_at ? $mail_log->sent_at->format('Y-m-d H:i:s') : '未发送' }}
            </div>
          </div>
          @if($mail_log->error_message)
            <div class="row mb-3">
              <div class="col-sm-3"><strong>错误信息：</strong></div>
              <div class="col-sm-9">
                <div class="alert alert-danger">
                  {{ $mail_log->error_message }}
                </div>
              </div>
            </div>
          @endif
        </div>
      </div>

      <!-- 邮件内容 -->
      @if($mail_log->content)
        <div class="card mt-4">
          <div class="card-header">
            <h5 class="card-title mb-0">邮件内容</h5>
          </div>
          <div class="card-body">
            <div class="border p-3" style="max-height: 500px; overflow-y: auto;">
              {!! $mail_log->content !!}
            </div>
          </div>
        </div>
      @endif
    </div>

    <!-- 侧边栏信息 -->
    <div class="col-md-4">
      <!-- 附件信息 -->
      @if($mail_log->attachments && count($mail_log->attachments) > 0)
        <div class="card">
          <div class="card-header">
            <h6 class="card-title mb-0">附件信息</h6>
          </div>
          <div class="card-body">
            @foreach($mail_log->attachments as $attachment)
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                  <i class="bi bi-paperclip"></i>
                  {{ $attachment['name'] ?? '未知文件' }}
                </div>
                <small class="text-muted">
                  {{ isset($attachment['size']) ? number_format($attachment['size'] / 1024, 2) . ' KB' : '' }}
                </small>
              </div>
            @endforeach
          </div>
        </div>
      @endif

      <!-- 邮件头信息 -->
      @if($mail_log->headers && count($mail_log->headers) > 0)
        <div class="card">
          <div class="card-header">
            <h6 class="card-title mb-0">邮件头信息</h6>
          </div>
          <div class="card-body">
            <div class="accordion" id="headersAccordion">
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                          data-bs-target="#headersCollapse">
                    查看详细头信息
                  </button>
                </h2>
                <div id="headersCollapse" class="accordion-collapse collapse"
                     data-bs-parent="#headersAccordion">
                  <div class="accordion-body">
                    <div style="max-height: 300px; overflow-y: auto;">
                      @foreach($mail_log->headers as $key => $value)
                        <div class="mb-2">
                          <strong>{{ $key }}:</strong>
                          <div class="text-muted small">{{ $value }}</div>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endif

      <!-- 操作历史 -->
      <div class="card mt-3">
        <div class="card-header">
          <h6 class="card-title mb-0">操作历史</h6>
        </div>
        <div class="card-body">
          <div class="timeline">
            <div class="timeline-item">
              <div class="timeline-marker bg-primary"></div>
              <div class="timeline-content">
                <h6 class="timeline-title">邮件创建</h6>
                <p class="timeline-text">{{ $mail_log->created_at->format('Y-m-d H:i:s') }}</p>
              </div>
            </div>
            @if($mail_log->sent_at)
              <div class="timeline-item">
                <div class="timeline-marker bg-success"></div>
                <div class="timeline-content">
                  <h6 class="timeline-title">邮件发送</h6>
                  <p class="timeline-text">{{ $mail_log->sent_at->format('Y-m-d H:i:s') }}</p>
                </div>
              </div>
            @endif
            @if($mail_log->status === 'failed')
              <div class="timeline-item">
                <div class="timeline-marker bg-danger"></div>
                <div class="timeline-content">
                  <h6 class="timeline-title">发送失败</h6>
                  <p class="timeline-text">{{ $mail_log->updated_at->format('Y-m-d H:i:s') }}</p>
                </div>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('header')
<style>
.timeline {
  position: relative;
  padding-left: 30px;
}

.timeline::before {
  content: '';
  position: absolute;
  left: 15px;
  top: 0;
  bottom: 0;
  width: 2px;
  background: #dee2e6;
}

.timeline-item {
  position: relative;
  margin-bottom: 20px;
}

.timeline-marker {
  position: absolute;
  left: -22px;
  top: 0;
  width: 12px;
  height: 12px;
  border-radius: 50%;
  border: 2px solid #fff;
}

.timeline-title {
  font-size: 14px;
  font-weight: 600;
  margin-bottom: 5px;
}

.timeline-text {
  font-size: 12px;
  color: #6c757d;
  margin: 0;
}
</style>
@endpush

@push('footer')
<script>
const app = {
  // 重新发送
  resend() {
    layer.confirm('确定要重新发送这封邮件吗？', {icon: 3, title: '提示'}, (index) => {
      axios.post(`{{ admin_route("mail_logs.index") }}/{{ $mail_log->id }}/resend`).then(res => {
        layer.msg(res.data.message);
        if (res.data.status) {
          location.reload();
        }
      });
      layer.close(index);
    });
  },

  // 删除记录
  deleteRecord() {
    layer.confirm('确定要删除这条记录吗？', {icon: 3, title: '提示'}, (index) => {
      axios.delete('{{ admin_route("mail_logs.destroy") }}', {
        data: { ids: [{{ $mail_log->id }}] }
      }).then(res => {
        layer.msg(res.data.message);
        if (res.data.status) {
          location.href = '{{ admin_route("mail_logs.index") }}';
        }
      });
      layer.close(index);
    });
  }
};
</script>
@endpush
