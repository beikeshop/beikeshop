@extends('admin::layouts.master')

@section('title', '售后申请')

@section('content')
  <div class="card mb-4">
    <div class="card-header"><h6 class="card-title">售后申请详情</h6></div>
    <div class="card-body">
      <div class="row">
        <div class="col-4">
          <table class="table table-borderless">
            <tbody>
              <tr>
                <td>ID：</td>
                <td>{{ $rma->id }}</td>
              </tr>
              <tr>
                  <td>客户姓名：</td>
                  <td>{{ $rma->name }}</td>
              </tr>
              <tr>
                  <td>联系电话：</td>
                  <td>{{ $rma->telephone }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col-4">
          <table class="table table-borderless">
            <tbody>
              <tr>
                <td>商品：</td>
                <td>{{ $rma->product_name }}</td>
              </tr>
              <tr>
                <td>型号：</td>
                <td>{{ $rma->model }}</td>
              </tr>
              <tr>
                <td>数量：</td>
                <td>{{ $rma->quantity }}</td>
              </tr>
              <tr>
                <td>退货原因：</td>
                <td>{{ $rma->quantity }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header"><h6 class="card-title">状态</h6></div>
    <div class="card-body" id="app">
      <el-form ref="form" :model="form" :rules="rules" label-width="100px">
        <el-form-item label="当前状态">
          待支付
        </el-form-item>
        <el-form-item label="修改状态" prop="status">
          <el-select size="small" v-model="form.status" placeholder="请选择">
            <el-option
              v-for="item in statuses"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
        </el-form-item>
        {{-- <el-form-item label="通知客户">
          <el-switch v-model="form.notify">
          </el-switch>
        </el-form-item> --}}
        <el-form-item label="备注信息">
          <textarea class="form-control w-max-500" v-model="form.comment"></textarea>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="submitForm('form')">更新状态</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header"><h6 class="card-title">操作历史</h6></div>
    <div class="card-body">
      @foreach ($rma->histories as $history)
      @endforeach
    </div>
  </div>
@endsection

@push('footer')
  <script>
    new Vue({
      el: '#app',

      data: {
        statuses: [],
        rma: @json($rma ?? []),
        form: {
          status: "",
          notify: false,
          comment: '',
        },

        rules: {
          status: [{required: true, message: '请输入用户名', trigger: 'blur'}, ],
        }
      },

      beforeMount() {
        let statuses = @json($statuses ?? []);
        this.statuses = Object.keys(statuses).map(key => {
          return {
            value: key,
            label: statuses[key]
          }
        });
      },

      methods: {
        submitForm(form) {
          this.$refs[form].validate((valid) => {
            if (!valid) {
              layer.msg('请检查表单是否填写正确',()=>{});
              return;
            }

            $http.post(`rmas/history/${this.rma.id}`,this.form).then((res) => {
              console.log(res)
              layer.msg(res.message);
            })
          });
        }
      }
    })
  </script>
@endpush

