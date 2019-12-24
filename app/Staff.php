<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

use Carbon\Carbon;

class Staff extends Model
{
    use HasSlug, SearchableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $casts = [ 'is_active' => 'boolean' ];
    
    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('full_name')
            ->saveSlugsTo('slug');
    }

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'staff.full_name' => 5,
            'staff.ippis' => 10,
        ],
        // 'joins' => [
        //     'posts' => ['users.id','posts.user_id'],
        // ],
    ];


    public function user()
    {
        return $this->hasOne(User::class, 'ippis', 'ippis');
    }

    public function ledgers()
    {
        return $this->hasMany(Ledger::class);
    }


    public function monthly_savings()
    {
        return $this->hasMany(MonthlySaving::class, 'ippis', 'ippis');
    }


    public function monthly_savings_payments()
    {
        return $this->hasMany(MonthlySavingsPayment::class, 'ippis', 'ippis');
    }


    public function long_term_loans()
    {
        return $this->hasMany(LongTerm::class, 'ippis', 'ippis');
    }


    public function long_term_payments()
    {
        return $this->hasMany(LongTermPayment::class, 'ippis', 'ippis');
    }


    public function short_term_loans()
    {
        return $this->hasMany(ShortTerm::class);
    }

    public function short_term_payments()
    {
        return $this->hasMany(ShortTermPayment::class, 'ippis', 'ippis');
    }


    public function commodities_loans()
    {
        return $this->hasMany(Commodity::class);
    }

    public function commodities_loans_payments()
    {
        return $this->hasMany(CommodityPayment::class, 'ippis', 'ippis');
    }


    public function member_pay_point()
    {
        return $this->belongsTo(Center::class, 'pay_point', 'id');
    }

    public function toggleStatus() {
        $this->is_active = !$this->is_active;

        if ($this->is_active) {
            $this->deactivation_date = Carbon::now();
        } else {
            $this->activation_date = Carbon::now();
        }

        return $this;
    }
}
