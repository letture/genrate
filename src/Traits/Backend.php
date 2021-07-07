<?php
namespace Lettrue\Genrate\Traits;

use Lettrue\Genrate\Http\Requests\BaseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Lettrue\Genrate\Http\JsonResponse;

trait Backend
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // sql 语句条件
        if ($this->order && $this->order_by) {
            $list = $this->model->where($this->where)->orderBy($this->order, $this->order_by)->orderByDesc('id')->paginate();
        } else {
            $list = $this->model->where($this->where)->orderByDesc('id')->paginate();
        }
        return JsonResponse::success($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $params = $request->all();
        $validate = $this->checkRule($params);
        if ($validate !== true) {
            return $validate;
        }
        $data= collect($params)->whereNotNull()->all();
        $this->model->create($data);
        return JsonResponse::success([], '创建成功');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $info = $this->model->find($id);
        return JsonResponse::success($info);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $info = $this->model->findOrfail($id);
        $params = $request->all();
        $validate = $this->checkRule($params);
        if ($validate !== true) {
            return $validate;
        }
        $data= collect($params)->whereNotNull()->all();
        $info->update($data);
        return JsonResponse::success([], '修改成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $info = $this->model->findOrFail($id);
        $info->destroy($id);
        return JsonResponse::success([], '删除成功');
    }

    protected function getRules($scene = ''): array
    {
        $rules = $this->request->rules();
        $scenes = $this->request->scenes;
        if ($scene && isset($scenes[$scene])) {
            $sceneFields = is_array($scenes[$scene])
                ? $scenes[$scene] : explode(',', $scenes[$scene]);
            $newRules = [];
            foreach ($sceneFields as $field) {
                if (array_key_exists($field, $rules)) {
                    $newRules[$field] = $rules[$field];
                }
            }
            return $newRules;
        }
        return $rules;
    }

    protected function checkRule($params)
    {
        if ($this->request instanceof BaseRequest) {
            // 处理rules
            $action = getControllerAndFunction();
            $rules = $this->getRules();
            if (empty($rules)) return true;
            $validator = Validator::make($params, $this->getRules($action['method']), $this->request->messages());
            if ($errors = $validator->fails()) {
                return JsonResponse::error($validator->errors()->first());
            }
        }

        return true;
    }
}
