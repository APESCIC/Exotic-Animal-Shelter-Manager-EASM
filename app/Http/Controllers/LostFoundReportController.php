<?php

namespace App\Http\Controllers;

use App\Enums\LostFoundType;
use App\Http\Requests\StoreLostFoundReportRequest;
use App\Http\Requests\UpdateLostFoundReportRequest;
use App\Models\Animal;
use App\Models\LostFoundReport;
use App\Models\Person;
use App\Services\LostFoundMatcher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LostFoundReportController extends Controller
{
    public function __construct(private LostFoundMatcher $matcher) {}

    public function index(Request $request): View
    {
        $q = $request->string('q')->toString();
        $type = $request->string('type')->toString();

        $reports = LostFoundReport::query()
            ->with(['person', 'matchedAnimal'])
            ->search($q !== '' ? $q : null)
            ->ofType($type !== '' ? $type : null)
            ->orderByDesc('reported_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('lost-found.index', [
            'reports' => $reports,
            'types' => LostFoundType::cases(),
            'q' => $q,
            'type' => $type,
        ]);
    }

    public function create(): View
    {
        $this->authorizeManage();

        return view('lost-found.create', [
            'report' => new LostFoundReport([
                'type' => LostFoundType::Lost,
                'reported_at' => now()->toDateString(),
            ]),
            'people' => Person::query()->orderBy('name')->get(),
            'animals' => Animal::query()->orderBy('name')->get(),
        ]);
    }

    public function store(StoreLostFoundReportRequest $request): RedirectResponse
    {
        $report = LostFoundReport::query()->create($this->validatedReportData($request));

        return redirect()
            ->route('lost-found.show', $report)
            ->with('status', 'Lost/found report created.');
    }

    public function show(LostFoundReport $lostFoundReport): View
    {
        $lostFoundReport->load(['person', 'matchedAnimal']);

        return view('lost-found.show', [
            'report' => $lostFoundReport,
            'matches' => $this->matcher->matchesFor($lostFoundReport),
        ]);
    }

    public function edit(LostFoundReport $lostFoundReport): View
    {
        $this->authorizeManage();

        return view('lost-found.edit', [
            'report' => $lostFoundReport,
            'people' => Person::query()->orderBy('name')->get(),
            'animals' => Animal::query()->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateLostFoundReportRequest $request, LostFoundReport $lostFoundReport): RedirectResponse
    {
        $lostFoundReport->update($this->validatedReportData($request));

        return redirect()
            ->route('lost-found.show', $lostFoundReport)
            ->with('status', 'Lost/found report updated.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedReportData(StoreLostFoundReportRequest $request): array
    {
        $data = $request->safe()->all();

        foreach (['person_id', 'matched_animal_id', 'colour', 'markings', 'identifying_code', 'location_area', 'notes'] as $nullable) {
            if (($data[$nullable] ?? null) === '') {
                $data[$nullable] = null;
            }
        }

        return $data;
    }

    private function authorizeManage(): void
    {
        abort_unless($this->requestUserCanManage(), 403);
    }

    private function requestUserCanManage(): bool
    {
        $role = request()->user()?->role;

        return $role !== null && $role->canManageLostFound();
    }
}
