<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'description', 'category',
        'base_price', 'image', 'is_available'
    ];

    public function branchStocks() {
        return $this->hasMany(BranchStock::class);
    }

    public function transactionItems() {
        return $this->hasMany(TransactionItem::class);
    }
}