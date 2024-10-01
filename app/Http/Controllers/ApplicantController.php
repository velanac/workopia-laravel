<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateApplicationRequest;
use App\Models\Job;
use App\Models\Applicant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class ApplicantController extends Controller
{
    // @desc Store new job application
    // @route POST /jobs/{job}/apply
    public function store(CreateApplicationRequest $request, Job $job): RedirectResponse
    {
        // Check if the user has already applied
        $existingApplicaion = Applicant::where('job_id', $job->id)->where('user_id', Auth::user()->id)->exists();

        if ($existingApplicaion) {
            return redirect()->back()->with('error', 'You have already applied to this job');
        }

        // Validate data
        $validateData = $request->validated();

        // Handle resume upload
        if ($request->hasFile('resume')) {
            $path = $request->file('resume')->store('resumes', 'public');
            $validateData['resume_path'] = $path;
        }

        // Store the applicaiton
        $application = new Applicant($validateData);
        $application->job_id = $job->id;
        $application->user_id = Auth::user()->id;
        $application->save();

        return redirect()->back()->with('success', 'Your applicaiton has been submitted');
    }

    // @desc Delete job application
    // @route Delete /applicants/{applicant}
    public function destory($id): RedirectResponse
    {
        // Validate data
        $application = Applicant::findOrFail($id);
        $application->delete();

        return redirect()->route('dashboard')->with('success', 'Aplicaiton deleted successfully!');
    }
}
