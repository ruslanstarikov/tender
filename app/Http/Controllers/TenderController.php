<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use App\Models\TenderMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TenderController extends Controller
{
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
            // We'll validate media in a custom loop below
        ]);

        // 2) Create the Tender record
        // Assume 'customer_id' is hard‐coded or pulled from a select. For demo, we'll use the first customer.
        $customer = \App\Models\Customer::first();
        $tender = Tender::create([
            'customer_id'         => $customer->id,
            'project_title'       => $validated['project_title'],
            'tender_status'       => 'pending',
            'property_address'    => $validated['address'],
            'full_address'        => $validated['address'],
            'suburb'              => $validated['suburb'],
            'state'               => $validated['state'],
            'owner_email'         => $customer->email,
            'owner_phone'         => $customer->phone,
            'post_code'           => $validated['post_code'],
            'work_start_datetime' => $validated['work_start_datetime'],
            'work_end_datetime'   => $validated['work_end_datetime'],
            'premises_entry_info' => $validated['premises_entry_info'] ?? null,
            'frame_details'       => $validated['frame_details'] ?? null,
        ]);

        // 3) Handle multiple file uploads:
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

        // 4) Redirect to the preview page
        return redirect()->route('admin.tenders.show', $tender->id)
            ->with('success', 'Tender created successfully.');
    }

    /**
     * Preview a single Tender and its media.
     */
    public function show(Tender $tender)
    {
        // Eager‐load media
        $tender->load('media');
        return view('tenders.show', compact('tender'));
    }
}
