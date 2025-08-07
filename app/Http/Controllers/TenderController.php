<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use App\Models\TenderMedia;
use App\Models\TenderFrame;
use App\Models\FrameType;
use App\Models\WindowTemplate;
use App\Models\Window;
use App\Models\WindowCell;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TenderController extends Controller
{
    /**
     * Display a listing of tenders.
     */
    public function index()
    {
        $tenders = Tender::with('customer')->latest()->paginate(10);
        return view('admin.tenders.index', compact('tenders'));
    }

    /**
     * Show the form to create a new Tender (with media uploads).
     */
    public function create()
    {
        return view('tenders.create');
    }

    /**
     * Store a newly created Tender and its media.
     */
    public function store(Request $request)
    {
        // 1) Validate all tender fields.
        $validated = $request->validate([
            'project_title'       => 'required|string|max:255',
            'address'             => 'required|string|max:255',
            'suburb'              => 'required|string|max:100',
            'state'               => 'required|string|max:100',
            'post_code'           => 'required|string|max:20',
            'work_start_datetime' => 'required|date',
            'work_end_datetime'   => 'required|date|after_or_equal:work_start_datetime',
            'premises_entry_info' => 'nullable|string',
            'frame_details'       => 'nullable|string',
            'frames'              => 'nullable|array',
            'frames.*.frame_type_id' => 'required|exists:frame_types,id',
            'frames.*.width'      => 'required|numeric|min:100|max:5000',
            'frames.*.height'     => 'required|numeric|min:100|max:3000',
            'frames.*.quantity'   => 'required|integer|min:1|max:100',
            // Window validation rules
            'windows'             => 'nullable|array',
            'windows.*.template_id' => 'required|exists:window_templates,id',
            'windows.*.label'     => 'nullable|string|max:255',
            'windows.*.width_mm'  => 'required|integer|min:100|max:5000',
            'windows.*.height_mm' => 'required|integer|min:100|max:3000',
            'windows.*.notes'     => 'nullable|string|max:1000',
            'windows.*.cells'     => 'nullable|array',
            'windows.*.cells.*.cell_index' => 'required|integer|min:0',
            'windows.*.cells.*.open_left' => 'nullable|in:0,1,true,false',
            'windows.*.cells.*.open_right' => 'nullable|in:0,1,true,false',
            'windows.*.cells.*.open_top' => 'nullable|in:0,1,true,false',
            'windows.*.cells.*.open_bottom' => 'nullable|in:0,1,true,false',
            'windows.*.cells.*.slide_left' => 'nullable|in:0,1,true,false',
            'windows.*.cells.*.slide_right' => 'nullable|in:0,1,true,false',
            'windows.*.cells.*.slide_top' => 'nullable|in:0,1,true,false',
            'windows.*.cells.*.slide_bottom' => 'nullable|in:0,1,true,false',
            'windows.*.cells.*.folding_left' => 'nullable|in:0,1,true,false',
            'windows.*.cells.*.folding_right' => 'nullable|in:0,1,true,false',
            'windows.*.cells.*.folding_top' => 'nullable|in:0,1,true,false',
            'windows.*.cells.*.folding_bottom' => 'nullable|in:0,1,true,false',
            // We'll validate media in a custom loop below
        ]);

        // 2) Create the Tender record
        // Try to get the first customer, but make it nullable for now
        $customer = \App\Models\Customer::first();
        $tender = Tender::create([
            'customer_id'         => $customer?->id,
            'project_title'       => $validated['project_title'],
            'tender_status'       => 'pending',
            'property_address'    => $validated['address'],
            'full_address'        => $validated['address'],
            'suburb'              => $validated['suburb'],
            'state'               => $validated['state'],
            'owner_email'         => $customer?->email,
            'owner_phone'         => $customer?->phone,
            'post_code'           => $validated['post_code'],
            'work_start_datetime' => $validated['work_start_datetime'],
            'work_end_datetime'   => $validated['work_end_datetime'],
            'premises_entry_info' => $validated['premises_entry_info'] ?? null,
            'frame_details'       => $validated['frame_details'] ?? null,
        ]);

        // 3) Handle frame selections
        if (isset($validated['frames']) && is_array($validated['frames'])) {
            foreach ($validated['frames'] as $frameData) {
                TenderFrame::create([
                    'tender_id' => $tender->id,
                    'frame_type_id' => $frameData['frame_type_id'],
                    'width' => $frameData['width'],
                    'height' => $frameData['height'],
                    'quantity' => $frameData['quantity'],
                ]);
            }
        }

        // 3.5) Handle window configurations
        if (isset($validated['windows']) && is_array($validated['windows'])) {
            foreach ($validated['windows'] as $windowData) {
                $window = Window::create([
                    'tender_id' => $tender->id,
                    'template_id' => $windowData['template_id'],
                    'label' => $windowData['label'],
                    'width_mm' => $windowData['width_mm'],
                    'height_mm' => $windowData['height_mm'],
                    'notes' => $windowData['notes'],
                ]);

                // Create window cells with all configurations
                if (isset($windowData['cells']) && is_array($windowData['cells'])) {
                    foreach ($windowData['cells'] as $cellData) {
                        // Find the corresponding template cell
                        $templateCell = \App\Models\TemplateCell::where('template_id', $windowData['template_id'])
                            ->where('cell_index', $cellData['cell_index'])
                            ->first();
                            
                        if ($templateCell) {
                            WindowCell::create([
                                'window_id' => $window->id,
                                'template_cell_id' => $templateCell->id,
                                'open_left' => filter_var($cellData['open_left'] ?? false, FILTER_VALIDATE_BOOLEAN),
                                'open_right' => filter_var($cellData['open_right'] ?? false, FILTER_VALIDATE_BOOLEAN),
                                'open_top' => filter_var($cellData['open_top'] ?? false, FILTER_VALIDATE_BOOLEAN),
                                'open_bottom' => filter_var($cellData['open_bottom'] ?? false, FILTER_VALIDATE_BOOLEAN),
                                'slide_left' => filter_var($cellData['slide_left'] ?? false, FILTER_VALIDATE_BOOLEAN),
                                'slide_right' => filter_var($cellData['slide_right'] ?? false, FILTER_VALIDATE_BOOLEAN),
                                'slide_top' => filter_var($cellData['slide_top'] ?? false, FILTER_VALIDATE_BOOLEAN),
                                'slide_bottom' => filter_var($cellData['slide_bottom'] ?? false, FILTER_VALIDATE_BOOLEAN),
                                'folding_left' => filter_var($cellData['folding_left'] ?? false, FILTER_VALIDATE_BOOLEAN),
                                'folding_right' => filter_var($cellData['folding_right'] ?? false, FILTER_VALIDATE_BOOLEAN),
                                'folding_top' => filter_var($cellData['folding_top'] ?? false, FILTER_VALIDATE_BOOLEAN),
                                'folding_bottom' => filter_var($cellData['folding_bottom'] ?? false, FILTER_VALIDATE_BOOLEAN),
                            ]);
                        }
                    }
                }
            }
        }

        // 4) Handle multiple file uploads:
        //
        //    We expect the form to send media as:
        //      media[inside][photos][] (image/*),
        //      media[inside][videos][] (video/*),
        //      media[parking][photos][],
        //      media[parking][videos][],
        //      media[outside][photos][],
        //      media[outside][videos][],
        //      media[storage][photos][],
        //      media[storage][videos][],
        //      media[polycam][models][] (could be any file type, but treat as 'polycam').
        //
        //    Loop through each category & type, store files, create TenderMedia records.

        $categories = ['inside', 'parking', 'outside', 'storage'];
        $mediaInput = $request->file('media', []);

        foreach ($categories as $category) {
            // PHOTOS in this category
            if (isset($mediaInput[$category]['photos'])) {
                foreach ($mediaInput[$category]['photos'] as $photo) {
                    if ($photo && $photo->isValid()) {
                        $path = $photo->store("tenders/{$tender->id}/{$category}/photos", 'public');
                        TenderMedia::create([
                            'tender_id'  => $tender->id,
                            'media_type' => 'photo',
                            'category'   => $category,
                            'file_path'  => $path,
                        ]);
                    }
                }
            }

            // VIDEOS in this category
            if (isset($mediaInput[$category]['videos'])) {
                foreach ($mediaInput[$category]['videos'] as $video) {
                    if ($video && $video->isValid()) {
                        $path = $video->store("tenders/{$tender->id}/{$category}/videos", 'public');
                        TenderMedia::create([
                            'tender_id'  => $tender->id,
                            'media_type' => 'video',
                            'category'   => $category,
                            'file_path'  => $path,
                        ]);
                    }
                }
            }
        }

        // POLYCAM MODELS (all go under media_type = 'polycam', category = 'polycam')
        if ($request->hasFile('media.polycam.models')) {
            foreach ($request->file('media.polycam.models') as $glbFile) {
                if ($glbFile->isValid()) {
                    // Store under "public/tenders/{id}/polycam/"
                    $path = $glbFile->store("tenders/{$tender->id}/polycam", 'public');

                    TenderMedia::create([
                        'tender_id'  => $tender->id,
                        'media_type' => 'polycam',
                        'category'   => 'polycam',
                        'file_path'  => $path, // e.g. "tenders/5/polycam/model1.glb"
                    ]);
                }
            }
        }

        // 5) Redirect to the preview page
        return redirect()->route('admin.tenders.show', $tender->id)
            ->with('success', 'Tender created successfully.');
    }

    /**
     * Preview a single Tender and its media.
     */
    public function show(Tender $tender)
    {
        // Eager‐load media, frames, and windows with all their relationships
        $tender->load([
            'media', 
            'frames.frameType',
            'windows.template.templateCells',
            'windows.windowCells.templateCell'
        ]);

        // Transform windows data for JavaScript consumption
        $windowsData = $tender->windows->map(function($window) {
            return [
                'id' => $window->id,
                'width_mm' => $window->width_mm,
                'height_mm' => $window->height_mm,
                'label' => $window->label,
                'template' => [
                    'id' => $window->template->id,
                    'name' => $window->template->name,
                    'cells' => $window->template->templateCells->map(function($cell) {
                        return [
                            'id' => $cell->id,
                            'cell_index' => $cell->cell_index,
                            'x' => (float) $cell->x,
                            'y' => (float) $cell->y,
                            'width_ratio' => (float) $cell->width_ratio,
                            'height_ratio' => (float) $cell->height_ratio,
                        ];
                    })->values()
                ],
                'window_cells' => $window->windowCells->map(function($wc) {
                    return [
                        'template_cell' => [
                            'cell_index' => $wc->templateCell->cell_index
                        ],
                        'open_left' => (bool) $wc->open_left,
                        'open_right' => (bool) $wc->open_right,
                        'open_top' => (bool) $wc->open_top,
                        'open_bottom' => (bool) $wc->open_bottom,
                        'slide_left' => (bool) $wc->slide_left,
                        'slide_right' => (bool) $wc->slide_right,
                        'slide_top' => (bool) $wc->slide_top,
                        'slide_bottom' => (bool) $wc->slide_bottom,
                        'folding_left' => (bool) $wc->folding_left,
                        'folding_right' => (bool) $wc->folding_right,
                        'folding_top' => (bool) $wc->folding_top,
                        'folding_bottom' => (bool) $wc->folding_bottom,
                    ];
                })->values()
            ];
        })->values();

        return view('tenders.show', compact('tender', 'windowsData'));
    }

    /**
     * API endpoint to get all frame types.
     */
    public function getFrameTypes()
    {
        $frameTypes = FrameType::orderBy('type')->orderBy('panels')->get();
        
        return response()->json($frameTypes->map(function ($frameType) {
            return [
                'id' => $frameType->id,
                'filename' => $frameType->filename,
                'type' => $frameType->type,
                'panels' => $frameType->panels,
                'config' => $frameType->config,
                'config_string' => $frameType->config_string,
                'display_name' => $frameType->display_name,
                'image_url' => $frameType->image_url,
            ];
        }));
    }

    /**
     * API endpoint to get all window templates.
     */
    public function getWindowTemplates()
    {
        $templates = WindowTemplate::with('templateCells')->orderBy('name')->get();
        
        return response()->json($templates->map(function ($template) {
            return [
                'id' => $template->id,
                'name' => $template->name,
                'cells' => $template->templateCells->map(function ($cell) {
                    return [
                        'id' => $cell->id,
                        'cell_index' => $cell->cell_index,
                        'x' => (float) $cell->x,
                        'y' => (float) $cell->y,
                        'width_ratio' => (float) $cell->width_ratio,
                        'height_ratio' => (float) $cell->height_ratio,
                    ];
                })->sortBy('cell_index')->values(),
            ];
        }));
    }
}
