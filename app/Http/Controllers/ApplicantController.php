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
}
