<?php

namespace App\Livewire\Admin;

use App\Models\PageHero;
use App\Models\PageHeroSlide;
use App\Services\ImageCompressionService;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class PageHeroManager extends Component
{
    use WithFileUploads;

    public ?int $selectedHeroId = null;

    public $image = null;

    public string $headline = '';

    public string $subheadline = '';

    public string $cta_label = '';

    public string $cta_url = '';

    public string $overlay_style = 'dark_bottom';

    public string $text_align = 'center';

    public ?int $editingSlideId = null;

    public bool $showModal = false;

    public function mount(): void
    {
        if (auth()->user()?->role !== 'admin') {
            abort(403);
        }

        $this->selectedHeroId = PageHero::query()->orderBy('label')->value('id');
    }

    public function updatedSelectedHeroId(): void
    {
        $this->closeModal();
    }

    public function openAddModal(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function startEdit(int $slideId): void
    {
        $slide = PageHeroSlide::query()->findOrFail($slideId);
        if ((int) $slide->page_hero_id !== (int) $this->selectedHeroId) {
            abort(403);
        }

        $this->resetValidation();
        $this->editingSlideId = $slide->id;
        $this->headline = $slide->headline ?? '';
        $this->subheadline = $slide->subheadline ?? '';
        $this->cta_label = $slide->cta_label ?? '';
        $this->cta_url = $slide->cta_url ?? '';
        $this->overlay_style = $slide->overlay_style ?: 'dark_bottom';
        $this->text_align = $slide->text_align ?: 'center';
        $this->image = null;
        $this->showModal = true;
    }

    public function resetForm(): void
    {
        $this->editingSlideId = null;
        $this->image = null;
        $this->headline = '';
        $this->subheadline = '';
        $this->cta_label = '';
        $this->cta_url = '';
        $this->overlay_style = 'dark_bottom';
        $this->text_align = 'center';
        $this->resetValidation();
    }

    public function saveSlide(): void
    {
        if (! $this->selectedHeroId) {
            session()->flash('error', 'No page is selected.');

            return;
        }

        $hero = PageHero::query()->findOrFail($this->selectedHeroId);

        $rules = [
            'headline' => 'nullable|string|max:255',
            'subheadline' => 'nullable|string|max:2000',
            'cta_label' => 'nullable|string|max:120',
            'cta_url' => [
                'nullable',
                'string',
                'max:2048',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if ($value === '' || $value === null) {
                        return;
                    }
                    if (! filter_var($value, FILTER_VALIDATE_URL) && ! str_starts_with($value, '/')) {
                        $fail('The link must be a valid URL or a path starting with /.');
                    }
                },
            ],
            'overlay_style' => 'required|in:dark_bottom,dark_full,gradient_emerald,none',
            'text_align' => 'required|in:center,left',
        ];

        if ($this->editingSlideId) {
            $rules['image'] = 'nullable|image|max:10240';
        } else {
            $rules['image'] = 'required|image|max:10240';
        }

        $this->validate($rules);

        $compress = app(ImageCompressionService::class);

        if ($this->editingSlideId) {
            $slide = PageHeroSlide::query()->findOrFail($this->editingSlideId);
            if ((int) $slide->page_hero_id !== (int) $hero->id) {
                abort(403);
            }

            $data = [
                'headline' => $this->headline !== '' ? $this->headline : null,
                'subheadline' => $this->subheadline !== '' ? $this->subheadline : null,
                'cta_label' => $this->cta_label !== '' ? $this->cta_label : null,
                'cta_url' => $this->cta_url !== '' ? $this->cta_url : null,
                'overlay_style' => $this->overlay_style,
                'text_align' => $this->text_align,
            ];

            if ($this->image) {
                $this->deleteStoredImageIfLocal($slide->image_path);
                $data['image_path'] = $compress->storeCompressed($this->image, 'page-heroes', 1200);
            }

            $slide->update($data);
            session()->flash('success', 'Slide updated.');
        } else {
            $maxOrder = (int) PageHeroSlide::query()->where('page_hero_id', $hero->id)->max('sort_order');

            PageHeroSlide::query()->create([
                'page_hero_id' => $hero->id,
                'image_path' => $compress->storeCompressed($this->image, 'page-heroes', 1200),
                'headline' => $this->headline !== '' ? $this->headline : null,
                'subheadline' => $this->subheadline !== '' ? $this->subheadline : null,
                'cta_label' => $this->cta_label !== '' ? $this->cta_label : null,
                'cta_url' => $this->cta_url !== '' ? $this->cta_url : null,
                'overlay_style' => $this->overlay_style,
                'text_align' => $this->text_align,
                'sort_order' => $maxOrder + 1,
                'is_active' => true,
            ]);
            session()->flash('success', 'Slide added.');
        }

        $this->closeModal();
    }

    public function deleteSlide(int $slideId): void
    {
        $slide = PageHeroSlide::query()->findOrFail($slideId);
        if ((int) $slide->page_hero_id !== (int) $this->selectedHeroId) {
            abort(403);
        }

        $this->deleteStoredImageIfLocal($slide->image_path);
        $slide->delete();
        session()->flash('success', 'Slide removed.');
        $this->closeModal();
    }

    public function toggleActive(int $slideId): void
    {
        $slide = PageHeroSlide::query()->findOrFail($slideId);
        if ((int) $slide->page_hero_id !== (int) $this->selectedHeroId) {
            abort(403);
        }

        $slide->update(['is_active' => ! $slide->is_active]);
    }

    public function moveUp(int $slideId): void
    {
        $slide = PageHeroSlide::query()->findOrFail($slideId);
        if ((int) $slide->page_hero_id !== (int) $this->selectedHeroId) {
            abort(403);
        }

        $prev = PageHeroSlide::query()
            ->where('page_hero_id', $slide->page_hero_id)
            ->where('sort_order', '<', $slide->sort_order)
            ->orderByDesc('sort_order')
            ->first();

        if (! $prev) {
            return;
        }

        $tmp = $slide->sort_order;
        $slide->update(['sort_order' => $prev->sort_order]);
        $prev->update(['sort_order' => $tmp]);
    }

    public function moveDown(int $slideId): void
    {
        $slide = PageHeroSlide::query()->findOrFail($slideId);
        if ((int) $slide->page_hero_id !== (int) $this->selectedHeroId) {
            abort(403);
        }

        $next = PageHeroSlide::query()
            ->where('page_hero_id', $slide->page_hero_id)
            ->where('sort_order', '>', $slide->sort_order)
            ->orderBy('sort_order')
            ->first();

        if (! $next) {
            return;
        }

        $tmp = $slide->sort_order;
        $slide->update(['sort_order' => $next->sort_order]);
        $next->update(['sort_order' => $tmp]);
    }

    protected function deleteStoredImageIfLocal(?string $path): void
    {
        if (! $path || str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function render()
    {
        $heroes = PageHero::query()->orderBy('label')->get();
        $hero = PageHero::query()->find($this->selectedHeroId);
        $managedSlides = $hero
            ? $hero->slides()->orderBy('sort_order')->orderBy('id')->get()
            : collect();

        return view('livewire.admin.page-hero-manager', [
            'heroes' => $heroes,
            'managedSlides' => $managedSlides,
            'currentHero' => $hero,
        ]);
    }
}
