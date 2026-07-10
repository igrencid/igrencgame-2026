<?php
namespace App\Filament\Admin\Resources\VoucherResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Voucher;

/**
 * @property Voucher $resource
 */
class VoucherTransformer extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->resource->toArray();
    }
}
