<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Models\Job;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class JobController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $jobs = Job::paginate(9);

        return view('jobs.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View|RedirectResponse
    {
        return view('jobs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateJobRequest $request)
    {
        // Get validated data from request
        $validatedData = $request->validated();
        // Harcoded user_id
        $validatedData['user_id'] = Auth::user()->id;

        // Check for imate
        if ($request->hasFile('company_logo')) {
            // Store the file and get path
            $path = $request->file('company_logo')->store('logos', 'public');

            $validatedData['company_logo'] = $path;
        }

        // Submit to database
        Job::create($validatedData);

        return redirect()->route('jobs.index')->with('success', 'Job listing created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job): View
    {
        return view('jobs.show')->with('job', $job);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Job $job): View
    {
        $this->authorize('edit', $job);

        return view('jobs.edit')->with('job', $job);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobRequest $request, Job $job): string
    {
        // Get validated data from request
        $validatedData = $request->validated();

        // Check for imate
        if ($request->hasFile('company_logo')) {
            // Delete old logo
            Storage::delete('public/logos/' . basename($job->company_logo));
            // Store the file and get path
            $path = $request->file('company_logo')->store('logos', 'public');

            $validatedData['company_logo'] = $path;
        }

        // Submit to database
        $job->update($validatedData);

        return redirect()->route('jobs.index')->with('success', 'Job listing created successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $job): RedirectResponse
    {
        // Check if user is authorize
        $this->authorize('delete', $job);

        // If logo, then delete it
        if ($job->company_logo) {
            Storage::delete('public/logos/' . $job->company_logo);
        }

        $job->delete();

        // Check if request came from the dashboard
        if (request()->query('from') == 'dashboard') {
            return redirect()->route('dashboard')->with('success', 'Job listing deleted successfully!');
        }

        return redirect()->route('jobs.index')->with('success', 'Job listing deleted successfully!');
    }
}
