
<script src="{{ asset('vendor/vue/2.6.14/vue.js') }}"></script>
<script src="{{ asset('vendor/element-ui/2.15.6/js.js') }}"></script>
<link rel="stylesheet" href="{{ asset('vendor/element-ui/2.15.6/css.css') }}">

<div class="row mt-5" id="bk-stripe-app" v-cloak>
      <div class="col-12 col-md-8">
        <div class="checkout-black">
          <h5 class="checkout-title">卡信息</h5>
          <div class="border px-3 py-2">
            <el-form ref="form" label-position="top" :rules="rules" :model="form" class="form-wrap">
              <el-form-item label="卡号" prop="cardnum">
                <el-input v-model="form.cardnum"></el-input>
              </el-form-item>
              <el-form-item label="截止日期" required>
                <div class="d-flex align-items-center">
                  <el-form-item prop="year">
                    <el-date-picker
                      class="w-auto me-2"
                      v-model="form.year"
                      format="yyyy"
                      value-format="yyyy"
                      type="year"
                      placeholder="选择年">
                    </el-date-picker>
                  </el-form-item>
                  <el-form-item prop="month">
                    <el-date-picker
                      v-model="form.month"
                      class="w-auto"
                      format="MM"
                      value-format="MM"
                      type="month"
                      placeholder="选择月">
                    </el-date-picker>
                  </el-form-item>
                </div>
              </el-form-item>
              <el-form-item label="安全码" prop="cvv">
                <el-input v-model="form.cvv"></el-input>
              </el-form-item>
              <el-form-item>
                <el-checkbox v-model="form.remenber">記住這張卡以備將來使用</el-checkbox>
              </el-form-item>
            </el-form>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-4 right-column">
        <div class="card total-wrap">
          <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">订单结账</h5>
            <span class="rounded-circle bg-primary"></span>
          </div>
          <div class="card-body">
            <ul class="totals">
              <li><span>订单号</span><span>@{{ source.order.number }}</span></li>
              <li><span>运费</span><span>15</span></li>
              <li><span>应付总金额</span><span v-text="source.order.total"></span></li>
            </ul>
            <div class="d-grid gap-2 mt-3">
              <button class="btn btn-primary" type="button" @click="checkedBtnCheckoutConfirm('form')">支付</button>
            </div>
          </div>
        </div>
      </div>
    </div>

  <script>
    new Vue({
      el: '#bk-stripe-app',

      data: {
        form: {
          cardnum: '',
          year: '',
          month: '',
          cvv: '',
          remenber: false,
        },

        source: {
          order: @json($order ?? null),
        },

        rules: {
          cardnum: [{required: true, message: '请输入卡号', trigger: 'blur'}, ],
          cvv: [{required: true, message: '请输入安全码', trigger: 'blur'}, ],
          year: [{required: true, message: '请选择年', trigger: 'blur'}, ],
          month: [{required: true, message: '请选择月', trigger: 'blur'}, ],
        }
      },

      beforeMount () {
      },

      methods: {
        checkedBtnCheckoutConfirm(form) {
          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('请检查表单是否填写正确');
              return;
            }

            console.log(this.form);

            $http.post(`/orders/${this.source.order.number}/pay`, this.form).then((res) => {
              console.log(res)
            })
          });
        }
      }
    })
  </script>

