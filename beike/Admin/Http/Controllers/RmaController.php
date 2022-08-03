<?php
/**
 * RmaController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-08-03 21:17:04
 * @modified   2022-08-03 21:17:04
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Models\Rma;
use Beike\Repositories\RmaReasonRepo;
use Beike\Repositories\RmaRepo;
use Exception;
use Illuminate\Http\Request;

class RmaController extends Controller
{
    public function index(Request $request)
    {
        $rmas = RmaRepo::list($request->only('name', 'email', 'telephone', 'product_name', 'model', 'type', 'status'));
        $data = [
            'rmas' => $rmas,
        ];

        return view('admin::pages.rmas.index', $data);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function show(int $id)
    {
        $data = [
            'rma' => RmaRepo::find($id),
            'statuses' => RmaRepo::getStatuses(),
            'types' => RmaRepo::getTypes(),
        ];
        return view('admin::pages.rmas.info', $data);
    }

    public function addHistory(Request $request, int $id)
    {
        RmaRepo::addHistory($id, $request->only('status', 'notify', 'comment'));

         $data = [
            'rma' => RmaRepo::find($id),
            'statuses' => RmaRepo::getStatuses(),
        ];
       return json_success('更新成功', $data);
    }

    public function destroy(int $id): array
    {
        RmaRepo::delete($id);

        return json_success("已成功删除");
    }
}
