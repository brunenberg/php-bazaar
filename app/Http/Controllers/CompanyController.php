<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;

class CompanyController extends Controller
{
    function updateInfo(Request $request)
    {
        $validatedData = $request->validate([
            'winkelnaam' => 'required|string|max:255',
            'winkelbeschrijving' => 'nullable|string',
            'slug' => 'required|string|max:50',
            'image' => 'nullable|image',
            'achtergrondkleur' => 'nullable|string|max:7',
            'tekstkleur' => 'nullable|string|max:7',
        ]);

        $company = Company::find($request->input('companyId'));
        $company->name = $validatedData['winkelnaam'];
        $company->description = $validatedData['winkelbeschrijving'];
        $company->slug = $validatedData['slug'];
        $company->background_color = $validatedData['achtergrondkleur'];
        $company->text_color = $validatedData['tekstkleur'];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);
            $company->image = $name;
        }

        $company->save();
        return redirect()->back()->with('success', __('messages.information_updated'));
    }

    public function show(string $slug = null)
    {
        $company = Company::slug($slug)->first();

        if (!$company) {
            abort(404);
        }
        $templates = $company->templates;

        return view('company.show', compact('company', 'templates'));
    }



    public function addTemplate(Request $request)
    {
        $company = Company::find($request->input('companyId'));
        $templateId = $request->input('templateId');
        $order = $company->templates()->max('order') + 1;
        $company->templates()->attach($templateId, ['order' => $order]);

        return redirect()->back()->with('success', __('messages.template_added'));;
    }

    public function removeTemplate(Request $request)
    {
        $pivotId = $request->input('pivotId');
        $company = Company::find($request->input('companyId'));
        $company->templates()->wherePivot('id', $pivotId)->detach();

        return redirect()->back()->with('success', __('messages.template_removed'));
    }

    public function orderUp(Request $request)
    {
        $pivotId = $request->input('pivotId');
        $company = auth()->user()->company;
        $template = $company->templates()->wherePivot('id', $pivotId)->first(); // Haal het specifieke template op
        $currentOrder = $template->pivot->order; // Gebruik pivot om het order op te halen

        // Zoek het template met een order één lager dan het huidige template
        $targetTemplate = $company->templates()->wherePivot('order', $currentOrder - 1)->first();

        if ($targetTemplate) {
            // Verhoog het order van het target template
            $targetTemplate->pivot->update(['order' => $currentOrder]);
            // Verlaag het order van het geselecteerde template
            $template->pivot->update(['order' => $currentOrder - 1]);
        }

        return redirect()->back();
    }

    public function orderDown(Request $request)
    {
        $pivotId = $request->input('pivotId');
        $company = auth()->user()->company;
        $template = $company->templates()->wherePivot('id', $pivotId)->first(); // Haal het specifieke template op
        $currentOrder = $template->pivot->order; // Gebruik pivot om het order op te halen

        // Zoek het template met een order één hoger dan het huidige template
        $targetTemplate = $company->templates()->wherePivot('order', $currentOrder + 1)->first();

        if ($targetTemplate) {
            // Verlaag het order van het target template
            $targetTemplate->pivot->update(['order' => $currentOrder]);
            // Verhoog het order van het geselecteerde template
            $template->pivot->update(['order' => $currentOrder + 1]);
        }

        return redirect()->back();
    }

    public function allCompanies(Request $request)
    {
        $sortBy = $request->query('sortBy', 'created_at');
        $sortOrder = $request->session()->get('sortOrder', 'asc');
        if ($request->session()->has('sortColumn') && $request->session()->get('sortColumn') === $sortBy) {
            $sortOrder = $sortOrder === 'asc' ? 'desc' : 'asc';
        } else {
            $sortOrder = 'asc';
        }

        $request->session()->put('sortColumn', $sortBy);
        $request->session()->put('sortOrder', $sortOrder);

        $filter = $request->query('filter');

        $query = Company::withCount('contracts')->orderBy('contracts_count', $sortOrder);

        if ($filter === 'no_contracts') {
            $query->has('contracts', '=', 0);
        }

        $companies = $query->paginate(10);

        return view('company.companies', compact('companies'));
    }
    
    public function addReview(Request $request)
    {
        $validatedData = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
            'company_id' => 'required|integer',
        ]);

        $company = Company::find($validatedData['company_id']);

        $existingReview = $company->reviews()->where('user_id', auth()->user()->id)->first();
        if ($existingReview) {
            return redirect()->back()->with('error', __('messages.already_placed_company_review'));
        }

        $company->reviews()->attach(auth()->user()->id, [
            'rating' => $validatedData['rating'],
            'review' => $validatedData['review']
        ]);

        return redirect()->back()->with('success', __('messages.review_added'));;
    }

    public function deleteReview(Request $request)
    {
        $validatedData = $request->validate([
            'company_id' => 'required|integer'
        ]);

        $company = Company::find($validatedData['company_id']);
        $company->reviews()->detach(auth()->user()->id);

        return redirect()->back()->with('success', __('messages.review_removed'));;
    }


        


    public function downloadContract(Request $request)
    {
        $company = Company::find($request->input('company_id'));
        $dompdf = new Dompdf();
        $html = view('company.contract', compact('company'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('contract.pdf');
    }

    public function uploadContract(Request $request)
    {
        $request->validate([
            'contract' => 'required|mimes:pdf',
        ]);
        $company = Company::find($request->input('company_id'));
        $contract = $request->file('contract');
        $name = time() . '.' . $contract->getClientOriginalExtension();
        $destinationPath = public_path('/contracts');
        $contract->move($destinationPath, $name);
        // save the file path in the database in contracts table
        $company->contracts()->create(['path' => $name]);
        $company->save();

        return redirect()->back();
    }

    public function acceptContract(Request $request)
    {
        $company = auth()->user()->company;
        $contract = $company->contracts()->find($request->input('contract_id'));
        $contract->signed = true;
        $contract->save();

        return redirect()->back();
    }

    public function rejectContract(Request $request)
    {
        $company = auth()->user()->company;
        $contract = $company->contracts()->find($request->input('contract_id'));
        $contract->signed = false;
        $contract->save();

        return redirect()->back();
    }
}
