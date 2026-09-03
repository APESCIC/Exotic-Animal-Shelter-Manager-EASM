<?php

namespace App\Models;

use Database\Factories\AnimalObservationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnimalObservation extends Model
{
    /** @use HasFactory<AnimalObservationFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'animal_id',
        'observed_on',
        'body',
        'user_id',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'observed_on' => 'date',
        ];
    }

    /**
     * @return BelongsTo<Animal, $this>
     */
    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
