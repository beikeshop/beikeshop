<div align="center">
  <div class="" style="margin-left: 8px; margin-top: 8px; margin-bottom: 8px; margin-right: 8px;">
    <div style="word-break: break-all;box-sizing:border-box;text-align:center;min-width:320px; max-width:660px; border:1px solid #f6f6f6; background-color:#f7f8fa; margin:auto; padding:20px 0 30px; font-family:'helvetica neue',PingFangSC-Light,arial,'hiragino sans gb','microsoft yahei ui','microsoft yahei',simsun,sans-serif">
      <table style="width:100%;font-weight:300;margin-bottom:10px;border-collapse:collapse">
        <tbody>
          <tr style="font-weight:300">
            <td style="width:3%;max-width:30px;"></td>
            <td style="max-width:600px;">
              <div style="height: 35px;display: block;max-width: 200px;">
                <a href="{{ shop_route('home.index') }}" style="display: block;height: 35px;display: block;max-width: 200px;">
                  <img border="0" src="{{ image_origin(system_setting('base.logo')) }}" style="max-width:100%; max-height: 100%;display">
                </a>
              </div>

              <p style="height:2px;background-color: #fd560f;border: 0;font-size:0;padding:0;width:100%;margin-top:10px;"></p>
              <div style="background-color:#fff; padding:23px 0 20px;box-shadow: 0px 1px 1px 0px rgba(122, 55, 55, 0.2);text-align:left;">
                <table style="width:100%;font-weight:300;margin-bottom:10px;border-collapse:collapse;text-align:left;">
                  @yield('content')
                </table>
              </div>

              <div style="margin-top: 10px; text-align:center; font-size:12px; line-height:18px; color:#999">
                <table style="width:100%;font-weight:300;margin-bottom:10px;border-collapse:collapse">
                  <tbody>
                    <tr style="font-weight:300">
                      <td style="width:3.2%;max-width:30px;"></td>
                      <td style="max-width:540px;">
                        <p style="max-width: 100%; margin:auto;font-size:12px;color:#999;text-align:center;line-height:22px;">
                          {{ config('app.name') }} &copy; {{ date('Y') }} All Rights Reserved
                        </p>
                      </td>
                      <td style="width:3.2%;max-width:30px;"></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </td>
            <td style="width:3%;max-width:30px;"></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
