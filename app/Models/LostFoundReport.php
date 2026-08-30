<?php

namespace App\Models;

use App\Enums\LostFoundType;
use Database\Factories\LostFoundReportFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LostFoundReport extends Model
{
    /** @use HasFactory<LostFoundReportFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'type',
        'species',
        'colour',
        'markings',
        'identifying_code',
        'location_area',
        'reported_at',
        'person_id',
        'matched_animal_id',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => LostFoundType::class,
            'reported_at' => 'date',
        ];
    }

    /**
     * @return BelongsTo<Person, $this>
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * @return BelongsTo<Animal, $this>
     */
    public function matchedAnimal(): BelongsTo
    {
        return $this->belongsTo(Animal::class, 'matched_animal_id');
    }

    /**
     * @param  Builder<LostFoundReport>  $query
     * @return Builder<LostFoundReport>
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);

        if ($term === '') {
            return $query;
        }

        $like = '%'.$term.'%';

        return $query->where(function (Builder $inner) use ($like): void {
            $inner->where('species', 'like', $like)
                ->orWhere('colour', 'like', $like)
                ->orWhere('markings', 'like', $like)
                ->orWhere('identifying_code', 'like', $like)
                ->orWhere('location_area', 'like', $like)
                ->orWhere('notes', 'like', $like);
        });
    }

    /**
     * @param  Builder<LostFoundReport>  $query
     * @return Builder<LostFoundReport>
     */
    public function scopeOfType(Builder $query, ?string $type): Builder
    {
        $type = trim((string) $type);

        if ($type === '') {
            return $query;
        }

        return $query->where('type', $type);
    }
}
